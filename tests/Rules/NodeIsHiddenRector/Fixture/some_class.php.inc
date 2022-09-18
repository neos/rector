<?php

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

class SomeClass
{
    public function run(Node $node)
    {
        return $node->isHidden();
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
        $contentRepository = $this->contentRepositoryRegistry->get($node->subgraphIdentity->contentRepositoryId);
        $nodeHiddenStateFinder = $contentRepository->projectionState(\Neos\ContentRepository\Core\Projection\NodeHiddenState\NodeHiddenStateFinder::class);
        $hiddenState = $nodeHiddenStateFinder->findHiddenState($node->subgraphIdentity->contentStreamId, $node->subgraphIdentity->dimensionSpacePoint, $node->nodeAggregateId);
        return $hiddenState->isHidden();
    }
}

?>
