<?php

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionContextCurrentRenderingModeRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite node.context.currentRenderingMode... to renderingMode...', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.context\.currentRenderingMode\.(name|title|fusionPath|options)/',
                'renderingMode.$2',
                $eelExpression
            ))
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.context\.currentRenderingMode\.edit/',
                'renderingMode.isEdit',
                $eelExpression
            ))
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.context\.currentRenderingMode\.preview/',
                'renderingMode.isPreview',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.context\.currentRenderingMode/',
                '// TODO 9.0 migration: Line %LINE: You very likely need to rewrite "VARIABLE.context.currentRenderingMode..." to "renderingMode...". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )->getProcessedContent();
    }
}
