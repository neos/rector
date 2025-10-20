<?php

use Neos\ContentRepository\Domain\Model\NodeInterface;

class SomeClass
{
    public function run(NodeInterface $node)
    {
        foreach ($node->getChildNodes() as $node) {
        }
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
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        // TODO 9.0 migration: Try to remove the iterator_to_array($nodes) call.
        foreach (iterator_to_array($subgraph->findChildNodes($node->aggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter::create())) as $node) {
        }
    }
}

?>
