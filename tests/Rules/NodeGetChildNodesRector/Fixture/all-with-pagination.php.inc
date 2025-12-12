<?php

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;

class SomeClass
{
    public function run(TraversableNodeInterface $node)
    {
        foreach ($node->getChildNodes(null, 10,100) as $node) {
        }
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
        // TODO 9.0 migration: Try to remove the iterator_to_array($nodes) call.
        foreach (iterator_to_array($subgraph->findChildNodes($node->aggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter::create(pagination: ['limit' => 10, 'offset' => 100]))) as $node) {
        }
    }
}

?>
