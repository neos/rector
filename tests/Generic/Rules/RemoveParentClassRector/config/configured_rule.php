<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\RemoveParentClassRector;
use Neos\Rector\Generic\ValueObject\RemoveParentClass;
use Rector\Config\RectorConfig;
return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->ruleWithConfiguration(RemoveParentClassRector::class, [
        new RemoveParentClass(\Foo\Bar\Baz::class, '// TODO: Neos 9.0 Migration: Stuff')
    ]);
};
