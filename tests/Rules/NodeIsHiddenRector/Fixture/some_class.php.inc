<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        return $node->isHidden();
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
        $contentRepository = $this->contentRepositoryRegistry->get($node->subgraphIdentity->contentRepositoryId);
        $nodeHiddenStateFinder = $contentRepository->projectionState(\Neos\ContentRepository\Core\Projection\NodeHiddenState\NodeHiddenStateFinder::class);
        $hiddenState = $nodeHiddenStateFinder->findHiddenState($node->subgraphIdentity->contentStreamId, $node->subgraphIdentity->dimensionSpacePoint, $node->nodeAggregateId);
        return $hiddenState->isHidden();
    }
}

?>
