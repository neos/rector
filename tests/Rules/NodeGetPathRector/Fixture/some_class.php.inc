<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function getNode():Node {
        return new Node();
    }

    public function getPathByNode(Node $node)
    {
        $path = $node->getPath();
    }

    public function getPathByGetNode()
    {
        $path = $this->getNode()->getPath();
    }

    public function getPath(Node $node)
    {
        return $node->getPath();
    }

    public function getPathAsParameter(Node $node)
    {
        $path = MyFoo::do($node->getPath());
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Model\Node;

class SomeClass
{
    public function getNode():Node {
        return new Node();
    }

    public function getPathByNode(Node $node)
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

    public function getPath(Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
        return (string) $subgraph->findNodePath($node->aggregateId);
    }

    public function getPathAsParameter(Node $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
        $path = MyFoo::do((string) $subgraph->findNodePath($node->aggregateId));
    }
}

?>
