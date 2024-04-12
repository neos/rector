<?php

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Neos\Rector\Utility\CodeSampleLoader;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Neos\Rector\Generic\ValueObject\FusionNodePropertyPathToWarningComment;
use Webmozart\Assert\Assert;
use Neos\Rector\Generic\ValueObject\FusionPrototypeNameReplacement;

class FusionReplacePrototypeNameRector implements FusionRectorInterface, ConfigurableRectorInterface
{

    /**
     * @var FusionPrototypeNameReplacement[]
     */
    private array $fusionPrototypeNameReplacements;

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Rewrite prototype names form e.g Foo.Bar:Boo to Boo.Bar:Foo', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        foreach ($this->fusionPrototypeNameReplacements as $fusionPrototypeNameReplacement) {
            $pattern = '/(^|[=\s\(<\/])(' .$fusionPrototypeNameReplacement->oldName. ')([\s\{\)\/>]|$)/';
            $replacement = '$1'.$fusionPrototypeNameReplacement->newName.'$3';
            $fileContent = preg_replace($pattern, $replacement, $fileContent);
        }

        return $fileContent;
    }

    /**
     * @param FusionNodePropertyPathToWarningComment[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, FusionPrototypeNameReplacement::class);
        $this->fusionPrototypeNameReplacements = $configuration;
    }
}
