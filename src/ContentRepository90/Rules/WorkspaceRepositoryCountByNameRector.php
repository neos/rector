<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class WorkspaceRepositoryCountByNameRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector
    ) {
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"WorkspaceRepository::countByName()" will be rewritten.', __CLASS__);
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
        if (!$this->isName($node->name, 'countByName')) {
            return null;
        }


        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
                self::todoComment('remove ternary operator (...? 1 : 0 ) - unnecessary complexity',)
            ],
            $node
        );

        return new Node\Expr\Ternary(
            new Node\Expr\BinaryOp\NotIdentical(
                $this->contentRepository_findWorkspaceByName($this->workspaceName_fromString($node->args[0]->value)),
                new Expr\ConstFetch(new Node\Name('null'))
            ),
            new Node\Scalar\LNumber(1),
            new Node\Scalar\LNumber(0)
        );
    }
}
