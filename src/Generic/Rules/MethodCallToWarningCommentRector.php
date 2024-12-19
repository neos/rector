<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\Generic\ValueObject\MethodCallToWarningComment;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;
use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

final class MethodCallToWarningCommentRector extends AbstractRector implements ConfigurableRectorInterface
{
    use AllTraits;

    /**
     * @var MethodCallToWarningComment[]
     */
    private array $methodCallsToWarningComments = [];

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector
    )
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Warning comments for various non-supported use cases', __CLASS__, [
            new MethodCallToWarningComment(NodeLegacyStub::class, 'getWorkspace', '!! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.')
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);
        foreach ($this->methodCallsToWarningComments as $methodCallToWarningComment) {
            if (!$this->isName($node->name, $methodCallToWarningComment->methodName)) {
                continue;
            }
            if (!$this->isObjectType($node->var, new ObjectType($methodCallToWarningComment->objectType))) {
                continue;
            }

            $this->nodesToAddCollector->addNodesBeforeNode(
                [
                    self::todoComment(sprintf($methodCallToWarningComment->warningMessage, $methodCallToWarningComment->objectType, $methodCallToWarningComment->methodName))
                ],
                $node
            );

            return $node;
        }
        return null;
    }


    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration) : void
    {
        Assert::allIsAOf($configuration, MethodCallToWarningComment::class);
        $this->methodCallsToWarningComments = $configuration;
    }
}
