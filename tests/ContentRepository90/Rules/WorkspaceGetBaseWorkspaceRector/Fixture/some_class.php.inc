<?php

use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

class SomeClass
{
    public function run(Workspace $workspace)
    {
        $baseWorkspace = $workspace->getBaseWorkspace();

        return $workspace->getBaseWorkspace();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

class SomeClass
{
    public function run(Workspace $workspace)
    {

        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead and make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        $baseWorkspace = $contentRepository->findWorkspaceByName($workspace->baseWorkspaceName);

        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead and make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));

        return $contentRepository->findWorkspaceByName($workspace->baseWorkspaceName);
    }
}

?>
