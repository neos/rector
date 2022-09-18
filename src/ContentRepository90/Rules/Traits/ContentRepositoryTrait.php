<?php
declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules\Traits;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;

trait ContentRepositoryTrait
{
    /**
     * @var \Rector\Core\PhpParser\Node\NodeFactory
     */
    protected $nodeFactory;

    private function contentRepository_projectionState(string $projectionStateClassName): Expr
    {
        return $this->nodeFactory->createMethodCall(
            new Variable('contentRepository'),
            'projectionState',
            [
                new Expr\ClassConstFetch(
                    new FullyQualified($projectionStateClassName),
                    new Identifier('class')
                )
            ]
        );
    }

    private function contentRepository_getWorkspaceFinder_findOneByCurrentContentStreamId(Expr $contentStreamId): Expr
    {
        return $this->nodeFactory->createMethodCall(
            $this->contentRepository_getWorkspaceFinder(),
            'findOneByCurrentContentStreamId',
            [
                $contentStreamId
            ]
        );
    }

    private function contentRepository_getWorkspaceFinder_findOneByName(Expr $workspaceName)
    {
        return $this->nodeFactory->createMethodCall(
            $this->contentRepository_getWorkspaceFinder(),
            'findOneByName',
            [
                $workspaceName
            ]
        );
    }

    private function contentRepository_getWorkspaceFinder(): Expr
    {
        return $this->nodeFactory->createMethodCall(
            new Variable('contentRepository'),
            'getWorkspaceFinder',
            []
        );
    }

    private function contentRepository_getContentGraph_findRootNodeAggregateByType(Expr $contentStreamIdentifier, Expr $nodeTypeName)
    {
        return $this->nodeFactory->createMethodCall(
            $this->contentRepository_getContentGraph(),
            'findRootNodeAggregateByType',
            [
                $contentStreamIdentifier,
                $nodeTypeName
            ]
        );
    }

    private function contentRepository_getContentGraph_getSubgraph(Expr $contentStreamId, Expr $dimensionSpacePoint, Expr $visibilityConstraints)
    {
        return $this->nodeFactory->createMethodCall(
            $this->contentRepository_getContentGraph(),
            'getSubgraph',
            [
                $contentStreamId,
                $dimensionSpacePoint,
                $visibilityConstraints
            ]
        );
    }

    private function contentRepository_getContentGraph(): Expr
    {
        return $this->nodeFactory->createMethodCall(
            new Variable('contentRepository'),
            'getContentGraph',
            []
        );
    }

}
