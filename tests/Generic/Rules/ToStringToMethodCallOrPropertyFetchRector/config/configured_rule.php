<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\ToStringToMethodCallOrPropertyFetchRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->ruleWithConfiguration(ToStringToMethodCallOrPropertyFetchRector::class, [
        'SomeObject' => 'methodName()',
        'SomeOtherObject' => 'propertyName',
    ]);
};
