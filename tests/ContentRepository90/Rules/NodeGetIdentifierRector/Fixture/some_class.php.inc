<?php

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $nodeIdentifier = $node->getIdentifier();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

class SomeClass
{
    public function run(Node $node)
    {
        // TODO 9.0 migration: Check if you could change your code to work with the NodeAggregateId value object instead.

        $nodeIdentifier = $node->nodeAggregateId->value;
    }
}

?>
