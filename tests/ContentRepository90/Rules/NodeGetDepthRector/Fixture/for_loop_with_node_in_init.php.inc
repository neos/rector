<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        for ($i = $node->getDepth(); $i > 0; $i--) {
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
        for ($i = $subgraph->findNodePath($node->aggregateId)->getDepth(); $i > 0; $i--) {
            echo $i;
        }
    }
}

?>
