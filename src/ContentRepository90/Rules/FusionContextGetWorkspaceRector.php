<?php

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionContextGetWorkspaceRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Add comment to "node.context.workspace"', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->addCommentsIfRegexMatches(
                '/\.context\.workspace(\.\w)?\b/',
                '// TODO 9.0 migration: Line %LINE: You very likely need to rewrite "VARIABLE.context.workspace" as the "context" of nodes has been removed without a direct replacement in Neos 9. If you really need the workspace in fusion you need to create a dedicated helper yourself which should ideally do ALL the complex logic in php directly and return the computed result.'
            )->getProcessedContent();
    }
}
