<?php

declare (strict_types=1);

use Neos\Neos\Domain\Service\RenderingModeService;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Rector\Config\RectorConfig;

$rectorConfig = RectorConfig::configure();
$rectorConfig->withRules([\Neos\Rector\ContentRepository90\Rules\ContextIsLiveRector::class]);

$rectorConfig->withConfiguredRule(InjectServiceIfNeededRector::class, [
    new AddInjection('renderingModeService', RenderingModeService::class)
]);
return $rectorConfig;
