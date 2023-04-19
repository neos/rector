<?php
declare(strict_types=1);

namespace Neos\Rector\Generic\ValueObject;

class RemoveParentClass
{
    public function __construct(
        public readonly string $parentClassName,
        public readonly string $comment,
    )
    {
    }
}
