<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        for ($i = 0; $i < $node->getDepth(); $i++) {
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
        for ($i = 0; $i < $subgraph->findNodePath($node->aggregateId)->getDepth(); $i++) {
            echo $i;
        }
    }
}

?>
