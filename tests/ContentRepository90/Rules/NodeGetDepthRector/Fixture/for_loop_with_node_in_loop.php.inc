<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        for ($i = 0; $i < 100; $i = $i + $node->getDepth()) {
            echo $i;
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
        for ($i = 0; $i < 100; $i = $i + $subgraph->findNodePath($node->aggregateId)->getDepth()) {
            echo $i;
        }
    }
}

?>
