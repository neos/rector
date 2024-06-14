<?php

use Neos\ContentRepository\Utility;

class SomeClass
{
    public function __construct($nodeName)
    {
        $nodeName = Utility::renderValidNodeName($nodeName);
        $liveNodeName = Utility::renderValidNodeName('live');
    }
}

?>
-----
<?php

use Neos\ContentRepository\Utility;

class SomeClass
{
    public function __construct($nodeName)
    {
        $nodeName = \Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString($nodeName)->value;
        $liveNodeName = \Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString('live')->value;
    }
}

?>
