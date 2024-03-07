<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodeContextPathRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite node.contextPath to Neos.Node.serializedNodeAddress(node)', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.contextPath/',
                'Neos.Node.serializedNodeAddress($1)',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.contextPath/',
                '// TODO 9.0 migration: Line %LINE: !! You very likely need to rewrite "VARIABLE.contextPath" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.property\\(\'_contextPath\'\\)/',
                'Neos.Node.serializedNodeAddress($1)',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.property\\(\'_contextPath\'\\)/',
                '// TODO 9.0 migration: Line %LINE: !! You very likely need to rewrite "VARIABLE.property(\'_contextPath\')" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )
            ->getProcessedContent();
    }
}
