<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        return $node->getContextPath();
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
        return \Neos\ContentRepository\Core\SharedModel\Node\NodeAddress::fromNode($node)->toJson();
    }
}

?>
