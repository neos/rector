<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspaceGetBaseWorkspacesRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([WorkspaceGetBaseWorkspacesRector::class]);
return $rectorConfig;
