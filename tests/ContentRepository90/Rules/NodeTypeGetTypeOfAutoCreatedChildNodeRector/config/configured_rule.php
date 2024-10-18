<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use Neos\Rector\ContentRepository90\Rules\NodeTypeGetTypeOfAutoCreatedChildNodeRector;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->rule(NodeTypeGetTypeOfAutoCreatedChildNodeRector::class);

    $rectorConfig->ruleWithConfiguration(InjectServiceIfNeededRector::class, [
        new AddInjection('contentRepositoryRegistry', ContentRepositoryRegistry::class),
    ]);
};
