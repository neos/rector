<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function run(Node $node)
    {
        return $node->isHidden();
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
        return $node->tags->contain(\Neos\Neos\Domain\SubtreeTagging\NeosSubtreeTag::disabled());
    }
}

?>
