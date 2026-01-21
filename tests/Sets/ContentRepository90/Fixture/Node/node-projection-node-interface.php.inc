<?php

namespace Neos\Rector\Test;

class SomeClass extends AnotherClass
{
    public function getNode(): \Neos\ContentRepository\Domain\Projection\Content\NodeInterface {
        return $node;
    }

    public function node()
    {
        $nodeIdentifier = (string) $this->getNode()->getNodeAggregateIdentifier();
        $nodeTypeName = (string) $this->getNode()->getNodeTypeName();
        $nodeName = (string) $this->getNode()->getNodeName();
    }
}

-----
<?php

namespace Neos\Rector\Test;

class SomeClass extends AnotherClass
{
    public function getNode(): \Neos\ContentRepository\Core\Projection\ContentGraph\Node {
        return $node;
    }

    public function node()
    {
        $nodeIdentifier = (string) $this->getNode()->aggregateId;
        $nodeTypeName = (string) $this->getNode()->nodeTypeName;
        $nodeName = (string) $this->getNode()->nodeName;
    }
}

