<?php

use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

class SomeClass
{
    public function run(Workspace $workspace)
    {
        $title = $workspace->getTitle();
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

        $title = $this->workspaceService->getWorkspaceMetadata(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName)->title->value;
    }
}

?>
