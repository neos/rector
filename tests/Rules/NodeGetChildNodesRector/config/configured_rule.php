<?php

declare (strict_types=1);

//namespace RectorPrefix202208;

use Neos\Rector\ContentRepository90\Rules\NodeGetChildNodesRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([NodeGetChildNodesRector::class]);
return $rectorConfig;
