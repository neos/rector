<?php
declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules\Traits;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use Rector\PhpParser\Node\NodeFactory;

trait ContentRepositoryTrait
{
    protected NodeFactory $nodeFactory;

    private function contentRepository_findWorkspaceByName(Expr $workspaceName)
    {
        return $this->nodeFactory->createMethodCall(
            new Variable('contentRepository'),
            'findWorkspaceByName',
            [
                $workspaceName
            ]
        );
    }

    private function contentRepository_getContentGraph_findRootNodeAggregateByType(Expr $workspaceName, Expr $nodeTypeName)
    {
        return $this->nodeFactory->createMethodCall(
            $this->contentRepository_getContentGraph($workspaceName),
            'findRootNodeAggregateByType',
            [
                $nodeTypeName
            ]
        );
    }

    private function contentRepository_getContentGraph_getSubgraph(Expr $workspaceName, Expr $dimensionSpacePoint, Expr $visibilityConstraints)
    {
        return $this->nodeFactory->createMethodCall(
            $this->contentRepository_getContentGraph($workspaceName),
            'getSubgraph',
            [
                $dimensionSpacePoint,
                $visibilityConstraints
            ]
        );
    }

    private function contentRepository_getContentGraph(Expr $workspaceName): Expr
    {
        return $this->nodeFactory->createMethodCall(
            new Variable('contentRepository'),
            'getContentGraph',
            [$workspaceName]
        );
    }

    private function contentRepository_getVariationGraph(): Expr
    {
        return $this->nodeFactory->createMethodCall(
            new Variable('contentRepository'),
            'getVariationGraph',
            []
        );
    }

    private function contentRepository_getVariationGraph_getDimensionSpacePoints()
    {
        return $this->nodeFactory->createMethodCall(
            $this->contentRepository_getVariationGraph(),
            'getDimensionSpacePoints'
        );
    }

    private function contentRepository_getNodeTypeManager(): Expr
    {
        return $this->nodeFactory->createMethodCall(
            new Variable('contentRepository'),
            'getNodeTypeManager',
            []
        );
    }
}
