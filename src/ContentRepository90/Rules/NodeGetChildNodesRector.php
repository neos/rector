<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\NodeFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeGetChildNodesRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeInterface::getChildNodes()" will be rewritten', __CLASS__);
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
        if (!in_array('expr',$node->getSubNodeNames())) {
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
                        $node->name->toString() === 'getChildNodes'
                    ) {
                        if ($this->nodeTypeResolver->isObjectType($node->var, new ObjectType(\Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub::class))) {
                            $this->changed = true;
                            $this->nodeVar = $node->var;

                            $nodeTypeFilterExpr = null;
                            $limitExpr = null;
                            $offsetExpr = null;

                            foreach ($node->args as $index => $arg) {
                                $argumentName = $arg?->name?->name;
                                $namedArgument = $argumentName !== null;

                                if (($namedArgument && $argumentName === 'nodeTypeFilter') || (!$namedArgument && $index === 0)) {
                                    assert($arg instanceof Node\Arg);

                                    if ($arg->value instanceof Node\Scalar\String_) {
                                        $nodeTypeFilterExpr = $arg->value;
                                    }
                                }
                                if (($namedArgument && $argumentName === 'limit') || (!$namedArgument && $index === 1)) {
                                    assert($arg instanceof Node\Arg);
                                    $limitExpr = $arg->value;
                                }
                                if (($namedArgument && $argumentName === 'offset') || (!$namedArgument && $index === 2)) {
                                    assert($arg instanceof Node\Arg);
                                    $offsetExpr = $arg->value;
                                }
                            }
                            return $this->iteratorToArray(
                                $this->subgraph_findChildNodes($node->var, $nodeTypeFilterExpr, $limitExpr, $offsetExpr)
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
            self::assign(
                'subgraph',
                $this->this_contentRepositoryRegistry_subgraphForNode($visitor->nodeVar)
            ),
            self::withTodoComment(
                'Try to remove the iterator_to_array($nodes) call.',
                $node
            )
        ];
    }
}
