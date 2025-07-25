<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\RemoveInjectionsRector;
use Neos\Rector\Generic\ValueObject\RemoveInjection;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(RemoveInjectionsRector::class, [
        new RemoveInjection(\Foo\Bar\Baz::class)
    ]);

