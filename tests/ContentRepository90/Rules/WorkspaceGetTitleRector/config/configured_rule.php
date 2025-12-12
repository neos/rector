<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspaceGetTitleRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([WorkspaceGetTitleRector::class]);
return $rectorConfig;
