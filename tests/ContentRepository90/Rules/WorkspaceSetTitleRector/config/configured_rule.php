<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspaceSetTitleRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([WorkspaceSetTitleRector::class]);
