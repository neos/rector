<?php

use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;
use Neos\ContentRepository\Domain\Model\NodeInterface;

class SomeClass
{
    public function run(Workspace $workspace, NodeInterface $node)
    {
        $workspace->publishNode($node);
    }
}

?>
-----
<?php

use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;
use Neos\ContentRepository\Domain\Model\NodeInterface;

class SomeClass
{
    public function run(Workspace $workspace, NodeInterface $node)
    {
        // TODO 9.0 migration: Check if this matches your requirements as this is not a 100% replacement. Make this code aware of multiple Content Repositories.
        $this->workspacePublishingService->publishChangesInDocument(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, $node);
    }
}

?>
