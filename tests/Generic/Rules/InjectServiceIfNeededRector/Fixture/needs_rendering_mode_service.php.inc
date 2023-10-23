<?php

class SomeClass
{
    public function run()
    {
        $currentRenderingMode = $this->renderingModeService->findByCurrentUser();
    }
}

?>
-----
<?php

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\Neos\Domain\Service\RenderingModeService $renderingModeService;
    public function run()
    {
        $currentRenderingMode = $this->renderingModeService->findByCurrentUser();
    }
}

?>
