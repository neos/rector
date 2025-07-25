<?php

declare (strict_types=1);
//namespace RectorPrefix202208;

use Rector\Config\RectorConfig;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use Neos\Neos\Domain\Service\RenderingModeService;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withConfiguredRule(InjectServiceIfNeededRector::class, [
        new AddInjection('contentRepositoryRegistry', ContentRepositoryRegistry::class),
        new AddInjection('renderingModeService', RenderingModeService::class),
    ]);
return $rectorConfig;
