<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeIsAutoCreatedRector;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([NodeIsAutoCreatedRector::class]);
return $rectorConfig;
