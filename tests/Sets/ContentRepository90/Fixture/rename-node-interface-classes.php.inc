<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use \Neos\ContentRepository\Domain\Model\Node;

class SomeClass extends AnotherClass
{

    public function foo(Node $node, NodeInterface $targetNode)
    {
        /** @var array<Node> $childNodes */
        $childNodes = $node->getChildNodes();

        $parent = $targetNode->getParent();
    }

}

-----
<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use \Neos\ContentRepository\Domain\Model\Node;

class SomeClass extends AnotherClass
{

    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
    public function foo(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node, \Neos\ContentRepository\Core\Projection\ContentGraph\Node $targetNode)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        // TODO 9.0 migration: Try to remove the iterator_to_array($nodes) call.

        /** @var array<\Neos\ContentRepository\Core\Projection\ContentGraph\Node> $childNodes */
        $childNodes = iterator_to_array($subgraph->findChildNodes($node->nodeAggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter::create()));
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($targetNode);

        $parent = $subgraph->findParentNode($targetNode->nodeAggregateId);
    }

}

