<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodeDepthRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite node.depth and q(node).property("_depth") to Neos.Node.depth(node)', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/([a-zA-Z.]+)?(site|node|documentNode)\.depth([^\w(]|$)/',
                'Neos.Node.depth($1$2)$3',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.depth([^\w(]|$)/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.depth" to Neos.Node.depth(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )
            ->process(fn(string $eelExpression) => preg_replace(
                '/q\(([^)]+)\)\.property\([\'"]_depth[\'"]\)/',
                'Neos.Node.depth($1)',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.property\([\'"]_depth[\'"]\)/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "q(VARIABLE).property(\'_depth\')" to Neos.Node.depth(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )->getProcessedContent();
    }
}
