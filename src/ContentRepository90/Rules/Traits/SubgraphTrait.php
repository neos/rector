<?php
declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules\Traits;

use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;

trait SubgraphTrait
{
    use NodeTrait;

    /**
     * @var \Rector\Core\PhpParser\Node\NodeFactory
     */
    protected $nodeFactory;


    private function subgraph_findChildNodes(
        Expr $nodeVariable,
        ?Expr $nodeTypeConstraintsFilterString = null,
        ?Expr $limit = null,
        ?Expr $offset = null
    ): Expr
    {
        if ($nodeTypeConstraintsFilterString) {
            $filter = $this->nodeFactory->createStaticCall(
                FindChildNodesFilter::class,
                'nodeTypeConstraints',
                [
                    $nodeTypeConstraintsFilterString
                ]
            );
        } else {
            $filter = $this->nodeFactory->createStaticCall(
                FindChildNodesFilter::class,
                'all'
            );
        }

        if ($limit || $offset) {
            $filter = $this->nodeFactory->createMethodCall(
                $filter,
                'withPagination',
                [
                    $limit,
                    $offset
                ]
            );
        }

        return $this->nodeFactory->createMethodCall(
            'subgraph',
            'findChildNodes',
            [
                $this->node_nodeAggregateId($nodeVariable),
                $filter
            ]
        );
    }

    private function subgraph_findNodePath(Variable $nodeVariable): Expr
    {
        return $this->nodeFactory->createMethodCall(
            'subgraph',
            'findNodePath',
            [
                $this->node_nodeAggregateId($nodeVariable)
            ]
        );
    }

    private function subgraph_findNodePath_getDepth(Variable $nodeVariable): Expr
    {
        return $this->nodeFactory->createMethodCall(
            $this->subgraph_findNodePath($nodeVariable),
            'getDepth'
        );
    }

    private function subgraph_findNodeById(Expr $nodeAggregateIdentifier)
    {
        return $this->nodeFactory->createMethodCall(
            'subgraph',
            'findNodeById',
            [
                $nodeAggregateIdentifier
            ]
        );
    }

    private function subgraph_findParentNode(Variable $nodeVariable): Expr
    {
        return $this->nodeFactory->createMethodCall(
            'subgraph',
            'findParentNode',
            [
                $this->node_nodeAggregateId($nodeVariable)
            ]
        );
    }
}
