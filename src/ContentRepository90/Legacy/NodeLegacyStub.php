<?php

namespace Neos\Rector\ContentRepository90\Legacy;

use Neos\ContentRepository\Core\NodeType\NodeType;

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
}
