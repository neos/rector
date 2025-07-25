<?php
declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules\Traits;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use Rector\PhpParser\Node\NodeFactory;

trait ThisTrait
{
    protected NodeFactory $nodeFactory;

    private function this_contentRepositoryRegistry_subgraphForNode(Expr $nodeVariable): Expr
    {
        return $this->nodeFactory->createMethodCall(
            $this->this_contentRepositoryRegistry(),
            'subgraphForNode',
            [$nodeVariable]
        );
    }

    private function this_contentRepositoryRegistry_get(Expr $contentRepositoryIdentifier): Expr
    {
        return $this->nodeFactory->createMethodCall(
            $this->this_contentRepositoryRegistry(),
            'get',
            [
                $contentRepositoryIdentifier
            ]
        );
    }

    private function this_workspaceService_getWorkspaceMetadata(Expr $contentRepositoryIdentifier, Expr $workspaceName): Expr
    {
        return $this->nodeFactory->createMethodCall(
            $this->this_workspaceService(),
            'getWorkspaceMetadata',
            [
                $contentRepositoryIdentifier,
                $workspaceName
            ]
        );
    }

    private function this_workspaceService()
    {
        return $this->nodeFactory->createPropertyFetch('this', 'workspaceService');
    }

    private function this_workspacePublishingService()
    {
        return $this->nodeFactory->createPropertyFetch('this', 'workspacePublishingService');
    }


    private function this_contentRepositoryRegistry(): Expr
    {
        return $this->nodeFactory->createPropertyFetch('this', 'contentRepositoryRegistry');
    }
}
