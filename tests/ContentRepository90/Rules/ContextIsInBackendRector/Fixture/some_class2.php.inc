<?php

class SomeClass
{
    public function run(\Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub $node)
    {
        $context = $node->getContext();
        $isInBackend = $context->isInBackend();
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
        $isInBackend = $this->renderingModeService->findByCurrentUser()->isEdit;
    }
}

?>
