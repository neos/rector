<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeGetDepthRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([NodeGetDepthRector::class]);
return $rectorConfig;
