<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\RemoveInjectionsRector;
use Neos\Rector\Generic\ValueObject\RemoveInjection;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->rule(\Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryCountByNameRector::class);

    $rectorConfig->rule(\Neos\Rector\ContentRepository90\Rules\InjectContentRepositoryRegistryIfNeededRector::class);
    $rectorConfig->ruleWithConfiguration(RemoveInjectionsRector::class, [
        new RemoveInjection(\Neos\ContentRepository\Domain\Repository\WorkspaceRepository::class)
    ]);
};
