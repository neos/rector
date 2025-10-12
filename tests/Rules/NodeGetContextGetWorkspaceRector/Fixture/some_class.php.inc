<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public NodeLegacyStub $node;

    public function getNode(): NodeLegacyStub {
        return $this->node;
    }

    public function run(NodeLegacyStub $node)
    {
        $workspace = $this->getNode()->getContext()->getWorkspace();

        $workspace = $this->node->getContext()->getWorkspace();

        return $node->getContext()->getWorkspace();
    }
}

?>
-----
<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public NodeLegacyStub $node;

    public function getNode(): NodeLegacyStub {
        return $this->node;
    }

    public function run(NodeLegacyStub $node)
    {
        $contentRepository = $this->contentRepositoryRegistry->get($this->getNode()->contentRepositoryId);
        $workspace = $contentRepository->findWorkspaceByName($this->getNode()->workspaceName);
        $contentRepository = $this->contentRepositoryRegistry->get($this->node->contentRepositoryId);

        $workspace = $contentRepository->findWorkspaceByName($this->node->workspaceName);
        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);

        return $contentRepository->findWorkspaceByName($node->workspaceName);
    }
}

?>
