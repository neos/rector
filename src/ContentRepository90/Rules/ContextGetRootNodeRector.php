<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints;
use Neos\Rector\ContentRepository90\Legacy\LegacyContextStub;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\NodeFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ContextGetRootNodeRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"Context::getRootNode()" will be rewritten.', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt::class];
    }

    /**
     * @param Node\Stmt $node
     */
    public function refactor(Node $node): ?array
    {
        if (!in_array('expr', $node->getSubNodeNames())) {
            return null;
        }

        $traverser = new NodeTraverser();
        $traverser->addVisitor(
            $visitor = new class($this->nodeTypeResolver, $this->nodeFactory) extends NodeVisitorAbstract {
                use AllTraits;

                public function __construct(
                    private readonly NodeTypeResolver $nodeTypeResolver,
                    protected NodeFactory $nodeFactory,
                    public bool $changed = false,
                    public ?Node\Expr $nodeVar = null,
                ) {
                }

                public function leaveNode(Node $node)
                {
                    if (
                        $node instanceof MethodCall &&
                        $node->name instanceof Identifier &&
                        $node->name->toString() === 'getRootNode'
                    ) {
                        if ($this->nodeTypeResolver->isObjectType($node->var, new ObjectType(LegacyContextStub::class))) {
                            $this->changed = true;
                            $this->nodeVar = $node->var;

                            return $this->subgraph_findNodeById(
                                $this->nodeFactory->createPropertyFetch('rootNodeAggregate', 'nodeAggregateId')
                            );
                        }
                    }
                    return null;
                }
            });

        /** @var Node\Expr $newExpr */
        $newExpr = $traverser->traverse([$node->expr])[0];

        if (!$visitor->changed) {
            return null;
        }

        $node->expr = $newExpr;

        return [
            new Nop(), // Needed, to render the comment below
            self::withTodoComment(
                '!! MEGA DIRTY CODE! Ensure to rewrite this; by getting rid of LegacyContextStub.',
                self::assign('contentRepository',
                    $this->this_contentRepositoryRegistry_get(
                        $this->contentRepositoryId_fromString('default')
                    )
                )
            ),
            self::assign('workspace',
                $this->contentRepository_findWorkspaceByName(
                    $this->workspaceName_fromString(
                        $this->context_workspaceName_fallbackToLive($visitor->nodeVar)
                    )
                )
            ),
            self::assign('rootNodeAggregate',
                $this->contentRepository_getContentGraph_findRootNodeAggregateByType(
                    $this->nodeFactory->createPropertyFetch('workspace', 'workspaceName'),
                    $this->nodeTypeName_fromString('Neos.Neos:Sites')
                )
            ),
            self::assign('subgraph',
                $this->contentRepository_getContentGraph_getSubgraph(
                    $this->nodeFactory->createPropertyFetch('workspace', 'workspaceName'),
                    $this->dimensionSpacePoint_fromLegacyDimensionArray(
                        $this->context_dimensions_fallbackToEmpty($visitor->nodeVar)
                    ),
                    $this->visibilityConstraints($visitor->nodeVar)
                )
            ),
            $node
        ];
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
