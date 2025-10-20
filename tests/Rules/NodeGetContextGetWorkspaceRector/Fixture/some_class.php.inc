<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public Node $node;

    public function getNode(): Node {
        return $this->node;
    }

    public function run(Node $node)
    {
        $workspace = $this->getNode()->getContext()->getWorkspace();

        $workspace = $this->node->getContext()->getWorkspace();

        return $node->getContext()->getWorkspace();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public Node $node;

    public function getNode(): Node {
        return $this->node;
    }

    public function run(Node $node)
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
