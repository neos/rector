<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function getNode():NodeLegacyStub {
        return new NodeLegacyStub();
    }

    public function getPathByNode(NodeLegacyStub $node)
    {
        $path = $node->getPath();
    }

    public function getPathByGetNode()
    {
        $path = $this->getNode()->getPath();
    }

    public function getPath(NodeLegacyStub $node)
    {
        return $node->getPath();
    }

    public function getPathAsParameter(NodeLegacyStub $node)
    {
        $path = MyFoo::do($node->getPath());
    }
}

?>
-----
<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function getNode():NodeLegacyStub {
        return new NodeLegacyStub();
    }

    public function getPathByNode(NodeLegacyStub $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
        $path = (string) $subgraph->findNodePath($node->aggregateId);
    }

    public function getPathByGetNode()
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($this->getNode());
        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
        $path = (string) $subgraph->findNodePath($this->getNode()->aggregateId);
    }

    public function getPath(NodeLegacyStub $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
        return (string) $subgraph->findNodePath($node->aggregateId);
    }

    public function getPathAsParameter(NodeLegacyStub $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
        $path = MyFoo::do((string) $subgraph->findNodePath($node->aggregateId));
    }
}

?>
