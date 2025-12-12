<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $nodeType = $node->getNodeType();
        $childNodes = $nodeType->getAutoCreatedChildNodes();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $nodeType = $node->getNodeType();
        // TODO 9.0 migration: NodeType::tetheredNodeTypeDefinitions() is not a 1:1 replacement of NodeType::getAutoCreatedChildNodes(). You need to change your code to work with new TetheredNodeTypeDefinition object.
        $childNodes = $nodeType->tetheredNodeTypeDefinitions;
    }
}

?>
