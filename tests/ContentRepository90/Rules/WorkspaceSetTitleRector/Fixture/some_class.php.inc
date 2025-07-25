<?php

use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

class SomeClass
{
    public function run(Workspace $workspace)
    {
        $workspace->setTitle("title");
        $bar = $workspace->setTitle("title");

        $foo = $this->getWorkspace()->setTitle("title");
    }

    public function getWorkspace(): Workspace {
        return Workspace::create('default');
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
        $this->workspaceService->setWorkspaceTitle(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, \Neos\Neos\Domain\Model\WorkspaceTitle::fromString("title"));
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $bar = $this->workspaceService->setWorkspaceTitle(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, \Neos\Neos\Domain\Model\WorkspaceTitle::fromString("title"));

        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $foo = $this->workspaceService->setWorkspaceTitle(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $this->getWorkspace()->workspaceName, \Neos\Neos\Domain\Model\WorkspaceTitle::fromString("title"));
    }

    public function getWorkspace(): Workspace {
        return Workspace::create('default');
    }
}

?>
