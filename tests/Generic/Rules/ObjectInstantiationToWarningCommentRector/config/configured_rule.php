<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\ObjectInstantiationToWarningCommentRector;
use Neos\Rector\Generic\ValueObject\ObjectInstantiationToWarningComment;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ObjectInstantiationToWarningCommentRector::class, [
        new ObjectInstantiationToWarningComment(\My\Class\To\Comment::class, 'This is a comment'),
    ]);
};
