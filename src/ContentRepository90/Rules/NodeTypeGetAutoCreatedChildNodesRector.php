<?php

declare (strict_types=1);
namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Neos\ContentRepository\Core\NodeType\NodeType;

final class NodeTypeGetAutoCreatedChildNodesRector extends AbstractRector
{
    use AllTraits;

    public function __construct(

    )
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return CodeSampleLoader::fromFile('"$nodeType->getAutoCreatedChildNodes()" will be rewritten.', __CLASS__);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [Node\Expr\MethodCall::class];
    }
    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node) : ?Node
    {
        assert($node instanceof Node\Expr\MethodCall);

        if (!$this->isObjectType($node->var, new ObjectType(NodeType::class))) {
            return null;
        }

        if (!$this->isName($node->name, 'getAutoCreatedChildNodes')) {
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

        return $this->nodeFactory->createMethodCall(
            $this->contentRepository_getNodeTypeManager(),
            'getTetheredNodesConfigurationForNodeType',
                [$node->var]
        );
    }
}
