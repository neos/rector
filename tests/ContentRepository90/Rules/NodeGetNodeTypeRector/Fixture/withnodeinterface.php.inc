<?php

use Neos\ContentRepository\Domain\Model\NodeInterface;

class SomeClass
{
    public function run(NodeInterface $node)
    {
        $nodeType = $node->getNodeType();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Model\NodeInterface;

class SomeClass
{
    public function run(NodeInterface $node)
    {
        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);
        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
    }
}

?>
