<?php

namespace Neos\Rector\Test;

use Neos\Flow\Annotations as Flow;

class SomeClass extends AnotherClass
{
    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Repository\WorkspaceRepository
     */
    private $workspaceRepository;

    public function findWorkspace($workspaceIdentifier)
    {
        $dependentWorkspaces = $this->workspaceRepository->findByBaseWorkspace($workspaceIdentifier);
    }
}

-----
<?php

namespace Neos\Rector\Test;

use Neos\Flow\Annotations as Flow;

class SomeClass extends AnotherClass
{
    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Repository\WorkspaceRepository
     */
    private $workspaceRepository;

    public function findWorkspace($workspaceIdentifier)
    {
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        $dependentWorkspaces = $contentRepository->findWorkspaces()->getDependantWorkspaces(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($workspaceIdentifier));
    }
}

