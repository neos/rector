<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([\Neos\Rector\ContentRepository90\Rules\NodeFactoryResetRector::class]);
return $rectorConfig;
