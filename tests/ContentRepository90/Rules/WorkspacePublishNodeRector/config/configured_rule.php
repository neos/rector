<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspacePublishNodeRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([WorkspacePublishNodeRector::class]);
return $rectorConfig;
