<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeFactoryResetRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeFactory::reset()" will be removed.', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class, Node\Stmt\Return_::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node)
    {
        $methodCall = $this->betterNodeFinder->findFirst($node, function (Node $subNode) {
            return $subNode instanceof MethodCall
                && $this->isObjectType($subNode->var, new ObjectType(\Neos\ContentRepository\Domain\Factory\NodeFactory::class))
                && $this->isName($subNode->name, 'reset');
        });

        if ($methodCall) {
            if ($node instanceof Node\Stmt\Return_) {
                return new Node\Stmt\Return_();
            }
            return NodeVisitor::REMOVE_NODE;
        }

        return $node;
    }
}
