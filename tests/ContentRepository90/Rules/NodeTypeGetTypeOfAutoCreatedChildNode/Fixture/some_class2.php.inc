<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $nodeName = NodeName::fromString('name');
        $node->getNodeType()->getTypeOfAutoCreatedChildNode($nodeName);
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
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\Factory\ContentRepositoryId::fromString('default'));
        $contentRepository->getNodeTypeManager()->getTypeOfTetheredNode($node->getNodeType(), $nodeName);
    }
}

?>
