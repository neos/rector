<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspaceGetNameRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([WorkspaceGetNameRector::class]);
return $rectorConfig;
