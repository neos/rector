<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use PhpParser\Node;
use PHPStan\Type\ObjectType;

trait ContextRectorTrait
{
    private function isContextWithMethod(Node\Expr\MethodCall $node, string $methodName): bool
    {
        return (
            $node->name == $methodName
            && $this->isObjectType($node->var, new ObjectType('Neos\Rector\ContentRepository90\Legacy\LegacyContextStub')
            )
        );
    }
}
