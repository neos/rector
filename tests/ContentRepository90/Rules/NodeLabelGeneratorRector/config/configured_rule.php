<?php

declare (strict_types=1);

use Neos\Neos\Domain\NodeLabel\NodeLabelGeneratorInterface;
use Neos\Rector\ContentRepository90\Rules\NodeLabelGeneratorRector;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withRules([NodeLabelGeneratorRector::class]);

    $rectorConfig->withConfiguredRule(InjectServiceIfNeededRector::class, [
        new AddInjection('nodeLabelGenerator', NodeLabelGeneratorInterface::class),
    ]);
return $rectorConfig;