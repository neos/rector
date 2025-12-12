<?php

namespace Neos\ContentRepository\Domain\Model;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Rector\ContentRepository90\Legacy\LegacyContextStub;

/**
 * @deprecated
 */
class Node
{
    public function getContext(): LegacyContextStub
    {
        return new LegacyContextStub([]);
    }

    public function getNodeType(): NodeType
    {
        return new NodeType('foo', [], [], null);
    }

    public function getParent(): Node
    {
        return new Node();
    }

    public function findParentNode(): Node
    {
        return new Node();
    }

    public function findNamedChildNode(NodeName $nodeName): Node
    {
        return new Node();
    }

    public function getNode(): Node
    {
        return new Node();
    }

    public function findChildNodes(NodeTypeConstraints $nodeTypeConstraints = null, int $limit = null, int $offset = null): TraversableNodes
    {
        return new TraversableNodes();
    }

    public function findReferencedNodes(): TraversableNodes
    {
        return new TraversableNodes();
    }

    public function findNamedReferencedNodes(PropertyName $edgeName): TraversableNodes
    {
        return new TraversableNodes();
    }
}
