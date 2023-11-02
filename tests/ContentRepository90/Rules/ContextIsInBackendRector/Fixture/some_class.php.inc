<?php

class SomeClass
{
    public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
    {
        $isInBackend = $context->isInBackend();
        if ($context->isInBackend() && $foo == 'bar') {
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
        $isInBackend = $this->renderingModeService->findByCurrentUser()->isEdit;
        if ($this->renderingModeService->findByCurrentUser()->isEdit && $foo == 'bar') {
            return true;
        }
    }
}

?>
