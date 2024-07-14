<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodeNodeTypeRector implements FusionRectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite "node.nodeType" and "q(node).property(\'_nodeType\')" to "Neos.Node.getNodeType(node)"', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.nodeType\.name/',
                'q($1).nodeTypeName()',
                $eelExpression
            ))
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.nodeType\b/',
                'Neos.Node.getNodeType($1)',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.nodeType\b(?!\()/',
                '// TODO 9.0 migration: Line %LINE: You very likely need to rewrite "VARIABLE.nodeType" to "Neos.Node.getNodeType(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )
            ->process(fn(string $eelExpression) => preg_replace(
                '/\.property\\((\'|")_nodeType\.name(\'|")\\)/',
                '.nodeTypeName()',
                $eelExpression
            ))
            ->process(fn(string $eelExpression) => preg_replace(
                '/q\(([^)]+)\)\.property\\([\'"]_nodeType(\.[^\'"]*)?[\'"]\\)/',
                'Neos.Node.getNodeType($1)$2',
                $eelExpression
            ))
            ->getProcessedContent();
    }
}
