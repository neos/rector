<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ContextGetFirstLevelNodeCacheRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Context::getFirstLevelNodeCache()" will be removed.', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Expression $node
     */
    public function refactor(Node $node)
    {
        assert($node instanceof Node\Stmt\Expression);

        if ($this->containsContextGetFirstLevelNodeCache($node->expr)) {
            return NodeVisitor::REMOVE_NODE;
        }
        return $node;
    }

    private function containsContextGetFirstLevelNodeCache(Node\Expr $expr): bool
    {
        $nodeFinder = new NodeFinder();
        return $nodeFinder->findFirst(
                $expr,
                fn (Node $node) => $node instanceof Node\Expr\MethodCall
                    // WARNING: The System cannot infer the Context type properly, as the factory has no types.
                    // Thus, we simply check on the method name getFirstLevelNodeCache() which is unique enough.
                    //&& (
                    //    $this->isObjectType($node->var, new ObjectType(LegacyContextStub::class))
                    //    || $this->isObjectType($node->var, new ObjectType('Neos\ContentRepository\Domain\Service\Context'))
                    //    || $this->isObjectType($node->var, new ObjectType('Neos\Neos\Domain\Service\ContentContext'))
                    && $this->isName($node->name, 'getFirstLevelNodeCache')
            ) !== null;
    }
}
