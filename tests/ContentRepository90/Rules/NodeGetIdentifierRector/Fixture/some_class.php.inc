<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $nodeIdentifier = $node->getIdentifier();
    }
}

?>
-----
<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        // TODO 9.0 migration: Check if you could change your code to work with the NodeAggregateId value object instead.

        $nodeIdentifier = $node->nodeAggregateId->value;
    }
}

?>
