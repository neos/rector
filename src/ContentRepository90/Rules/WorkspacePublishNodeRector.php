<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

final class WorkspacePublishNodeRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
    )
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Workspace::publishNode()" will be rewritten', __CLASS__);
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
        if (!$this->isName($node->name, 'publishNode')) {
            return null;
        }

//        $this->nodesToAddCollector->addNodesBeforeNode(
//            [
//                self::todoComment('Check if this matches your requirements as this is not a 100% replacement. Make this code aware of multiple Content Repositories.')
//            ],
//            $node
//        );

        return
            $this->nodeFactory->createMethodCall(
                $this->this_workspacePublishingService(),
                'publishChangesInDocument',
                [
                    $this->contentRepositoryId_fromString('default'),
                    $this->nodeFactory->createPropertyFetch($node->var, 'workspaceName'),
                    $node->args[0]
                ]
            );
    }
}
