<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
use Neos\Rector\ContentRepository90\Rules\WorkspaceGetTitleRector;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([WorkspaceGetTitleRector::class]);
return $rectorConfig;
