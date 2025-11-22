<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Flow\SignalSlot\Dispatcher;
use Neos\Rector\Generic\ValueObject\SignalSlotToWarningComment;
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

final class SignalSlotToWarningCommentRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    use AllTraits;

    /**
     * @var SignalSlotToWarningComment[]
     */
    private array $signalSlotToWarningComments = [];

    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Warning comments for various non-supported signals', __CLASS__, [
            new SignalSlotToWarningComment(Node::class, 'beforeMove', '!! This signal "beforeMove" on Node doesn\'t exist anymore')
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
        if (!in_array('expr', $node->getSubNodeNames())) {
            return null;
        }

        $methodCall = $this->betterNodeFinder->findFirst($node, function (Node $subNode) {
            return $subNode instanceof MethodCall
                && $this->isObjectType($subNode->var, new ObjectType(Dispatcher::class))
                && $this->isName($subNode->name, 'connect');
        });

        if (!$methodCall instanceof MethodCall) {
            return null;
        }

        foreach ($this->signalSlotToWarningComments as $signalSlotToWarningComment) {
            $className = null;
            if ($methodCall->args[0]->value instanceof Node\Expr\ClassConstFetch) {
                $className = (string)$methodCall->args[0]->value->class;
            } elseif ($methodCall->args[0]->value instanceof Node\Scalar) {
                $className = (string)$methodCall->args[0]->value->value;
            }

            if ($className !== $signalSlotToWarningComment->className) {
                continue;
            }

            $methodName = null;
            if ($methodCall->args[1]->value instanceof Node\Scalar\String_) {
                $methodName = (string)$methodCall->args[1]->value->value;
            }

            if ($methodName !== $signalSlotToWarningComment->signalName) {
                continue;
            }

            return self::withTodoComment(
                $signalSlotToWarningComment->warningMessage,
                $node
            );
        }
        return null;
    }


    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, SignalSlotToWarningComment::class);
        $this->signalSlotToWarningComments = $configuration;
    }
}
