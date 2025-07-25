<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([\Neos\Rector\Generic\Rules\RemoveDuplicateCommentRector::class]);
return $rectorConfig;
