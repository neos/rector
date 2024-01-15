<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodeHiddenAfterDateTimeRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite node.hiddenAfterDateTime to q(node).property("disableAfterDateTime")', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode)\.hiddenAfterDateTime/',
                'q($1).property("disableAfterDateTime")',
                $eelExpression
            ))
            ->process(fn(string $eelExpression) => preg_replace(
                '/.property\(["\']_hiddenAfterDateTime["\']\)/',
                '.property("disableAfterDateTime")',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.hiddenAfterDateTime/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.hiddenAfterDateTime" to q(VARIABLE).property("disableAfterDateTime"). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )->getProcessedContent();
    }
}
