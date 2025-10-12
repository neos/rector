<?php

use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

class SomeClass
{
    public function run(Workspace $workspace)
    {
        $baseWorkspaces = $workspace->getBaseWorkspaces();

        return $workspace->getBaseWorkspaces();
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
        $baseWorkspaces = $contentRepository->findWorkspaces()->getBaseWorkspaces($workspace->workspaceName);

        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead and make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));

        return $contentRepository->findWorkspaces()->getBaseWorkspaces($workspace->workspaceName);
    }
}

?>
