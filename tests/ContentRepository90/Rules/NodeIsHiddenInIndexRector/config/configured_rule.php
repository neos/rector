<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeIsHiddenInIndexRector;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([NodeIsHiddenInIndexRector::class]);
return $rectorConfig;
