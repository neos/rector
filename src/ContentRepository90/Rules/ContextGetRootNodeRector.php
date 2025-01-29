<?php

declare (strict_types=1);
namespace Neos\Rector\ContentRepository90\Rules;

use Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints;
use Neos\Rector\ContentRepository90\Legacy\LegacyContextStub;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ContextGetRootNodeRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector
    )
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Context::getRootNode()" will be rewritten.', __CLASS__);
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

        if (!$this->isObjectType($node->var, new ObjectType(LegacyContextStub::class))) {
            return null;
        }
        if (!$this->isName($node->name, 'getRootNode')) {
            return null;
        }

        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::withTodoComment(
                    '!! MEGA DIRTY CODE! Ensure to rewrite this; by getting rid of LegacyContextStub.',
                    self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default')))
                ),
                self::assign('workspace', $this->contentRepository_findWorkspaceByName($this->workspaceName_fromString($this->context_workspaceName_fallbackToLive($node->var)))),
                self::assign('rootNodeAggregate', $this->contentRepository_getContentGraph_findRootNodeAggregateByType($this->nodeFactory->createPropertyFetch('workspace', 'workspaceName') , $this->nodeTypeName_fromString('Neos.Neos:Sites'))),
                self::assign('subgraph', $this->contentRepository_getContentGraph_getSubgraph($this->nodeFactory->createPropertyFetch('workspace', 'workspaceName'), $this->dimensionSpacePoint_fromLegacyDimensionArray($this->context_dimensions_fallbackToEmpty($node->var)), $this->visibilityConstraints($node->var))),

            ],
            $node
        );

        return $this->subgraph_findNodeById(
            $this->nodeFactory->createPropertyFetch('rootNodeAggregate', 'nodeAggregateId')
        );
    }


    private function context_workspaceName_fallbackToLive(Node\Expr $legacyContextStub)
    {
        return new Node\Expr\BinaryOp\Coalesce(
            $this->nodeFactory->createPropertyFetch($legacyContextStub, 'workspaceName'),
            new Node\Scalar\String_('live')
        );
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
            $this->nodeFactory->createStaticCall(VisibilityConstraints::class, 'default'),
        );
    }
}
