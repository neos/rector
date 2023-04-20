<?php

declare (strict_types=1);
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) : void {
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();
    $services->set(\Neos\Rector\Core\YamlProcessing\YamlFileProcessor::class);
    $rectorConfig->disableParallel(); // does not work for yaml files - see https://github.com/rectorphp/rector-src/pull/2597#issuecomment-1190120688

    $rectorConfig->rule(Neos\Rector\ContentRepository90\Rules\YamlDimensionConfigRector::class);
};
