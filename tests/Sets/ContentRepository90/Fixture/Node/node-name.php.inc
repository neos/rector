<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $nodeName = $node->getName();

        $node->setName('newName');
    }
}
-----
<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $nodeName = $node->nodeName;

        // TODO 9.0 migration: !! Node::setName() is not supported by the new CR. Use the "ChangeNodeAggregateName" command to change the node name.
        $node->setName('newName');
    }
}
