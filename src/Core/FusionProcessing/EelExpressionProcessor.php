<?php

namespace Neos\Rector\Core\FusionProcessing;

use Neos\Rector\Core\FusionProcessing\Helper\CustomObjectTreeParser;

class EelExpressionProcessor
{
    private function __construct(private readonly string $fileContent)
    {
    }

    public static function parse(string $fileContent): self
    {
        return new self($fileContent);
    }

    public function process(\Closure $processingFunction): string
    {
        $eelExpressions = CustomObjectTreeParser::findEelExpressions($this->fileContent);
        var_dump($eelExpressions);
        $afxExpressions = CustomObjectTreeParser::findAfxExpressions($this->fileContent);
        var_dump($afxExpressions);

        // parse
        // apply processing function on Eel expressions
        // render
        return $this->fileContent;
    }
}
