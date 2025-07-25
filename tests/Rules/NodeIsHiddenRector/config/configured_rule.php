<?php

declare (strict_types=1);
//namespace RectorPrefix202208;

use Neos\Rector\ContentRepository90\Rules\NodeIsHiddenRector;
use Rector\Config\RectorConfig;
    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([NodeIsHiddenRector::class]);
return $rectorConfig;
