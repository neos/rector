<?php

declare (strict_types=1);
namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;

use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeGetChildNodesRector extends AbstractRector
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
        if (!$this->isName($node->name, 'getChildNodes')) {
            return null;
        }
        $nodeTypeFilterExpr = null;
        $limitExpr = null;
        $offsetExpr = null;

        foreach ($node->args as $index => $arg) {
            $argumentName = $arg?->name?->name;
            $namedArgument = $argumentName !== null;

            if (($namedArgument && $argumentName === 'nodeTypeFilter') ||  !$namedArgument && $index === 0) {
                assert($arg instanceof Node\Arg);

                if ($arg->value instanceof Node\Scalar\String_) {
                    $nodeTypeFilterExpr = $arg->value;
                }
            }
            if (($namedArgument && $argumentName === 'limit') ||  !$namedArgument && $index === 1) {
                assert($arg instanceof Node\Arg);
                $limitExpr = $arg->value;
            }
            if (($namedArgument && $argumentName === 'offset') ||  !$namedArgument && $index === 2) {
                assert($arg instanceof Node\Arg);
                $offsetExpr = $arg->value;
            }
        }

//        $this->nodesToAddCollector->addNodesBeforeNode(
//            [
//                self::assign('subgraph', $this->this_contentRepositoryRegistry_subgraphForNode($node->var)),
//                self::todoComment('Try to remove the iterator_to_array($nodes) call.')
//            ],
//            $node
//        );

        return $this->iteratorToArray(
            $this->subgraph_findChildNodes($node->var, $nodeTypeFilterExpr, $limitExpr, $offsetExpr)
        );
    }
}
