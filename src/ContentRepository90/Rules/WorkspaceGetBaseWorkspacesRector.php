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

final class WorkspaceGetBaseWorkspacesRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector,
    )
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Workspace::getBaseWorkspaces()" will be rewritten', __CLASS__);
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

        if (!$this->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Core\SharedModel\Workspace\Workspace::class))) {
            return null;
        }
        if (!$this->isName($node->name, 'getBaseWorkspaces')) {
            return null;
        }

        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::withTodoComment('Check if you could change your code to work with the WorkspaceName value object instead and make this code aware of multiple Content Repositories.',
                    self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
                )
            ],
            $node
        );


        return
            $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createMethodCall(
                    new Variable('contentRepository'),
                    'findWorkspaces'
                ),
                'getBaseWorkspaces',
                [$this->nodeFactory->createPropertyFetch($node->var, 'workspaceName')]
            );
    }
}
