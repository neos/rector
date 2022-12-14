<?php

declare (strict_types=1);

use Neos\Rector\ContentRepository90\Legacy\LegacyContextStub;
use Neos\Rector\ContentRepository90\Rules\ContextFactoryToLegacyContextStubRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig) : void {
    $rectorConfig->rule(ContextFactoryToLegacyContextStubRector::class);
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Neos\ContentRepository\Domain\Service\Context' => LegacyContextStub::class,
        'Neos\Neos\Domain\Service\ContentContext' => LegacyContextStub::class,
    ]);
};
