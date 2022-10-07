<?php

declare(strict_types=1);

namespace Neos\Rector\Core\FusionProcessing;

use Closure;
use Neos\Rector\Core\FusionProcessing\AfxParser\AfxParserException;
use Neos\Rector\Core\FusionProcessing\FusionParser\Exception\ParserException;
use Neos\Rector\Core\FusionProcessing\Helper\CustomObjectTreeParser;
use Neos\Rector\Core\FusionProcessing\Helper\EelExpressionPosition;
use Neos\Rector\Core\FusionProcessing\Helper\EelExpressionPositions;
use Neos\Rector\Core\FusionProcessing\Helper\PrecedingFusionFileComment;
use Neos\Rector\Core\FusionProcessing\Helper\RegexCommentTemplatePair;

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
    public function process(Closure $processingFunction): self
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

    /**
     * @throws ParserException
     * @throws AfxParserException
     */
    public function addCommentsIfRegexMatches(string $regex, string $comment): self
    {
        $regexCommentTemplatePair = new RegexCommentTemplatePair($regex, $comment);
        return $this->addCommentsIfRegexesMatch([$regexCommentTemplatePair]);
    }

    /**
     * @param RegexCommentTemplatePair[] $regexCommentTemplatePairs
     * @throws AfxParserException
     * @throws ParserException
     */
    public function addCommentsIfRegexesMatch(array $regexCommentTemplatePairs): self
    {
        $eelExpressions = $this->findAllEelExpressions();

        $comments = [];
        // fill $comments
        foreach ($regexCommentTemplatePairs as $regexCommentTemplatePair) {
            $eelExpressions->map(
                function (EelExpressionPosition $expressionPosition) use ($regexCommentTemplatePair, &$comments) {
                    $comments = array_merge(
                        $comments,
                        $this->getPrecedingCommentsForEelExpressionPosition($expressionPosition, $regexCommentTemplatePair->regex, $regexCommentTemplatePair->template)
                    );
                    return $expressionPosition;
                }
            );
        }

        $comments = $this->replaceLinePlaceholderWithinCommentTemplates($comments);

        if (count($comments)) {
            $precedingComments = array_map(fn($comment) => $comment->text, $comments);
            return new EelExpressionTransformer(implode("\n", $precedingComments) . "\n" . $this->fileContent);
        } else {
            return $this;
        }
    }

    /** @return PrecedingFusionFileComment[] */
    private function getPrecedingCommentsForEelExpressionPosition(EelExpressionPosition $expressionPosition, string $regex, string $template): array
    {
        $comments = [];

        $matches = [];
        if (preg_match_all($regex, $expressionPosition->eelExpression, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
            foreach ($matches as $match) {
                // $match[0][0] => the fully matched string
                // $match[0][1] => the start offset of the string
                $offsetOfFullMatch = $match[0][1];
                $lineNumberOfMatch = substr_count($this->fileContent, "\n", 0, $expressionPosition->fromOffset + $offsetOfFullMatch);
                // we add one because the number of counted new line characters will be one less than the actual line number
                $lineNumberOfMatch += 1;
                // avoid adding the same comment multiple times per line by using an explicit array key
                $commentKey = sha1($lineNumberOfMatch . $regex . $template);
                $comments[$commentKey] = new PrecedingFusionFileComment($lineNumberOfMatch, $template);
            }
        }

        return $comments;
    }

    /**
     * @param PrecedingFusionFileComment[] $comments
     * @return PrecedingFusionFileComment[]
     */
    private function replaceLinePlaceholderWithinCommentTemplates(array $comments): array
    {
        foreach ($comments as $comment) {
            $finalLineNumber = $comment->lineNumberOfMatch + count($comments);
            $comment->text = str_replace('%LINE', (string)$finalLineNumber, $comment->template);
        }
        return $comments;
    }

    /**
     * @throws ParserException
     * @throws AfxParserException
     */
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

    private function render(EelExpressionPositions $eelExpressions): string
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

    public function getProcessedContent(): string
    {
        return $this->fileContent;
    }
}
