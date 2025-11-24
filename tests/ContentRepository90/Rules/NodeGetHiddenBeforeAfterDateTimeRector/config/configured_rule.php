<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeGetHiddenBeforeAfterDateTimeRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([NodeGetHiddenBeforeAfterDateTimeRector::class]);
return $rectorConfig;
