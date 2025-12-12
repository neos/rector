<?php

use Neos\ContentRepository\Domain\Model\Node;

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

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        return $subgraph->findNodePath($node->aggregateId)->getDepth();
    }
}

?>
