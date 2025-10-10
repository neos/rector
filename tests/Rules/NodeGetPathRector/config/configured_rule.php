<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeGetPathRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([NodeGetPathRector::class]);

