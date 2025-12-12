<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node, \Neos\ContentRepository\Domain\Model\Node $otherNode)
    {
        $node->copyBefore($otherNode);
        $node->copyInto($otherNode);
        $node->copyAfter($otherNode);
    }
}

-----
<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node, \Neos\ContentRepository\Core\Projection\ContentGraph\Node $otherNode)
    {
        // TODO 9.0 migration: !! Node::copyBefore() is not supported by the new CR. Use the "NodeDuplicationService::copyNodesRecursively" to copy a node.
        $node->copyBefore($otherNode);
        // TODO 9.0 migration: !! Node::copyInto() is not supported by the new CR. Use the "NodeDuplicationService::copyNodesRecursively" to copy a node.
        $node->copyInto($otherNode);
        // TODO 9.0 migration: !! Node::copyAfter() is not supported by the new CR. Use the "NodeDuplicationService::copyNodesRecursively" to copy a node.
        $node->copyAfter($otherNode);
    }
}

