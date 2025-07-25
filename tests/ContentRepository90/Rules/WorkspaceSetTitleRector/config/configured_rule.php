<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspaceSetTitleRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(WorkspaceSetTitleRector::class);
};
