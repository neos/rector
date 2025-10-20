
<?php

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

class SomeClass
{
    public function run(Node $node)
    {
        $nodeName = NodeName::fromString('name');
        $nodeType = $node->getNodeType();
        $type = $nodeType->getTypeOfAutoCreatedChildNode($nodeName);
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
    public function run(Node $node)
    {
        $nodeName = NodeName::fromString('name');
        $nodeType = $node->getNodeType();
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories. If you have a Node object around you can use $node->contentRepositoryId.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        $type = $contentRepository->getNodeTypeManager()->getNodeType($nodeType->tetheredNodeTypeDefinitions->get($nodeName));
    }
}

?>
