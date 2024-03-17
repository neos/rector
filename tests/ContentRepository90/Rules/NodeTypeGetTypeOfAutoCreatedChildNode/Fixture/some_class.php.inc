<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $nodeName = NodeName::fromString('name');
        $nodeType = $node->getNodeType();
        $type = $nodeType->getTypeOfAutoCreatedChildNode($nodeName);
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
        $nodeName = NodeName::fromString('name');
        $nodeType = $node->getNodeType();
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
        $type = $contentRepository->getNodeTypeManager()->getTypeOfTetheredNode($nodeType, $nodeName);
    }
}

?>
