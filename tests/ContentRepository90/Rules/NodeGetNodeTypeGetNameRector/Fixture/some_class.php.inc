<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $nodeType = $node->getNodeType();
        $nodeTypeName = $nodeType->getName();

        $nodeTypeName = $node->getNodeType()->getName();
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
        $nodeType = $node->getNodeType();
        $nodeTypeName = $nodeType->getName();

        $nodeTypeName = $node->nodeTypeName->value;
    }
}

?>
