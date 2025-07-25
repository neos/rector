<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspaceGetBaseWorkspaceRector;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([WorkspaceGetBaseWorkspaceRector::class]);
return $rectorConfig;
