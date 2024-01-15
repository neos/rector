<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodeHiddenBeforeDateTimeRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite node.hiddenBeforeDateTime to q(node).property("enableAfterDateTime")', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode)\.hiddenBeforeDateTime/',
                'q($1).property("enableAfterDateTime")',
                $eelExpression
            ))
            ->process(fn(string $eelExpression) => preg_replace(
                '/.property\(["\']_hiddenBeforeDateTime["\']\)/',
                '.property("enableAfterDateTime")',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.hiddenBeforeDateTime/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.hiddenBeforeDateTime" to q(VARIABLE).property("enableAfterDateTime"). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )->getProcessedContent();
    }
}
