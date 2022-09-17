<?php

namespace Neos\Rector\Core\FusionProcessing\Helper;

use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\AbstractPathValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\DslExpressionValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\EelExpressionValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Exception\ParserException;
use Neos\Rector\Core\FusionProcessing\FusionParser\Lexer;
use Neos\Rector\Core\FusionProcessing\FusionParser\ObjectTreeParser;

class CustomObjectTreeParser extends ObjectTreeParser
{
    /**
     * @var EelExpressionPosition[]
     */
    private array $foundEelExpressions = [];

    /**
     * @var AfxExpressionPosition[]
     */
    private array $foundAfxExpressions = [];

    /**
     * @throws ParserException
     */
    public static function findEelExpressions(string $sourceCode, ?string $contextPathAndFilename = null): EelExpressionPositions
    {
        $lexer = new Lexer($sourceCode);
        $parser = new self($lexer, $contextPathAndFilename);
        $parser->parseFusionFile();
        return EelExpressionPositions::fromArray($parser->foundEelExpressions);
    }

    /**
     * @return AfxExpressionPosition[]
     * @throws ParserException
     */
    public static function findAfxExpressions(string $sourceCode, ?string $contextPathAndFilename = null): array
    {
        $lexer = new Lexer($sourceCode);
        $parser = new self($lexer, $contextPathAndFilename);
        $parser->parseFusionFile();
        return $parser->foundAfxExpressions;
    }

    /**
     * @throws ParserException
     */
    protected function parsePathValue(): AbstractPathValue
    {
        $fromOffset = $this->lexer->getCursor();
        $result = parent::parsePathValue();
        if ($result instanceof EelExpressionValue) {
            $toOffset = $this->lexer->getCursor();
            $this->foundEelExpressions[] = new EelExpressionPosition($result->value, $fromOffset + 2, $toOffset - 1);
        }
        return $result;
    }

    /**
     * @throws ParserException
     */
    protected function parseDslExpression(): DslExpressionValue
    {
        $fromOffset = $this->lexer->getCursor();
        $result = parent::parseDslExpression();
        $toOffset = $this->lexer->getCursor();

        if ($result->identifier === 'afx') {
            $this->foundAfxExpressions[] = new AfxExpressionPosition($result->code, $fromOffset, $toOffset);
        }
        return $result;
    }
}
