<?php

declare (strict_types=1);
namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeGetChildNodesRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector
    )
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeInterface::getChildNodes()" will be rewritten', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node) : ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if (!$this->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Core\Projection\ContentGraph\Node::class))) {
            return null;
        }
        if (!$this->isName($node->name, 'getChildNodes')) {
            return null;
        }
        $nodeTypeFilterExpr = null;
        $limitExpr = null;
        $offsetExpr = null;
        if (count($node->args) >= 1) {
            $nodeTypeFilterExpr = $node->args[0];
            assert($nodeTypeFilterExpr instanceof Node\Arg);
            $nodeTypeFilterExpr = $nodeTypeFilterExpr->value;
        }
        if (count($node->args) >= 2) {
            $limitExpr = $node->args[1];
            assert($limitExpr instanceof Node\Arg);
            $limitExpr = $limitExpr->value;
        }
        if (count($node->args) >= 3) {
            $offsetExpr = $node->args[2];
            assert($offsetExpr instanceof Node\Arg);
            $offsetExpr = $offsetExpr->value;
        }


        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::assign('subgraph', $this->this_contentRepositoryRegistry_subgraphForNode($node->var)),
                self::todoComment('Try to remove the iterator_to_array($nodes) call.')
            ],
            $node
        );

        return $this->iteratorToArray(
            $this->subgraph_findChildNodes($node->var, $nodeTypeFilterExpr, $limitExpr, $offsetExpr)
        );
    }
}
