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
        return CodeSampleLoader::fromFile('Fusion: Rewrite node.identifier to node.nodeAggregateId.value', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.identifier/',
                '$1.nodeAggregateId.value',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.identifier/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.identifier" to VARIABLE.nodeAggregateId.value. We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.property\\(\'_identifier\'\\)/',
                '$1.nodeAggregateId.value',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.property\\(\'_identifier\'\\)/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.identifier" to VARIABLE.nodeAggregateId.value. We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )->getProcessedContent();
    }
}
