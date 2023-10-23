<?php

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\RenderingModeService;

class SomeClass
{
    #[Flow\Inject]
    protected RenderingModeService $renderingModeService;

    public function run()
    {
        $currentRenderingMode = $this->renderingModeService->findByCurrentUser();
    }
}

?>
-----
<?php

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Service\RenderingModeService;

class SomeClass
{
    #[Flow\Inject]
    protected RenderingModeService $renderingModeService;

    public function run()
    {
        $currentRenderingMode = $this->renderingModeService->findByCurrentUser();
    }
}

?>
