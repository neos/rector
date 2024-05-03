<?php

declare (strict_types=1);

use Neos\Rector\Core\FusionProcessing\FusionFileProcessor;
use Rector\Config\RectorConfig;
use Neos\Rector\ContentRepository90\Rules\FusionNodeContextPathRector;

return RectorConfig::configure()
    ->registerService(FusionFileProcessor::class)
    ->withoutParallel()
    ->withRules([
        FusionNodeContextPathRector::class
    ]);