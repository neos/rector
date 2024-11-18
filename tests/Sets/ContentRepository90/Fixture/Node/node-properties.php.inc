<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $value = $node->getProperty('foo');
        $bool = $node->hasProperty('foo');

        $node->setProperty('foo', 'bar');

        $node->removeProperty('bar');

        $propertyNames = $node->getPropertyNames();
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
        $value = $node->getProperty('foo');
        $bool = $node->hasProperty('foo');
        // TODO 9.0 migration: !! Node::setProperty() is not supported by the new CR. Use the "SetNodeProperties" command to change property values.


        $node->setProperty('foo', 'bar');
        // TODO 9.0 migration: !! Node::setProperty() is not supported by the new CR. Use the "SetNodeProperties" command to remove a property values.


        $node->removeProperty('bar');

        $propertyNames = array_keys(iterator_to_array($node->properties));
    }
}
