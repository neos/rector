<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeSearchServiceRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
    ) {
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
        return [\PhpParser\Node\Expr\MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if (
            !$this->isObjectType($node->var, new ObjectType(\Neos\Neos\Domain\Service\NodeSearchService::class))
             && !$this->isObjectType($node->var, new ObjectType(\Neos\Neos\Domain\Service\NodeSearchServiceInterface::class))
        ) {
            return null;
        }
        if (!$this->isName($node->name, 'findByProperties')) {
            return null;
        }

        if (!isset($node->args[3])) {
            $nodeExpr = self::assign('node', new \PhpParser\Node\Scalar\String_('we-need-a-node-here'));
            $nodeNode = $nodeExpr->expr->var;

//            $this->nodesToAddCollector->addNodesBeforeNode(
//                [
//                    self::withTodoComment('The replacement needs a node as starting point for the search. Please provide a node, to make this replacement working.', $nodeExpr),
//                    $subgraphNode = self::assign('subgraph', $this->this_contentRepositoryRegistry_subgraphForNode($nodeNode)),
//                ],
//                $node
//            );

        } else {
//            $this->nodesToAddCollector->addNodesBeforeNode(
//                [
//                    self::withTodoComment('This could be a suitable replacement. Please check if all your requirements are still fulfilled.',
//                        $subgraphNode = self::assign('subgraph', $this->this_contentRepositoryRegistry_subgraphForNode($node->args[3]->value))
//                    )
//                ],
//                $node
//
//            );
            $nodeNode = $node->args[3]->value;

        }
        return $node;
//        return $this->nodeFactory->createMethodCall(
//            $subgraphNode->expr->var,
//            'findDescendantNodes',
//            [
//                $this->nodeFactory->createPropertyFetch(
//                    $nodeNode,
//                    'aggregateId'
//                ),
//                $this->nodeFactory->createStaticCall(
//                    \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindDescendantNodesFilter::class,
//                    'create',
//                    [
//                        'nodeTypes' => $this->nodeFactory->createStaticCall(
//                            \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\NodeType\NodeTypeCriteria::class,
//                            'create',
//                            [
//                                $this->nodeFactory->createStaticCall(
//                                    \Neos\ContentRepository\Core\NodeType\NodeTypeNames::class,
//                                    'fromStringArray',
//                                    [
//                                        $node->args[1]->value,
//                                    ]
//                                ),
//                                $this->nodeFactory->createStaticCall(
//                                    \Neos\ContentRepository\Core\NodeType\NodeTypeNames::class,
//                                    'createEmpty',
//                                ),
//                            ]
//                        ),
//                        'searchTerm' => $node->args[0]->value,
//                    ]
//                )
//            ]
//        );
    }
}

/**
 * \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindDescendantNodesFilter::create(
 *      nodeTypes: \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\NodeType\NodeTypeCriteria::create(
 *          \Neos\ContentRepository\Core\NodeType\NodeTypeNames::fromStringArray($searchNodeTypes),
 *          \Neos\ContentRepository\Core\NodeType\NodeTypeNames::createEmpty()
 *      ),
 *      searchTerm: $term
 *  )
 */
