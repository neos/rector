<?php

use Neos\ContentRepository\Domain\Model\NodeInterface;

class SomeClass
{
    public function run(\Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub $node)
    {
        if ($node->getContext()->isLive() && $foo == 'bar') {
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
    public function run(\Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub $node)
    {
        if (!$this->renderingModeService->findByCurrentUser()->isEdit && !$this->renderingModeService->findByCurrentUser()->isPreview && $foo == 'bar') {
            return true;
        }
    }
}

?>
