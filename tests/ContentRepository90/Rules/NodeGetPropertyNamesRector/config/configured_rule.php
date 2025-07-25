<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeGetPropertyNamesRector;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([NodeGetPropertyNamesRector::class]);
return $rectorConfig;
