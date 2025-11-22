<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Flow\SignalSlot\Dispatcher;
use Neos\Rector\Generic\ValueObject\SignalSlotToWarningComment;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
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
    )
    {
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
        return [\PhpParser\Node\Expr\MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if (!$this->isName($node->name, 'connect')) {
            return null;
        }

        if (!$this->isObjectType($node->var, new ObjectType(Dispatcher::class))) {
            return null;
        }

        foreach ($this->signalSlotToWarningComments as $signalSlotToWarningComment) {
            $className = null;
            if ($node->args[0]->value instanceof Node\Expr\ClassConstFetch) {
                $className = (string) $node->args[0]->value->class;
            } elseif ($node->args[0]->value instanceof Node\Scalar) {
                $className = (string)$node->args[0]->value->value;
            }

            if ($className !== $signalSlotToWarningComment->className){
                continue;
            }

            $methodName = null;
            if ($node->args[1]->value instanceof Node\Scalar\String_) {
                $methodName = (string)$node->args[1]->value->value;
            }

            if ($methodName !== $signalSlotToWarningComment->signalName) {
                continue;
            }

//            $this->nodesToAddCollector->addNodesBeforeNode(
//                [
//                    self::todoComment($signalSlotToWarningComment->warningMessage)
//                ],
//                $node
//            );

            return $node;
        }
        return null;
    }


    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration) : void
    {
        Assert::allIsAOf($configuration, SignalSlotToWarningComment::class);
        $this->signalSlotToWarningComments = $configuration;
    }
}
