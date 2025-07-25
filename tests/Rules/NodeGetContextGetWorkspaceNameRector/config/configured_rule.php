<?php

declare (strict_types=1);
//namespace RectorPrefix202208;

use Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceNameRector;
use Rector\Config\RectorConfig;
    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([NodeGetContextGetWorkspaceNameRector::class]);
return $rectorConfig;
