<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $nodeType = $node->getNodeType();
        foreach ($nodeType->getAutoCreatedChildNodes() as $key => $_x) {
        }
    }
}

?>
-----
<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
    public function run(NodeLegacyStub $node)
    {
        $nodeType = $node->getNodeType();
        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\Factory\ContentRepositoryId::fromString('default'));
        foreach ($contentRepository->getNodeTypeManager()->getTetheredNodesConfigurationForNodeType($nodeType) as $key => $_x) {
        }
    }
}

?>
