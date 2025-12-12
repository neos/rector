<?php

class SomeClass
{
    public function run(\Neos\ContentRepository\Domain\Model\Node $node)
    {
        $renderingMode = $node->getContext()->getCurrentRenderingMode();
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
        $renderingMode = $this->renderingModeService->findByCurrentUser();
    }
}

?>
