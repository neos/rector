<?php

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

class SomeClass
{
    public function run(TraversableNodeInterface $node)
    {
        $parentNode = $node->findParentNode();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

class SomeClass
{
    public function run(TraversableNodeInterface $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $parentNode = $subgraph->findParentNode($node->aggregateId);
    }
}

?>
