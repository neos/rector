<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeGetContextGetWorkspaceNameRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeInterface::getContext()::getWorkspaceName()" will be rewritten', __CLASS__);
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
        // Node->getContext()->getWorkspaceName()
        if (!$this->isName($node->name, 'getWorkspaceName')) {
            return null;
        }
        if (!$node->var instanceof Node\Expr\MethodCall) {
            return null;
        }
        if (!$this->isName($node->var->name, 'getContext')) {
            return null;
        }

        if (!$this->isObjectType($node->var->var, new ObjectType(\Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub::class))) {
            return null;
        }

        $nodeVar = $node->var->var;

        return $this->nodeFactory->createPropertyFetch($nodeVar, 'workspaceName');
    }
}
