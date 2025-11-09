<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;

use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeGetNodeTypeGetNameRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct(
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeInterface::getNodeType()->getName()" will be rewritten', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if (!$this->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Core\NodeType\NodeType::class))) {
            return null;
        }
        if (!$this->isName($node->name, 'getName')) {
            return null;
        }

        if (!$node->var instanceof Node\Expr\MethodCall) {
            return null;
        }

        $nodeTypeVar = $node->var;

        if (!$this->isObjectType($nodeTypeVar->var, new ObjectType(\Neos\ContentRepository\Domain\Model\Node::class))) {
            return null;
        }

        if (!$this->isName($nodeTypeVar->name, 'getNodeType')) {
            return null;
        }

        return
            $this->nodeFactory->createPropertyFetch(
                $this->nodeFactory->createPropertyFetch(
                    $node->var->var,
                    'nodeTypeName'
                ), 'value'
            );
    }
}
