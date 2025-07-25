<?php

declare (strict_types=1);

use Neos\Rector\Generic\Rules\ObjectInstantiationToWarningCommentRector;
use Neos\Rector\Generic\ValueObject\ObjectInstantiationToWarningComment;
use Rector\Config\RectorConfig;

    $rectorConfig = RectorConfig::configure();
    $rectorConfig->withConfiguredRule(ObjectInstantiationToWarningCommentRector::class, [
        new ObjectInstantiationToWarningComment(\My\Class\To\Comment::class, 'This is a comment'),
    ]);
return $rectorConfig;
