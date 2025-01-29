<?php

namespace Neos\Rector\Test;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\Workspace;

class SomeClass extends AnotherClass
{

    public function test(Workspace $workspace)
    {
        $baseWorkspace = $workspace->getBaseWorkspace();

        $baseWorkspaces = $workspace->getBaseWorkspaces();

        $title = $workspace->getTitle();

        $description = $workspace->getDescription();

        $name = $workspace->getName();

        $workspace->getNodeCount();

        $workspace->getOwner();

        $workspace->getRootNodeData();

        $workspace->isInternalWorkspace();

        $workspace->isPersonalWorkspace();

        $workspace->isPrivateWorkspace();

        $workspace->isPublicWorkspace();

        $workspace->publish($targetWorkspace);

        $workspace->publishNode($node, $targetWorkspace);

        $workspace->publishNodes([$node], $targetWorkspace);

        $workspace->setBaseWorkspace($baseWorkspace);

        $workspace->setOwner($user);

        $workspace->setDescription("description");

        $workspace->setTitle("title");
    }
}
-----


namespace Neos\Rector\Test;

use Neos\Flow\Annotations as Flow;

class SomeClass extends AnotherClass
{
    #[Flow\Inject]
    protected \Neos\Neos\Domain\Service\WorkspaceService $workspaceService;
    #[Flow\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
    #[Flow\Inject]
    protected \Neos\Neos\Domain\Service\WorkspacePublishingService $workspacePublishingService;

    public function test(\Neos\ContentRepository\Core\SharedModel\Workspace\Workspace $workspace)
    {

        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        $baseWorkspace = $contentRepository->findWorkspaceByName($workspace->baseWorkspaceName);

        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        $baseWorkspaces = $contentRepository->findWorkspaces()->getBaseWorkspaces($workspace->baseWorkspaceName);

        $title = $this->workspaceService->getWorkspaceMetadata(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName)->title;

        $description = $this->workspaceService->getWorkspaceMetadata(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName)->description;
        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead.


        $name = $workspace->workspaceName->value;
        // TODO 9.0 migration: !! Workspace::getNodeCount() has been removed in Neos 9.0 without a replacement.


        $workspace->getNodeCount();

        $workspace->getOwner();
        // TODO 9.0 migration: !! Workspace::getRootNodeData() has been removed in Neos 9.0 without a replacement.


        $workspace->getRootNodeData();
        // TODO 9.0 migration: !! Workspace::isInternalWorkspace() has been removed in Neos 9.0. Please use the new Workspace permission api instead. See ContentRepositoryAuthorizationService::getWorkspacePermissions()


        $workspace->isInternalWorkspace();
        // TODO 9.0 migration: !! Workspace::isPersonalWorkspace() has been removed in Neos 9.0. Please use the new Workspace permission api instead. See ContentRepositoryAuthorizationService::getWorkspacePermissions()


        $workspace->isPersonalWorkspace();
        // TODO 9.0 migration: !! Workspace::isPrivateWorkspace() has been removed in Neos 9.0. Please use the new Workspace permission api instead. See ContentRepositoryAuthorizationService::getWorkspacePermissions()


        $workspace->isPrivateWorkspace();
        // TODO 9.0 migration: !! Workspace::isPublicWorkspace() has been removed in Neos 9.0. Please use the new Workspace permission api instead. See ContentRepositoryAuthorizationService::getWorkspacePermissions()


        $workspace->isPublicWorkspace();

        $this->workspacePublishingService->publishWorkspace(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName);

        // TODO: Check if this matches your requirements as this is not a 100% replacement
        $this->workspacePublishingService->publishChangesInDocument(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, $node);

        // TODO: Use the \Neos\Neos\Domain\Service\WorkspacePublishingService to publish a workspace or changesin a document.
        $workspace->publishNodes([$node], $targetWorkspace);


        $workspace->setBaseWorkspace($baseWorkspace);
        $workspace->setOwner($user);

        $this->workspaceService->setWorkspaceDescription(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, 'description');

        $this->workspaceService->setWorkspaceTitle(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, 'title');
    }
}
