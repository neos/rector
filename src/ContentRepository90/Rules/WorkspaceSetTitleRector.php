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
use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

final class WorkspaceSetTitleRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector,
    )
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Workspace::setTitle()" will be rewritten', __CLASS__);
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

        if (!$this->isObjectType($node->var, new ObjectType(Workspace::class))) {
            return null;
        }
        if (!$this->isName($node->name, 'setTitle')) {
            return null;
        }
        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::todoComment('Make this code aware of multiple Content Repositories.')
            ],
            $node
        );

        return
            $this->nodeFactory->createMethodCall(
                $this->this_workspaceService(),
                'setWorkspaceTitle',
                [$this->contentRepositoryId_fromString('default'),
                    $this->nodeFactory->createPropertyFetch($node->var, 'workspaceName'),
                    $this->nodeFactory->createStaticCall(\Neos\Neos\Domain\Model\WorkspaceTitle::class, 'fromString', [$node->args[0]])
                ]
            );
    }
}
