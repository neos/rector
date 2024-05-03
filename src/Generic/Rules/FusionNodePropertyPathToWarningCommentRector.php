<?php

declare(strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\Core\FusionProcessing\AfxParser\AfxParserException;
use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\FusionParser\Exception\ParserException;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Core\FusionProcessing\Helper\RegexCommentTemplatePair;
use Neos\Rector\Generic\ValueObject\FusionNodePropertyPathToWarningComment;
use Neos\Rector\Utility\CodeSampleLoader;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

class FusionNodePropertyPathToWarningCommentRector implements FusionRectorInterface, ConfigurableRectorInterface
{

    use AllTraits;

    /** @var FusionNodePropertyPathToWarningComment[] */
    private array $fusionNodePropertyPathToWarningComments = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Adds a warning comment when the defined path is used within an Eel expression.', __CLASS__, [
            new FusionNodePropertyPathToWarningComment('removed', 'Line %LINE: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.'),
            new FusionNodePropertyPathToWarningComment('hiddenBeforeDateTime', 'Line %LINE: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.'),
            new FusionNodePropertyPathToWarningComment('hiddenAfterDateTime', 'Line %LINE: !! node.hiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.'),
            new FusionNodePropertyPathToWarningComment('foo.bar', 'Line %LINE: !! node.foo.bar is not supported anymore.'),
        ]);
    }

    /**
     * @throws ParserException
     * @throws AfxParserException
     */
    public function refactorFileContent(string $fileContent): string
    {

        $regexWarningMessagePairs = [];
        foreach ($this->fusionNodePropertyPathToWarningComments as $fusionNodePropertyPathToWarningComment) {

            // escape the fusion path separator "."
            $propertyPath = str_replace('.', '\.', $fusionNodePropertyPathToWarningComment->propertyPath);

            $regexWarningMessagePairs[] = new RegexCommentTemplatePair(
                "/(node|site|documentNode)\.$propertyPath/",
                (string)self::todoCommentAttribute($fusionNodePropertyPathToWarningComment->warningMessage)
            );

        }

        return EelExpressionTransformer::parse($fileContent)
            ->addCommentsIfRegexesMatch($regexWarningMessagePairs)
            ->getProcessedContent()
        ;

    }

    /**
     * @param FusionNodePropertyPathToWarningComment[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, FusionNodePropertyPathToWarningComment::class);
        $this->fusionNodePropertyPathToWarningComments = $configuration;
    }
}
