<?php

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

class SomeClass
{
    public function doSomethingWithNode(TraversableNodeInterface $node): array
    {
        while ($parentNode = $node->findParentNode()) {
            $node = $parentNode;
        }

        while ($a === $b) {
            $parentNode = $this->foo($node->findParentNode());
        }

        return $node->findParentNode();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

class SomeClass
{
    public function doSomethingWithNode(TraversableNodeInterface $node): array
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        while ($parentNode = $subgraph->findParentNode($node->aggregateId)) {
            $node = $parentNode;
        }

        while ($a === $b) {
            $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
            $parentNode = $this->foo($subgraph->findParentNode($node->aggregateId));
        }
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);

        return $subgraph->findParentNode($node->aggregateId);
    }
}

?>
