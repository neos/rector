<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeFindParentNodeRector;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([NodeFindParentNodeRector::class]);
return $rectorConfig;
