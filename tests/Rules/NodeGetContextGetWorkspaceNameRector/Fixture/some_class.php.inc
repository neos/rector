<?php

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

class SomeClass
{
    public function run(Node $node)
    {
        return $node->getContext()->getWorkspaceName();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $contentRepository = $this->contentRepositoryRegistry->get($node->subgraphIdentity->contentRepositoryId);
        return $contentRepository->getWorkspaceFinder()->findOneByCurrentContentStreamId($node->subgraphIdentity->contentStreamId)->workspaceName;
    }
}

?>
