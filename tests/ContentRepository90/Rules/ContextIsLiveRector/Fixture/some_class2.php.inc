<?php

class SomeClass
{
    public function run(\Neos\ContentRepository\Domain\Model\Node $node)
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
    public function run(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $context = $node->getContext();
        $isLive = !$this->renderingModeService->findByCurrentUser()->isEdit && !$this->renderingModeService->findByCurrentUser()->isPreview;
    }
}

?>
