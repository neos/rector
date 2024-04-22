<?php

declare (strict_types=1);

use Neos\Rector\Core\FusionProcessing\FusionFileProcessor;
use Rector\Config\RectorConfig;
use Neos\Rector\Generic\Rules\FusionPrototypeNameAddCommentRector;
use Neos\Rector\Generic\ValueObject\FusionPrototypeNameAddComment;

return static function (RectorConfig $rectorConfig) : void {
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();
    $services->set(FusionFileProcessor::class);
    $rectorConfig->disableParallel(); // does not work for fusion files - see https://github.com/rectorphp/rector-src/pull/2597#issuecomment-1190120688

    $rectorConfig->ruleWithConfiguration(FusionPrototypeNameAddCommentRector::class, [
        new FusionPrototypeNameAddComment('Neos.Neos:Raw', 'Neos.Neos:Raw: Add this comment to top of file.'),
        new FusionPrototypeNameAddComment('Neos.Neos:Foo', 'Neos.Neos:Foo: Add this comment to top of file.'),
        new FusionPrototypeNameAddComment('Neos.Neos:Bar', 'Neos.Neos:Bar: Add this comment NOT to top of file.'),
        new FusionPrototypeNameAddComment('Neos.Neos:PrimaryContent', 'TODO 9.0 migration: You need to refactor "Neos.Neos:PrimaryContent" to use "Neos.Neos:ContentCollection" instead.'),
    ]);
};
