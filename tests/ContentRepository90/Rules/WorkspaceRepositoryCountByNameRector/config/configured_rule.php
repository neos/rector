<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\RemoveInjectionsRector;
use Neos\Rector\Generic\ValueObject\RemoveInjection;
use Rector\Config\RectorConfig;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(\Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryCountByNameRector::class);

    $rectorConfig->ruleWithConfiguration(InjectServiceIfNeededRector::class, [
        new AddInjection('contentRepositoryRegistry', \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry::class)
    ]);
    $rectorConfig->ruleWithConfiguration(RemoveInjectionsRector::class, [
        new RemoveInjection(\Neos\ContentRepository\Domain\Repository\WorkspaceRepository::class)
    ]);
};
