<?php

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionFlowQueryContextRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Add comments for q(node).context({targetDimensions|currentDateTime|removedContentShown|inaccessibleContentShown: ...})', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->addCommentsIfRegexMatches(
                '/context\(\s*\{(.*)[\'"](targetDimensions|currentDateTime|removedContentShown|inaccessibleContentShown)[\'"](.*)\}\s*\)/',
                '// TODO 9.0 migration: Line %LINE: The "context()" FlowQuery operation has changed and does not support the following properties anymore: targetDimensions,currentDateTime,removedContentShown,inaccessibleContentShown.'
            )->getProcessedContent();
    }
}
