<?php

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

class SomeClass
{
    public function run(Node $node)
    {
        foreach ($node->getChildNodes(offset: 100, limit: 10) as $node) {
        }
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
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        // TODO 9.0 migration: Try to remove the iterator_to_array($nodes) call.

        foreach (iterator_to_array($subgraph->findChildNodes($node->nodeAggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter::create(pagination: ['limit' => 10, 'offset' => 100]))) as $node) {
        }
    }
}

?>