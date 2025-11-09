<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
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

final class NodeSearchServiceRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeSearchService::findDescendantNodes()" will be rewritten', __CLASS__);
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
                    public bool $hasStartingPoint = false,
                    public ?Node\Expr $nodeVar = null,
                ) {
                }

                public function leaveNode(Node $node)
                {
                    if (
                        $node instanceof MethodCall &&
                        $node->name instanceof Identifier &&
                        $node->name->toString() === 'findByProperties'
                    ) {
                        if ($this->nodeTypeResolver->isObjectType($node->var, new ObjectType(\Neos\Neos\Domain\Service\NodeSearchService::class))
                            || $this->nodeTypeResolver->isObjectType($node->var, new ObjectType(\Neos\Neos\Domain\Service\NodeSearchServiceInterface::class))
                        ) {
                            $this->changed = true;

                            if (isset($node->args[3])) {
                                $this->hasStartingPoint = true;
                                $this->nodeVar = $node->args[3]->value;
                            } else {
                                $this->nodeVar = new Node\Expr\Variable('node');
                            }

                            return $this->nodeFactory->createMethodCall(
                                'subgraph',
                                'findDescendantNodes',
                                [
                                    $this->nodeFactory->createPropertyFetch(
                                        $this->nodeVar,
                                        'aggregateId'
                                    ),
                                    $this->nodeFactory->createStaticCall(
                                        \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindDescendantNodesFilter::class,
                                        'create',
                                        [
                                            'nodeTypes' => $this->nodeFactory->createStaticCall(
                                                \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\NodeType\NodeTypeCriteria::class,
                                                'create',
                                                [
                                                    $this->nodeFactory->createStaticCall(
                                                        \Neos\ContentRepository\Core\NodeType\NodeTypeNames::class,
                                                        'fromStringArray',
                                                        [
                                                            $node->args[1]->value,
                                                        ]
                                                    ),
                                                    $this->nodeFactory->createStaticCall(
                                                        \Neos\ContentRepository\Core\NodeType\NodeTypeNames::class,
                                                        'createEmpty',
                                                    ),
                                                ]
                                            ),
                                            'searchTerm' => $node->args[0]->value,
                                        ]
                                    )
                                ]
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

        if (!$visitor->hasStartingPoint) {
            return [
                new Nop(), // Needed, to render the comment below
                self::withTodoComment(
                    'The replacement needs a node as starting point for the search. Please provide a node, to make this replacement working.',
                    self::assign($visitor->nodeVar->name, new \PhpParser\Node\Scalar\String_('we-need-a-node-here')),
                ),
                self::assign('subgraph', $this->this_contentRepositoryRegistry_subgraphForNode($visitor->nodeVar)),
                $node,
            ];
        }

        return [
            new Nop(), // Needed, to render the comment below
            self::withTodoComment(
                'This could be a suitable replacement. Please check if all your requirements are still fulfilled.',
                self::assign('subgraph', $this->this_contentRepositoryRegistry_subgraphForNode($visitor->nodeVar)),
            ),
            $node,
        ];
    }
}

