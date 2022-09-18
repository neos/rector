<?php
declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules\Traits;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;

trait NodeTrait
{
    /**
     * @var \Rector\Core\PhpParser\Node\NodeFactory
     */
    protected $nodeFactory;

    private function node_subgraphIdentity(Expr $nodeVariable): Expr
    {
        return $this->nodeFactory->createPropertyFetch($nodeVariable, 'subgraphIdentity');
    }

    private function node_subgraphIdentity_contentRepositoryId(Expr $nodeVariable)
    {
        return $this->nodeFactory->createPropertyFetch(
            $this->node_subgraphIdentity($nodeVariable),
            'contentRepositoryId'
        );
    }

    private function node_subgraphIdentity_contentStreamId(Expr $nodeVariable): Expr
    {
        return $this->nodeFactory->createPropertyFetch(
            $this->node_subgraphIdentity($nodeVariable),
            'contentStreamId'
        );
    }

    private function node_subgraphIdentity_dimensionSpacePoint(Expr $nodeVariable): Expr
    {
        return $this->nodeFactory->createPropertyFetch(
            $this->node_subgraphIdentity($nodeVariable),
            'dimensionSpacePoint'
        );
    }

    private function node_nodeAggregateId(Expr $nodeVariable): Expr
    {
        return $this->nodeFactory->createPropertyFetch(
            $nodeVariable,
            'nodeAggregateId'
        );
    }

    private function node_originDimensionSpacePoint(Expr $nodeVariable): Expr
    {
        return $this->nodeFactory->createPropertyFetch($nodeVariable, 'originDimensionSpacePoint');
    }

    private function node_originDimensionSpacePoint_toLegacyDimensionArray(Expr $nodeVariable): Expr
    {
        return $this->nodeFactory->createMethodCall(
            $this->node_originDimensionSpacePoint($nodeVariable),
            'toLegacyDimensionArray'
        );
    }

}
