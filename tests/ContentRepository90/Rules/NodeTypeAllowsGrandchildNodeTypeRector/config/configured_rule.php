<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use Neos\Rector\ContentRepository90\Rules\NodeTypeAllowsGrandchildNodeTypeRector;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([NodeTypeAllowsGrandchildNodeTypeRector::class]);

    $rectorConfig->withConfiguredRule(InjectServiceIfNeededRector::class, [
        new AddInjection('contentRepositoryRegistry', ContentRepositoryRegistry::class),
    ]);
return $rectorConfig;
