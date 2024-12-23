<?php

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionContextGetWorkspaceNameRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite "node.context.workspaceName" to "node.workspaceName"', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.context\.workspaceName/',
                '$1.workspaceName',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.context\.workspaceName/',
                '// TODO 9.0 migration: Line %LINE: You very likely need to rewrite "VARIABLE.context.workspaceName" to "VARIABLE.workspaceName". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )->getProcessedContent();
    }
}
