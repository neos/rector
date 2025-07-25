<?php

declare(strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\Core\FusionProcessing\AfxParser\AfxParserException;
use Neos\Rector\Core\FusionProcessing\EelExpressionTransformer;
use Neos\Rector\Core\FusionProcessing\Fusion\ObjectTreeParser\Exception\ParserException;
use Neos\Rector\Core\FusionProcessing\FusionRectorInterface;
use Neos\Rector\Core\FusionProcessing\Helper\RegexCommentTemplatePair;
use Neos\Rector\Utility\CodeSampleLoader;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;
use Neos\Rector\Generic\ValueObject\FusionFlowQueryNodePropertyToWarningComment;

class FusionFlowQueryNodePropertyToWarningCommentRector implements FusionRectorInterface, ConfigurableRectorInterface
{

    use AllTraits;

    /** @var FusionFlowQueryNodePropertyToWarningComment[] */
    private array $fusionFlowQueryNodePropertyToWarningComments = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Fusion: Adds a warning comment when the defined property is used within an FlowQuery "property()".', __CLASS__, [
            new FusionFlowQueryNodePropertyToWarningComment('_autoCreated', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_autoCreated")" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.')
        ]);
    }

    /**
     * @throws ParserException
     * @throws AfxParserException
     */
    public function refactorFileContent(string $fileContent): string
    {

        $regexWarningMessagePairs = [];
        foreach ($this->fusionFlowQueryNodePropertyToWarningComments as $flowQueryNodePropertyToWarningComment) {
            $property = $flowQueryNodePropertyToWarningComment->property;
            $regexWarningMessagePairs[] = new RegexCommentTemplatePair(
                "/\.property\(('|\")$property('|\")\)/",
                (string)self::todoCommentAttribute($flowQueryNodePropertyToWarningComment->warningMessage)
            );

        }

        return EelExpressionTransformer::parse($fileContent)
            ->addCommentsIfRegexesMatch($regexWarningMessagePairs)
            ->getProcessedContent();

    }

    /**
     * @param FusionFlowQueryNodePropertyToWarningComment[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, FusionFlowQueryNodePropertyToWarningComment::class);
        $this->fusionFlowQueryNodePropertyToWarningComments = $configuration;
    }
}
