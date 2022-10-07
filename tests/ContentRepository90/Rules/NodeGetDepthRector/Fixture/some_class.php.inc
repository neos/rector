<?php

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

class SomeClass
{
    public function run(Node $node)
    {
        return $node->getDepth();
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
        return $subgraph->findNodePath($node->nodeAggregateId)->getDepth();
    }
}

?>
