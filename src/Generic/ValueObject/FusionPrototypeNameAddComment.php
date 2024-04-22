<?php
declare(strict_types=1);

namespace Neos\Rector\Generic\ValueObject;

class FusionPrototypeNameAddComment
{
    public function __construct(
        public readonly string $name,
        public readonly string $comment,
    ) {
    }
}
