<?php

declare (strict_types=1);

use Neos\Rector\Core\FusionProcessing\FusionFileProcessor;
use Rector\Config\RectorConfig;
use Neos\Rector\Generic\ValueObject\FusionPrototypeNameReplacement;
use Neos\Rector\Generic\Rules\FusionReplacePrototypeNameRector;

return static function (RectorConfig $rectorConfig) : void {
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();
    $services->set(FusionFileProcessor::class);
    $rectorConfig->disableParallel(); // does not work for fusion files - see https://github.com/rectorphp/rector-src/pull/2597#issuecomment-1190120688

    $rectorConfig->ruleWithConfiguration(FusionReplacePrototypeNameRector::class, [
        new FusionPrototypeNameReplacement('Neos.Neos:Raw', 'Neos.Neos:NewRaw'),
        new FusionPrototypeNameReplacement('Neos.Neos:SomethingOld', 'Neos.Neos:SomethingNew'),
    ]);
};
