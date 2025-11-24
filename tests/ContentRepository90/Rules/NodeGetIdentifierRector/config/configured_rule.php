<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\NodeGetIdentifierRector;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([NodeGetIdentifierRector::class]);
return $rectorConfig;
