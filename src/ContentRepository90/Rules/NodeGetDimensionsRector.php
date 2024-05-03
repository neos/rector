<?php

declare (strict_types=1);
namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeGetDimensionsRector extends AbstractRector
{
    use AllTraits;

    public function __construct(

    )
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeInterface::getChildNodes()" will be rewritten', __CLASS__);
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

        if (!$this->isObjectType($node->var, new ObjectType(\Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub::class))) {
            return null;
        }
        if (!$this->isName($node->name, 'getDimensions')) {
            return null;
        }

//        $this->nodesToAddCollector->addNodesBeforeNode(
//            [
//                self::todoComment('Try to remove the toLegacyDimensionArray() call and make your codebase more typesafe.')
//            ],
//            $node
//        );

        return $this->node_originDimensionSpacePoint_toLegacyDimensionArray($node->var);
    }
}
