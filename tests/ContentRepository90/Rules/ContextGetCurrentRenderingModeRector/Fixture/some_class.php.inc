<?php

class SomeClass
{
    public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
        $renderingMode = $context->getCurrentRenderingMode();
    }
}

?>
-----
<?php

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\Neos\Domain\Service\RenderingModeService $renderingModeService;
    public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
        $renderingMode = $this->renderingModeService->findByCurrentUser();
    }
}

?>
