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

final class WorkspaceGetTitleRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector,
    )
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Workspace::getTitle()" will be rewritten', __CLASS__);
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
        if (!$this->isName($node->name, 'getTitle')) {
            return null;
        }

        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::todoComment('Make this code aware of multiple Content Repositories.')
            ],
            $node
        );

        return
            $this->nodeFactory->createPropertyFetch(
                $this->nodeFactory->createPropertyFetch(
                    $this->this_workspaceService_getWorkspaceMetadata(
                        $this->contentRepositoryId_fromString('default'),
                        $this->nodeFactory->createPropertyFetch($node->var, 'workspaceName')
                    ),
                    'title',
                ), 'value'
            );
    }
}
