<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodeLabelRector implements FusionRectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite "node.label" and "q(node).property(\'_label\')" to "Neos.Node.label(node)"', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.label/',
                'Neos.Node.label($1)',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.label\b(?!\()/',
                '// TODO 9.0 migration: Line %LINE: You very likely need to rewrite "VARIABLE.label" to "Neos.Node.label(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )
            ->process(fn(string $eelExpression) => preg_replace(
                '/q\(([^)]+)\)\.property\\([\'"]_label[\'"]\\)/',
                'Neos.Node.label($1)',
                $eelExpression
            ))
            ->getProcessedContent();
    }
}
