<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Neos\Neos\Domain\Service\RenderingModeService;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->rule(\Neos\Rector\ContentRepository90\Rules\ContextGetCurrentRenderingModeRector::class);

    $rectorConfig->ruleWithConfiguration(InjectServiceIfNeededRector::class, [
        new AddInjection('renderingModeService', RenderingModeService::class)
    ]);
};
