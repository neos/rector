<?php

declare (strict_types=1);

use Rector\Config\RectorConfig;
use Neos\Rector\ContentRepository90\Rules\WorkspaceGetTitleRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(WorkspaceGetTitleRector::class);
};
