<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeGetContextPathRector;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([NodeGetContextPathRector::class]);
return $rectorConfig;
