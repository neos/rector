<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $contextPath = $node->getContextPath();
    }
}

-----
<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Core\NodeType\NodeType;

class SomeClass extends AnotherClass
{
    public function node(\Neos\ContentRepository\Core\Projection\ContentGraph\Node $node)
    {
        $contextPath = \Neos\ContentRepository\Core\SharedModel\Node\NodeAddress::fromNode($node)->toJson();
    }
}

