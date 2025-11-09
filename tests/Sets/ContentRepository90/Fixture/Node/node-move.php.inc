<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node, \Neos\ContentRepository\Domain\Model\Node $otherNode)
    {
        $node->moveBefore($otherNode);
        $node->moveInto($otherNode);
        $node->moveAfter($otherNode);
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
        // TODO 9.0 migration: !! Node::moveBefore() is not supported by the new CR. Use the "MoveNodeAggregate" command to move a node.
        $node->moveBefore($otherNode);
        // TODO 9.0 migration: !! Node::moveInto() is not supported by the new CR. Use the "MoveNodeAggregate" command to move a node.
        $node->moveInto($otherNode);
        // TODO 9.0 migration: !! Node::moveAfter() is not supported by the new CR. Use the "MoveNodeAggregate" command to move a node.
        $node->moveAfter($otherNode);
    }
}

