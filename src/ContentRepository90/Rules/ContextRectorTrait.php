<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Rector\PostRector\Collector\NodesToAddCollector;
use PhpParser\Node\Expr\Assign;
use PhpParser\NodeDumper;

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
