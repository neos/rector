<?php

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Neos\Rector\Utility\CodeSampleLoader;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Neos\Rector\Generic\ValueObject\FusionNodePropertyPathToWarningComment;
use Webmozart\Assert\Assert;
use Neos\Rector\Generic\ValueObject\FusionPrototypeNameReplacement;
use Neos\Rector\Generic\ValueObject\FusionPrototypeNameAddComment;

class FusionPrototypeNameAddCommentRector implements FusionRectorInterface, ConfigurableRectorInterface
{
    /**
     * @var FusionPrototypeNameAddComment[]
     */
    private array $fusionPrototypeNameAddComments;

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Add comment to file if prototype name matches at least once.', __CLASS__);
    }

    public function refactorFileContent(string $fileContent): string
    {
        foreach ($this->fusionPrototypeNameAddComments as $fusionPrototypeNameAddComment) {
            $matches = [];
            $pattern = '/(^|[=\s\(<\/])(' .$fusionPrototypeNameAddComment->name. ')([\s\{\)\/>]|$)/';
            preg_match($pattern, $fileContent, $matches);

            if (count($matches) > 0) {
                $comments[] = "// " . $fusionPrototypeNameAddComment->comment;
            }
        }

        if (count($comments) > 0){
            $fileContent = implode("\n", $comments) . "\n" . $fileContent;
        }

        return $fileContent;
    }

    /**
     * @param FusionPrototypeNameAddComment[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, FusionPrototypeNameAddComment::class);
        $this->fusionPrototypeNameAddComments = $configuration;
    }
}
