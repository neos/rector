<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Neos\Neos\Domain\Service\RenderingModeService;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([\Neos\Rector\ContentRepository90\Rules\ContentRepositoryUtilityRenderValidNodeNameRector::class]);
return $rectorConfig;
