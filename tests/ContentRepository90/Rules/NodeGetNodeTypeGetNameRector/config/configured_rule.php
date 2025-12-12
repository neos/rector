<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeGetNodeTypeGetNameRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([NodeGetNodeTypeGetNameRector::class]);
return $rectorConfig;
