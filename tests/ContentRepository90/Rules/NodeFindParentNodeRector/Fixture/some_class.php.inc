<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $parentNode = $node->findParentNode();
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
        $parentNode = $subgraph->findParentNode($node->aggregateId);
    }
}

?>
