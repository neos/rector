<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodeHiddenRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite node.hidden and q(node).property("_hidden") to Neos.Node.isDisabled(node)', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.hidden\b(?!\.|\()/',
                'Neos.Node.isDisabled($1)',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.hidden\b(?!\.|\()/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.hidden" to Neos.Node.isDisabled(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )
            ->process(fn(string $eelExpression) => preg_replace(
                '/q\(([^)]+)\)\.property\([\'"]_hidden[\'"]\)/',
                'Neos.Node.isDisabled($1)',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.property\([\'"]_hidden[\'"]\)/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "q(VARIABLE).property(\'_hidden\')" to Neos.Node.isDisabled(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )->getProcessedContent();
    }
}
