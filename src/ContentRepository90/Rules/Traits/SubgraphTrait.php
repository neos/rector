<?php
declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules\Traits;

use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter;
use PhpParser\Node\Expr;
use Rector\PhpParser\Node\NodeFactory;

trait SubgraphTrait
{
    use NodeTrait;

    protected NodeFactory $nodeFactory;

    private function subgraph_findChildNodes(
        Expr $nodeVariable,
        ?Expr $nodeTypeConstraintsFilterString = null,
        ?Expr $limit = null,
        ?Expr $offset = null,
    ): Expr {
        $args = [];

        if ($nodeTypeConstraintsFilterString) {
            $args = [
                'nodeTypeConstraints' => $nodeTypeConstraintsFilterString
            ];
        }

        if ($limit || $offset) {
            $args['pagination'] = [
                'limit' => $limit,
                'offset' => $offset
            ];
        }

        $filter = $this->nodeFactory->createStaticCall(
            FindChildNodesFilter::class,
            'create',
            $args
        );

        return $this->nodeFactory->createMethodCall(
            'subgraph',
            'findChildNodes',
            [
                $this->node_nodeAggregateId($nodeVariable),
                $filter
            ]
        );
    }

    private function subgraph_findNodePath(Expr $nodeVariable): Expr
    {
        return $this->nodeFactory->createMethodCall(
            'subgraph',
            'findNodePath',
            [
                $this->node_nodeAggregateId($nodeVariable)
            ]
        );
    }

    private function subgraph_findNodePath_getDepth(Expr $nodeVariable): Expr
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

    private function subgraph_findParentNode(Expr $nodeVariable): Expr
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
