<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspaceGetDescriptionRector;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([WorkspaceGetDescriptionRector::class]);
return $rectorConfig;
