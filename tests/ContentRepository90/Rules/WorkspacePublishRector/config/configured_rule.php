<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspacePublishRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([WorkspacePublishRector::class]);
return $rectorConfig;
