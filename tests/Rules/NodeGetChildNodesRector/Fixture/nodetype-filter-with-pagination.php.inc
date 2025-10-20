<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        foreach ($node->getChildNodes('Neos.Neos:Document', 10, 100) as $node) {
        }
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        // TODO 9.0 migration: Try to remove the iterator_to_array($nodes) call.
        foreach (iterator_to_array($subgraph->findChildNodes($node->aggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter::create(nodeTypeConstraints: 'Neos.Neos:Document', pagination: ['limit' => 10, 'offset' => 100]))) as $node) {
        }
    }
}

?>
