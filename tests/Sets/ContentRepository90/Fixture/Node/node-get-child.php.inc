<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $node->getNode('path')->getName();

        $nodeName = NodeName::fromString("name");
        $node->findNamedChildNode($nodeName)->getName();

        foreach ($node->findChildNodes() as $childNode) {
            $childNode->getName();
        }
    }

    public function nodeInterface(\Neos\ContentRepository\Domain\Model\NodeInterface $node)
    {
        $node->getNode('path')->getName();

        $nodeName = NodeName::fromString("name");
        $node->findNamedChildNode($nodeName)->getName();

        foreach ($node->findChildNodes() as $childNode) {
            $childNode->getName();
        }
    }

    public function traversableNodeInterface(\Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface $node)
    {
        $node->getNode('path')->getName();

        $nodeName = NodeName::fromString("name");
        $node->findNamedChildNode($nodeName)->getName();

        foreach ($node->findChildNodes() as $childNode) {
            $childNode->getName();
        }
    }
}

-----
<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $node->getNode('path')->nodeName;

        $nodeName = \Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString("name");
        $node->findNamedChildNode($nodeName)->nodeName;

        foreach ($node->findChildNodes() as $childNode) {
            $childNode->nodeName;
        }
    }

    public function nodeInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $node->getNode('path')->nodeName;

        $nodeName = \Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString("name");
        $node->findNamedChildNode($nodeName)->nodeName;

        foreach ($node->findChildNodes() as $childNode) {
            $childNode->nodeName;
        }
    }

    public function traversableNodeInterface(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $node->getNode('path')->nodeName;

        $nodeName = \Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString("name");
        $node->findNamedChildNode($nodeName)->nodeName;

        foreach ($node->findChildNodes() as $childNode) {
            $childNode->nodeName;
        }
    }
}

