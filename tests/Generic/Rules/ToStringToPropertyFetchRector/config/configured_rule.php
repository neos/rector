<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\ToStringToPropertyFetchRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->ruleWithConfiguration(ToStringToPropertyFetchRector::class, [
        'SomeObject' => 'propertyName',
    ]);
};
