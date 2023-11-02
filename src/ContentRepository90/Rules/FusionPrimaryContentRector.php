<?php declare(strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Utility\CodeSampleLoader;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class FusionPrimaryContentRector implements FusionRectorInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Warn about removed "Neos.Neos:PrimaryContent"', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        $comment = '// TODO 9.0 migration: You need to rewrite "Neos.Neos:PrimaryContent" to "Neos.Neos:ContentCollection".';
        $regex = '/Neos\.Neos:PrimaryContent/';

        if (preg_match($regex, $fileContent) === 1){
            return $comment . "\n" . $fileContent;
        }

        return $fileContent;

    }
}
