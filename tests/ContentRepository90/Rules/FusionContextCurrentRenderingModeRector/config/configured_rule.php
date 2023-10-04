<?php

declare (strict_types=1);
use Neos\Rector\ContentRepository90\Rules\FusionContextCurrentRenderingModeRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) : void {
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();
    $services->set(\Neos\Rector\Core\FusionProcessing\FusionFileProcessor::class);
    $rectorConfig->disableParallel(); // does not work for fusion files - see https://github.com/rectorphp/rector-src/pull/2597#issuecomment-1190120688

    $rectorConfig->rule(FusionContextCurrentRenderingModeRector::class);
};
