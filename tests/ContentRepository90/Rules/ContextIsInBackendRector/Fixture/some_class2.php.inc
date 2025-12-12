<?php

class SomeClass
{
    public function run(\Neos\ContentRepository\Domain\Model\Node $node)
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
    public function run(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $context = $node->getContext();
        $isInBackend = $this->renderingModeService->findByCurrentUser()->isEdit;
    }
}

?>
