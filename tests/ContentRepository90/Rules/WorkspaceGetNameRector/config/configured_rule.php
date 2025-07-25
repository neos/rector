<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
use Neos\Rector\ContentRepository90\Rules\WorkspaceGetNameRector;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([WorkspaceGetNameRector::class]);
return $rectorConfig;
