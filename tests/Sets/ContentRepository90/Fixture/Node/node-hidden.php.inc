<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Domain\NodeAggregate\NodeName;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $hidden = $node->isHidden();

        $node->setHidden(false);
        $node->setHidden(true);
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
        $hidden = $node->tags->contain(\Neos\Neos\Domain\SubtreeTagging\NeosSubtreeTag::disabled());
        // TODO 9.0 migration: !! Node::setHidden() is not supported by the new CR. Use the "EnableNodeAggregate" or "DisableNodeAggregate" command to change the visibility of the node.


        $node->setHidden(false);
        // TODO 9.0 migration: !! Node::setHidden() is not supported by the new CR. Use the "EnableNodeAggregate" or "DisableNodeAggregate" command to change the visibility of the node.

        $node->setHidden(true);
    }
}

