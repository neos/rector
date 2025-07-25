<?php
declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules\Traits;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use Rector\PhpParser\Node\NodeFactory;

trait NodeTrait
{
    protected NodeFactory $nodeFactory;

    private function node_nodeAggregateId(Expr $nodeVariable): Expr
    {
        return $this->nodeFactory->createPropertyFetch(
            $nodeVariable,
            'aggregateId'
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
