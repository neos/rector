<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $node->getParent()->getName();

        $node->findParentNode()->getName();
    }

    public function nodeInterface(\Neos\ContentRepository\Domain\Model\NodeInterface $node)
    {
        $node->getParent()->getName();

        $node->findParentNode()->getName();
    }

    public function traversableNodeInterface(\Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface $node)
    {
        $node->getParent()->getName();

        $node->findParentNode()->getName();
    }
}

-----
<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
    public function node(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $subgraph->findParentNode($node->aggregateId)->nodeName;
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);

        $subgraph->findParentNode($node->aggregateId)->nodeName;
    }

    public function nodeInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $subgraph->findParentNode($node->aggregateId)->nodeName;
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);

        $subgraph->findParentNode($node->aggregateId)->nodeName;
    }

    public function traversableNodeInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $subgraph->findParentNode($node->aggregateId)->nodeName;
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);

        $subgraph->findParentNode($node->aggregateId)->nodeName;
    }
}

