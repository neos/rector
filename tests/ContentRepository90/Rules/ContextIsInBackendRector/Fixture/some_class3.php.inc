<?php

use Neos\ContentRepository\Domain\Model\NodeInterface;

class SomeClass
{
    public function run(NodeInterface $node)
    {
        if ($node->getContext()->isInBackend() && $foo == 'bar') {
            return true;
        }
    }
}

?>
-----
<?php

use Neos\ContentRepository\Domain\Model\NodeInterface;

class SomeClass
{
    #[\Neos\Flow\Annotations\Inject]
    protected \Neos\Neos\Domain\Service\RenderingModeService $renderingModeService;
    public function run(NodeInterface $node)
    {
        if ($this->renderingModeService->findByCurrentUser()->isEdit && $foo == 'bar') {
            return true;
        }
    }
}

?>
