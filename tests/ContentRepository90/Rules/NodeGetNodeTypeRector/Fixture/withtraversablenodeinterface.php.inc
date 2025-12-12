<?php

use \Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

class SomeClass
{
    public function run(TraversableNodeInterface $node)
    {
        $nodeType = $node->getNodeType();
    }
}

?>
-----
<?php

use \Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

class SomeClass
{
    public function run(TraversableNodeInterface $node)
    {
        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);
        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
    }
}

?>
