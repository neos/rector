<?php

declare (strict_types=1);
namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeFactoryResetRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
    )
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeFactory::reset()" will be removed.', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node) : ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if (!$this->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Domain\Factory\NodeFactory::class))) {
            return null;
        }
        if (!$this->isName($node->name, 'reset')) {
            return null;
        }

        $this->removeNode($node);

        return $node;
    }
}
