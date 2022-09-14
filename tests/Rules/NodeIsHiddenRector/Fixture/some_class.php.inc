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
        $contentRepository = $this->contentRepositoryRegistry->get($node->subgraphIdentity->contentRepositoryIdentifier);
        $nodeHiddenStateFinder = $contentRepository->getProjection(\Neos\ContentRepository\Core\Projection\NodeHiddenState\NodeHiddenStateFinder::class);
        $hiddenState = $nodeHiddenStateFinder->findHiddenState($node->subgraphIdentity->contentStreamIdentifier, $node->subgraphIdentity->dimensionSpacePoint, $node->nodeAggregateIdentifier);
        return $hiddenState->isHidden();
    }
}

?>
