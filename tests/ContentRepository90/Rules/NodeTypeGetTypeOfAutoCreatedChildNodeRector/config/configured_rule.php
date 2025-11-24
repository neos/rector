<?php

declare (strict_types=1);

use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use Neos\Rector\ContentRepository90\Rules\NodeTypeGetTypeOfAutoCreatedChildNodeRector;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([NodeTypeGetTypeOfAutoCreatedChildNodeRector::class]);

$rectorConfig->withConfiguredRule(InjectServiceIfNeededRector::class, [
    new AddInjection('contentRepositoryRegistry', ContentRepositoryRegistry::class),
]);
return $rectorConfig;
