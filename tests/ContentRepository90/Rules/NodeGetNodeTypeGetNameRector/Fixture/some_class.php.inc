<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $nodeType = $node->getNodeType();
        $nodeTypeName = $nodeType->getName();

        $nodeTypeName = $node->getNodeType()->getName();
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
        $nodeTypeName = $nodeType->getName();

        $nodeTypeName = $node->nodeTypeName->value;
    }
}

?>
