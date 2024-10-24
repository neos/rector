<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodeIdentifierRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite "node.identifier" and "q(node).property(\'_identifier\')" to "node.nodeAggregateId"', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.identifier/',
                '$1.nodeAggregateId',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.identifier/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.identifier" to "VARIABLE.nodeAggregateId". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )
            ->process(fn(string $eelExpression) => preg_replace(
                '/q\(([^)]+)\)\.property\([\'"]_identifier[\'"]\)/',
                '$1.nodeAggregateId',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                    '/\.property\([\'"]_identifier[\'"]\)/',
                 '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.identifier" to "VARIABLE.nodeAggregateId". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )->getProcessedContent();
    }
}
