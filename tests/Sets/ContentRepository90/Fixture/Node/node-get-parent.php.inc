<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $parentNode = $node->getParent();
        $parentNode->getName();

        $parentNode = $node->findParentNode();
        $parentNode->getName();
    }

    public function nodeInterface(\Neos\ContentRepository\Domain\Model\NodeInterface $node)
    {
        $parentNode = $node->getParent();
        $parentNode->getName();

        $parentNode = $node->findParentNode();
        $parentNode->getName();
    }

    public function traversableNodeInterface(\Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface $node)
    {
        $parentNode = $node->getParent();
        $parentNode->getName();

        $parentNode = $node->findParentNode();
        $parentNode->getName();
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
        $parentNode = $subgraph->findParentNode($node->aggregateId);
        $parentNode->nodeName;
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);

        $parentNode = $subgraph->findParentNode($node->aggregateId);
        $parentNode->nodeName;
    }

    public function nodeInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $parentNode = $subgraph->findParentNode($node->aggregateId);
        $parentNode->nodeName;
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);

        $parentNode = $subgraph->findParentNode($node->aggregateId);
        $parentNode->nodeName;
    }

    public function traversableNodeInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $parentNode = $subgraph->findParentNode($node->aggregateId);
        $parentNode->nodeName;
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);

        $parentNode = $subgraph->findParentNode($node->aggregateId);
        $parentNode->nodeName;
    }
}

