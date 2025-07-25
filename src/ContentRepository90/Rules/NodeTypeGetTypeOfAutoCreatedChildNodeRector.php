<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeTypeGetTypeOfAutoCreatedChildNodeRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"$nodeType->getTypeOfAutoCreatedChildNode($nodeName)" will be rewritten.', __CLASS__);
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

        if (!$this->isName($node->name, 'getTypeOfAutoCreatedChildNode')) {
            return null;
        }

//        $this->nodesToAddCollector->addNodesBeforeNode(
//            [
//                self::withTodoComment(
//                    'Make this code aware of multiple Content Repositories. If you have a Node object around you can use $node->contentRepositoryId.',
//                    self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
//                )
//            ],
//            $node
//        );

        return
            $this->nodeFactory->createMethodCall(
                $this->contentRepository_getNodeTypeManager(),
                'getNodeType',
                [$this->nodeFactory->createMethodCall(
                    $this->nodeFactory->createPropertyFetch(
                        $node->var,
                        'tetheredNodeTypeDefinitions'
                    ),
                    'get',
                    $node->args
                )]
            );
    }
}
