<?php

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

class SomeClass
{
    public function run(Node $node)
    {
        $parentNodeName = 'name';
        $nodeType = $node->getNodeType();
        $grandParentsNodeType = $node->getParent()->getParent()->getNodeType();

        $grandParentsNodeType->allowsGrandchildNodeType($parentNodeName, $nodeType);
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
        $parentNodeName = 'name';
        $nodeType = $node->getNodeType();
        $grandParentsNodeType = $node->getParent()->getParent()->getNodeType();

        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));

        $contentRepository->getNodeTypeManager()->isNodeTypeAllowedAsChildToTetheredNode($grandParentsNodeType->name, \Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString($parentNodeName), $nodeType->name);
    }
}

?>
