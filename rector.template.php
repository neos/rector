<?php

declare(strict_types=1);

use Neos\Rector\NeosRectorSets;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        NeosRectorSets::CONTENTREPOSITORY_9_0,
        //NeosRectorSets::NEOS_8_4,
    ]);

    $rectorConfig->autoloadPaths([
        __DIR__ . '/Packages',
        __DIR__ . '/DistributionPackages',
    ]);

    $rectorConfig->paths([
        // TODO: Start adding your paths here, like so:
        __DIR__ . '/DistributionPackages/'
    ]);
};
