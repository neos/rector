<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodeHiddenInIndexRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite node.hiddenInIndex and q(node).property("_hiddenInIndex") to node.property(\'hiddenInIndex\')', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.hiddenInIndex([^\w(]|$)/',
                '$1.property(\'hiddenInIndex\')$2',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.hiddenInIndex([^\w(]|$)/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.hiddenInIndex" to VARIABLE.property(\'hiddenInIndex\'). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )
            ->process(fn(string $eelExpression) => preg_replace(
                '/\.property\([^)]+_hiddenInIndex[^)]+\)/',
                '.property(\'hiddenInIndex\')',
                $eelExpression
            )
            )->getProcessedContent();
    }
}
