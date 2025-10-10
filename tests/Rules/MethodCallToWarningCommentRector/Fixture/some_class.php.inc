<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $node->getNode();
        return $node->getWorkspace();
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
        // TODO 9.0 migration: !! Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub::getNode() has been removed.
        $node->getNode();
        // TODO 9.0 migration: !! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.
        return $node->getWorkspace();
    }
}

?>
