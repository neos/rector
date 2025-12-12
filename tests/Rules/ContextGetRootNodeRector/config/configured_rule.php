<?php

declare (strict_types=1);

//namespace RectorPrefix202208;

use Neos\Rector\ContentRepository90\Rules\ContextGetRootNodeRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([ContextGetRootNodeRector::class]);
return $rectorConfig;
