<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $isHidden = $node->isHiddenInIndex();
        $node->setHiddenInIndex(false);
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
        $isHidden = $node->getProperty('hiddenInMenu');
        // TODO 9.0 migration: !! Node::setHiddenInIndex() is not supported by the new CR. Use the "SetNodeProperties" command to change the property value for "hiddenInMenu".

        $node->setHiddenInIndex(false);
    }
}
