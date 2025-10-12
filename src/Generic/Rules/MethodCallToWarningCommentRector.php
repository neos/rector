<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
use Neos\Rector\Generic\ValueObject\MethodCallToWarningComment;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class MethodCallToWarningCommentRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    use AllTraits;

    /**
     * @var MethodCallToWarningComment[]
     */
    private array $methodCallsToWarningComments = [];

    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
    ) {
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
        return [Node\Stmt::class];
    }

    /**
     * @param Node\Stmt $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!in_array('expr',$node->getSubNodeNames())) {
            return null;
        }

        foreach ($this->methodCallsToWarningComments as $methodCallToWarningComment) {
            $methodCall = $this->betterNodeFinder->findFirst($node, function (Node $subNode) use ($methodCallToWarningComment) {
                return $subNode instanceof MethodCall
                    && $this->isObjectType($subNode->var, new ObjectType($methodCallToWarningComment->objectType))
                    && $this->isName($subNode->name, $methodCallToWarningComment->methodName);
            });
            if ($methodCall) {
                return self::withTodoComment(
                    sprintf($methodCallToWarningComment->warningMessage, $methodCallToWarningComment->objectType, $methodCallToWarningComment->methodName),
                    $node
                );
            }
        }
        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, MethodCallToWarningComment::class);
        $this->methodCallsToWarningComments = $configuration;
    }
}
