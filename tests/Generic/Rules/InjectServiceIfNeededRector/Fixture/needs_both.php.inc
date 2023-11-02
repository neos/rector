<?php

use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

class SomeClass
{
    public function run(NodeLegacyStub $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $currentRenderingMode = $this->renderingModeService->findByCurrentUser();
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
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\Neos\Domain\Service\RenderingModeService $renderingModeService;
    public function run(NodeLegacyStub $node)
    {
        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
        $currentRenderingMode = $this->renderingModeService->findByCurrentUser();
    }
}

?>
