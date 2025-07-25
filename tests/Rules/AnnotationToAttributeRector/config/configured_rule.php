<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withSets([
        \Neos\Rector\NeosRectorSets::ANNOTATIONS_TO_ATTRIBUTES
    ]);
return $rectorConfig;
