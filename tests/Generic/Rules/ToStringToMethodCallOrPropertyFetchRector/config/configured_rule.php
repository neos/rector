<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\ToStringToMethodCallOrPropertyFetchRector;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withConfiguredRule(ToStringToMethodCallOrPropertyFetchRector::class, [
        'SomeObject' => 'methodName()',
        'SomeOtherObject' => 'propertyName',
    ]);
return $rectorConfig;
