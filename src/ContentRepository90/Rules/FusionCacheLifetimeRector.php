<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionCacheLifetimeRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Add comment if .cacheLifetime() is used.', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->addCommentsIfRegexMatches(
                '/\.cacheLifetime()/',
                '// TODO 9.0 migration: Line %LINE: You may need to remove ".cacheLifetime()" as this FlowQuery Operation has been removed. This is not needed anymore as the concept of timeable node visibility has changed. See https://github.com/neos/timeable-node-visibility'
            )->getProcessedContent();
    }
}
