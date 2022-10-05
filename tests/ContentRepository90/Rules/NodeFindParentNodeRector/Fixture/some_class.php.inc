<?php

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $parentNode = $node->findParentNode();
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
        $parentNode = $subgraph->findParentNode($node->nodeAggregateId);
    }
}

?>
