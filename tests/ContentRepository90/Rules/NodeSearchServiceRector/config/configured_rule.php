<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeGetNodeTypeGetNameRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetNodeTypeRector;
use Neos\Rector\ContentRepository90\Rules\NodeSearchServiceRector;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([NodeSearchServiceRector::class]);
return $rectorConfig;
