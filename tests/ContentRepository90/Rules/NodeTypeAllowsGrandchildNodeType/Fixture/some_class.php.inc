<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

class SomeClass
{
    public function run(NodeLegacyStub $node)
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

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
    public function run(NodeLegacyStub $node)
    {
        $parentNodeName = 'name';
        $nodeType = $node->getNodeType();
        $grandParentsNodeType = $node->getParent()->getParent()->getNodeType();
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\Factory\ContentRepositoryId::fromString('default'));

        $contentRepository->getNodeTypeManager()->isNodeTypeAllowedAsChildToTetheredNode($grandParentsNodeType, \Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString($parentNodeName), $nodeType);
    }
}

?>
