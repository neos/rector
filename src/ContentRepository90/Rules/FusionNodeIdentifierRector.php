<?php

declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionNodeIdentifierRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite "node.identifier" and "q(node).property(\'_identifier\')" to "q(node).id()"', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(fn(string $eelExpression) => preg_replace(
                '/(node|documentNode|site)\.identifier/',
                'q($1).id()',
                $eelExpression
            ))
            ->addCommentsIfRegexMatches(
                '/\.identifier/',
                '// TODO 9.0 migration: Line %LINE: You may need to rewrite "VARIABLE.identifier" to "q(VARIABLE).id()". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'
            )
            ->process(fn(string $eelExpression) => preg_replace(
                '/\.property\\((\'|")_identifier(\'|")\\)/',
                '.id()',
                $eelExpression
            )
            )->getProcessedContent();
    }
}
