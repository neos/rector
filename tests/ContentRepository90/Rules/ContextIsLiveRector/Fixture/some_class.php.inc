<?php

class SomeClass
{
    public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
        $isLive = $context->isLive();
        if ($context->isLive() && $foo == 'bar') {
            return true;
        }
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
        $isLive = !$this->renderingModeService->findByCurrentUser()->isEdit && !$this->renderingModeService->findByCurrentUser()->isPreview;
        if (!$this->renderingModeService->findByCurrentUser()->isEdit && !$this->renderingModeService->findByCurrentUser()->isPreview && $foo == 'bar') {
            return true;
        }
    }
}

?>
