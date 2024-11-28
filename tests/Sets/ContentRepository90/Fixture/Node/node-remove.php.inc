<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $node->remove();
        $node->setRemoved(false);
    }
}

-----
<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        // TODO 9.0 migration: !! Node::remove() is not supported by the new CR. Use the "RemoveNodeAggregate" command to remove a node.

        $node->remove();
        // TODO 9.0 migration: !! Node::setRemoved() is not supported by the new CR. Use the "RemoveNodeAggregate" command to remove a node.

        $node->setRemoved(false);
    }
}

