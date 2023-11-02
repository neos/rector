<?php

class SomeClass
{
    public function run(\Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub $node)
    {
        $context = $node->getContext();
        $isLive = $context->isLive();
    }
}

?>
-----
<?php

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\Neos\Domain\Service\RenderingModeService $renderingModeService;
    public function run(\Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub $node)
    {
        $context = $node->getContext();
        $isLive = !$this->renderingModeService->findByCurrentUser()->isEdit && !$this->renderingModeService->findByCurrentUser()->isPreview;
    }
}

?>
