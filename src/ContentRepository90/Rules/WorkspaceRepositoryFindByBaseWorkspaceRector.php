<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class WorkspaceRepositoryFindByBaseWorkspaceRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector
    )
    {
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"WorkspaceRepository::findByBaseWorkspace()" will be rewritten.', __CLASS__);
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

        if (!$this->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Domain\Repository\WorkspaceRepository::class))) {
            return null;
        }
        if (!$this->isName($node->name, 'findByBaseWorkspace')) {
            return null;
        }


        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::withTodoComment(
                    'Make this code aware of multiple Content Repositories.',
                    self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default')))),
            ],
            $node
        );

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall(
                new Variable('contentRepository'),
                'findWorkspaces',
                []
            ),
            'getDependantWorkspaces',
            [
                $this->workspaceName_fromString($node->args[0]->value)
            ]
        );
    }
}
