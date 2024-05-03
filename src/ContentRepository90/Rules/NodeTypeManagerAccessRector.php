<?php

declare (strict_types=1);
namespace Neos\Rector\ContentRepository90\Rules;

use Neos\ContentRepository\Core\NodeType\NodeTypeManager;
use Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints;
use Neos\Rector\ContentRepository90\Legacy\LegacyContextStub;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeTypeManagerAccessRector extends AbstractRector
{
    use AllTraits;

    public function __construct(

    )
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return CodeSampleLoader::fromFile('"$this->nodeTypeManager" will be rewritten.', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\PropertyFetch::class];
    }
    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node) : ?Node
    {
        assert($node instanceof Node\Expr\PropertyFetch);

        if (!$this->isObjectType($node, new ObjectType(NodeTypeManager::class))) {
            return null;
        }

//        $this->nodesToAddCollector->addNodesBeforeNode(
//            [
//                self::withTodoComment(
//                    'Make this code aware of multiple Content Repositories.',
//                    self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
//                )
//            ],
//            $node
//        );

        return $this->contentRepository_getNodeTypeManager();
    }


    private function context_workspaceName_fallbackToLive(Node\Expr $legacyContextStub)
    {
        return new Node\Expr\BinaryOp\Coalesce(
            $this->nodeFactory->createPropertyFetch($legacyContextStub, 'workspaceName'),
            new Node\Scalar\String_('live')
        );
    }


    private function workspace_currentContentStreamId(): Expr
    {
        return $this->nodeFactory->createPropertyFetch('workspace', 'currentContentStreamId');
    }

    private function context_dimensions_fallbackToEmpty(Expr $legacyContextStub)
    {
        return new Node\Expr\BinaryOp\Coalesce(
            $this->nodeFactory->createPropertyFetch($legacyContextStub, 'dimensions'),
            new Expr\Array_()
        );
    }

    private function visibilityConstraints(Expr $legacyContextStub)
    {
        return new Node\Expr\Ternary(
            $this->nodeFactory->createPropertyFetch($legacyContextStub, 'invisibleContentShown'),
            $this->nodeFactory->createStaticCall(VisibilityConstraints::class, 'withoutRestrictions'),
            $this->nodeFactory->createStaticCall(VisibilityConstraints::class, 'frontend'),
        );
    }
}
