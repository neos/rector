<?php

use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

class SomeClass
{
    public function run(Workspace $workspace)
    {
        $workspace->setDescription("description");
    }

    public function get(Workspace $workspace)
    {
        return $workspace->setDescription("description");
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
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $this->workspaceService->setWorkspaceDescription(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, \Neos\Neos\Domain\Model\WorkspaceDescription::fromString("description"));
    }

    public function get(Workspace $workspace)
    {
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        return $this->workspaceService->setWorkspaceDescription(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, \Neos\Neos\Domain\Model\WorkspaceDescription::fromString("description"));
    }
}

?>
