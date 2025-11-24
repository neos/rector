<?php

declare (strict_types=1);

//namespace RectorPrefix202208;

use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use Neos\Neos\Domain\Service\RenderingModeService;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withConfiguredRule(InjectServiceIfNeededRector::class, [
    new AddInjection('contentRepositoryRegistry', ContentRepositoryRegistry::class),
    new AddInjection('renderingModeService', RenderingModeService::class),
]);
return $rectorConfig;
