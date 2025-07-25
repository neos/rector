<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([\Neos\Rector\ContentRepository90\Rules\NodeTypeManagerAccessRector::class]);
return $rectorConfig;
