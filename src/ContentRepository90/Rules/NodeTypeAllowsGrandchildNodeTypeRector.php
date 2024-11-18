<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeTypeAllowsGrandchildNodeTypeRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"$nodeType->allowsGrandchildNodeType($parentNodeName, $nodeType)" will be rewritten.', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if (!$this->isObjectType($node->var, new ObjectType(NodeType::class))) {
            return null;
        }

        if (!$this->isName($node->name, 'allowsGrandchildNodeType')) {
            return null;
        }

        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::withTodoComment(
                    'Make this code aware of multiple Content Repositories.',
                    self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
                )
            ],
            $node
        );

        return $this->nodeFactory->createMethodCall(
            $this->contentRepository_getNodeTypeManager(),
            'isNodeTypeAllowedAsChildToTetheredNode',
            [
                $this->nodeFactory->createPropertyFetch(
                    $node->var,
                    'name'
                ),
                $this->nodeFactory->createStaticCall(
                    \Neos\ContentRepository\Core\SharedModel\Node\NodeName::class,
                    'fromString',
                    [
                        $node->args[0]
                    ]
                ),
                $this->nodeFactory->createPropertyFetch(
                    $node->args[1]->value,
                    'name'
                )
            ]
        );
    }
}
