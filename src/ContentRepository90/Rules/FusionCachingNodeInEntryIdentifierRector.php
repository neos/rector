<?php

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Core\FusionProcessing\Helper\FusionPath;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionCachingNodeInEntryIdentifierRector implements FusionRectorInterface
{

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite node to Neos.Caching.entryIdentifierForNode(...) in @cache.entryIdentifier segments', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        return EelExpressionTransformer::parse($fileContent)
            ->process(function (string $eelExpression, FusionPath $path) {
                if (!$path->containsSegments('__meta', 'cache', 'entryIdentifier')) {
                    return $eelExpression;
                }
                return preg_replace(
                    '/(?<!Neos\.Caching\.entryIdentifierForNode\()(node|documentNode|site)/',
                    'Neos.Caching.entryIdentifierForNode($1)',
                    $eelExpression
                );
            })->getProcessedContent();
    }
}
