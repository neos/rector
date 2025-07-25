<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Neos\Neos\Domain\Service\RenderingModeService;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([\Neos\Rector\ContentRepository90\Rules\ContextGetCurrentRenderingModeRector::class]);

    $rectorConfig->withConfiguredRule(InjectServiceIfNeededRector::class, [
        new AddInjection('renderingModeService', RenderingModeService::class)
    ]);
return $rectorConfig;
