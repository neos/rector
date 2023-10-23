<?php

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;

    public function run(Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
    }
}

?>
-----
<?php

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;

    public function run(Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
    }
}

?>
