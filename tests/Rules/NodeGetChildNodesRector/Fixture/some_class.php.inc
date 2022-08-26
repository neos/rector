<?php

use Neos\ContentRepository\Projection\ContentGraph\Node;

class SomeClass
{
    public function run(Node $node)
    {
        foreach ($node->getChildNodes() as $node) {
        }
    }
}

?>
-----
<?php

use Neos\ContentRepository\Projection\ContentGraph\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        // TODO 9.0 migration: Try to remove the iterator_to_array($nodes) call.

        foreach (iterator_to_array($subgraph->findChildNodes($node->nodeAggregateIdentifier)) as $node) {
        }
    }
}

?>
