<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodePathRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite node.path to Neos.Node.path(node)', __CLASS__, 'some_class.fusion');
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.path/',
                'Neos.Node.path($1)',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.path$/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.path" to Neos.Node.path(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )->getProcessedContent();
    }
}
