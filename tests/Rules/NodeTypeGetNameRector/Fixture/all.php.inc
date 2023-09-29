<?php

use Neos\ContentRepository\Core\NodeType\NodeType;

class SomeClass
{
    public function run(NodeType $nodetype)
    {
        $nodetype = $nodetype->getName();
    }
}

?>
-----
<?php

use Neos\ContentRepository\Core\NodeType\NodeType;

class SomeClass
{
    public function run(NodeType $nodetype)
    {
        $nodetype = $nodetype->name->value;
    }
}

?>
