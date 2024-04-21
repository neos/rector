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
        new FusionPrototypeNameReplacement('Neos.Neos:Raw', 'Neos.Neos:NewRaw', 'Neos.Neos:Raw: This comment should be added on top of the file.'),
        new FusionPrototypeNameReplacement('Neos.Neos:NotExisting', 'Neos.Neos:NewNotExisting', 'Neos.Neos:NotExisting: This comment should NOT be added on top of the file.'),
        new FusionPrototypeNameReplacement('Neos.Neos:SomethingOld', 'Neos.Neos:SomethingNew'),
        new FusionPrototypeNameReplacement('Neos.Neos:SomethingOlder', 'Neos.Neos:SomethingNewer', 'Neos.Neos:SomethingOlder: This comment should be added on top of the file.'),
    ]);
};
