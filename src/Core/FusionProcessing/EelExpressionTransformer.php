<?php

namespace Neos\Rector\Core\FusionProcessing;

use Neos\Rector\Core\FusionProcessing\AfxParser\AfxParserException;
use Neos\Rector\Core\FusionProcessing\FusionParser\Exception\ParserException;
use Neos\Rector\Core\FusionProcessing\Helper\CustomObjectTreeParser;
use Neos\Rector\Core\FusionProcessing\Helper\EelExpressionPosition;
use Neos\Rector\Core\FusionProcessing\Helper\EelExpressionPositions;

class EelExpressionTransformer
{
    private function __construct(private readonly string $fileContent)
    {
    }

    public static function parse(string $fileContent): self
    {
        return new self($fileContent);
    }


    /**
     * @throws ParserException
     * @throws AfxParserException
     */
    public function process(\Closure $processingFunction): self
    {
        $eelExpressions = $this->findAllEelExpressions();

        // apply processing function on Eel expressions
        $eelExpressions = $eelExpressions->map(
            fn(EelExpressionPosition $expressionPosition) => $expressionPosition->withEelExpression(
                $processingFunction($expressionPosition->eelExpression)
            )
        );

        return new self($this->render($eelExpressions));
    }

    public function addWarningsIfRegexMatches(string $regexpString, string $warningMessage): self
    {
        $eelExpressions = $this->findAllEelExpressions();

        $warningLines = [];
        // fill $warningLines
        $eelExpressions->map(
            function(EelExpressionPosition $expressionPosition) use ($regexpString, $warningMessage, &$warningLines) {
                $matches = [];
                if (preg_match_all($regexpString, $expressionPosition->eelExpression, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
                    foreach ($matches as $match) {
                        // $match[0][0] => the fully matched string
                        // $match[0][1] => the start offset of the string
                        $offsetOfFullMatch = $match[0][1];
                        $lineNumber = substr_count($this->fileContent, "\n", 0, $expressionPosition->fromOffset + $offsetOfFullMatch);

                        $replacements = [
                            // we need to add 2 because line counts in IDEs are 1-based; and additionally, we add a line by this warning.
                            '%LINE' => $lineNumber + count($warningLines) + 2
                        ];
                        $warningLines[] = str_replace(array_keys($replacements), array_values($replacements), $warningMessage);

                    }
                }

                return $expressionPosition;
            }
        );

        if (count($warningLines)) {
            return new EelExpressionTransformer(implode("\n", $warningLines) . "\n" . $this->fileContent);
        } else {
            return $this;
        }
    }

    public function findAllEelExpressions(): EelExpressionPositions
    {
        $eelExpressions = CustomObjectTreeParser::findEelExpressions($this->fileContent);
        $afxExpressions = CustomObjectTreeParser::findAfxExpressions($this->fileContent);
        foreach ($afxExpressions as $afxExpression) {
            $parser = new AfxParser\Parser($afxExpression->code);
            $ast = $parser->parse();
            $eelExpressionsInAfx = self::findEelExpressionsInAfxAst($ast);
            $eelExpressionsInAfx = $eelExpressionsInAfx->withOffset($afxExpression->fromOffset);

            $eelExpressions = $eelExpressions->addAndSort($eelExpressionsInAfx);
        }
        return $eelExpressions;
    }

    /**
     * @param array $afxAst
     * @return EelExpressionPositions
     */
    private static function findEelExpressionsInAfxAst(array $afxAst): EelExpressionPositions
    {
        $result = [];
        foreach ($afxAst as $afxElement) {
            self::findEelExpressionsInAfxAstElement($afxElement, $result);
        }
        return EelExpressionPositions::fromArray($result);
    }

    /**
     * @param array $afxElement
     * @param EelExpressionPosition[] $result
     * @return void
     */
    private static function findEelExpressionsInAfxAstElement(array $afxElement, array &$result): void
    {
        if ($afxElement['type'] === 'text') {
            return;
        }
        if ($afxElement['type'] === 'string') {
            return;
        }
        if ($afxElement['type'] === 'node') {
            foreach ($afxElement['payload']['attributes'] as $attribute) {
                self::findEelExpressionsInAfxAstElement($attribute, $result);
            }
            foreach ($afxElement['payload']['children'] as $child) {
                self::findEelExpressionsInAfxAstElement($child, $result);
            }
        }
        if ($afxElement['type'] === 'prop') {
            self::findEelExpressionsInAfxAstElement($afxElement['payload'], $result);
        }
        if ($afxElement['type'] === 'spread') {
            // We found an Eel expression in a spread operation
            $result[] = new EelExpressionPosition(
                $afxElement['payload']['payload']['contents'],
                $afxElement['payload']['payload']['from'] + 1,
                $afxElement['payload']['payload']['to'] + 1
            );
            return;
        }

        if ($afxElement['type'] === 'expression') {
            // We found an Eel expression
            $result[] = new EelExpressionPosition(
                $afxElement['payload']['contents'],
                $afxElement['payload']['from'] + 1,
                $afxElement['payload']['to'] + 1
            );
        }
    }

    private function render(EelExpressionPositions $eelExpressions)
    {
        if ($eelExpressions->isEmpty()) {
            return $this->fileContent;
        }

        // Render [fusion] [1st eel expression]
        $processedFusionString = substr($this->fileContent, 0, $eelExpressions->first()->fromOffset);
        $processedFusionString .= $eelExpressions->first()->eelExpression;
        $toOffsetOfLastSeenEelExpression = $eelExpressions->first()->toOffset;

        // Render all Eel expressions (2...n)
        foreach ($eelExpressions->withoutFirst() as $eelExpression) {
            $processedFusionString .= substr($this->fileContent, $toOffsetOfLastSeenEelExpression, $eelExpression->fromOffset - $toOffsetOfLastSeenEelExpression);
            $processedFusionString .= $eelExpression->eelExpression;
            $toOffsetOfLastSeenEelExpression = $eelExpression->toOffset;
        }
        // render remaining Fusion in File
        $processedFusionString .= substr($this->fileContent, $toOffsetOfLastSeenEelExpression);

        return $processedFusionString;
    }

    public function getProcessedContent()
    {
        return $this->fileContent;
    }
}
