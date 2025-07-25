<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspaceSetDescriptionRector;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([WorkspaceSetDescriptionRector::class]);
return $rectorConfig;
