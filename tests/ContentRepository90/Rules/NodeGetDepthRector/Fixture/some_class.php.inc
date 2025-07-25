<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        return $node->getDepth();
    }
}

?>
-----
<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        return $subgraph->findNodePath($node->aggregateId)->getDepth();
    }
}

?>
