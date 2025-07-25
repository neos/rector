<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $nodeType = $node->getNodeType();
        $childNodes = $nodeType->getAutoCreatedChildNodes();
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
        // TODO 9.0 migration: NodeType::tetheredNodeTypeDefinitions() is not a 1:1 replacement of NodeType::getAutoCreatedChildNodes(). You need to change your code to work with new TetheredNodeTypeDefinition object.

        $childNodes = $nodeType->tetheredNodeTypeDefinitions;
    }
}

?>
