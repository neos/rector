<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Rules\WorkspaceGetBaseWorkspaceRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(WorkspaceGetBaseWorkspaceRector::class);
};
