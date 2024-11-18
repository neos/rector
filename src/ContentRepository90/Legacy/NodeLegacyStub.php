<?php

namespace Neos\Rector\ContentRepository90\Legacy;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;

/**
 * @deprecated
 */
class NodeLegacyStub
{

    public function getContext() : LegacyContextStub {
        return new LegacyContextStub([]);
    }

    public function getNodeType(): NodeType {
        return new NodeType('foo', [], [], null);
    }

    public function getParent(): NodeLegacyStub {
        return new NodeLegacyStub();
    }

    public function findParentNode(): NodeLegacyStub {
        return new NodeLegacyStub();
    }

    public function findNamedChildNode(NodeName $nodeName): NodeLegacyStub
    {
        return new NodeLegacyStub();
    }

    public function getNode(): NodeLegacyStub {
        return new NodeLegacyStub();
    }

   public function findChildNodes(NodeTypeConstraints $nodeTypeConstraints = null, int $limit = null, int $offset = null): TraversableNodes {
        return new TraversableNodes();
   }

    public function findReferencedNodes(): TraversableNodes {
        return new TraversableNodes();
    }

    public function findNamedReferencedNodes(PropertyName $edgeName): TraversableNodes {
        return new TraversableNodes();
    }

}
