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

final class NodeGetHiddenBeforeAfterDateTimeRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"NodeInterface::getHiddenBeforeDateTime()", "NodeInterface::setHiddenBeforeDateTime()", "NodeInterface::getHiddenAfterDateTime()" and "NodeInterface::setHiddenAfterDateTime()" will be rewritten', __CLASS__);
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
    public function refactor(Node $node): ?Node
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
                    public bool $isGetter = false,
                    public ?Node\Expr $nodeVar = null,
                ) {
                }

                public function leaveNode(Node $node)
                {
                    $methodNames = ['getHiddenBeforeDateTime', 'setHiddenBeforeDateTime', 'getHiddenAfterDateTime', 'setHiddenAfterDateTime'];

                    if (
                        $node instanceof MethodCall &&
                        $node->name instanceof Identifier &&
                        in_array($node->name->toString(), $methodNames)
                    ) {
                        if ($this->nodeTypeResolver->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Domain\Model\Node::class))) {
                            $this->changed = true;
                            $this->nodeVar = $node->var;

                            if ($node->name->toString() === 'getHiddenBeforeDateTime') {
                                $this->isGetter = true;
                                return $this->nodeFactory->createMethodCall($node->var, 'getProperty', ['enableAfterDateTime']);
                            }
                            if ($node->name->toString() === 'getHiddenAfterDateTime') {
                                $this->isGetter = true;
                                return $this->nodeFactory->createMethodCall($node->var, 'getProperty', ['disableAfterDateTime']);
                            }
                            return $node;
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

        $comment = 'Timed publishing has been conceptually changed and has been extracted into a dedicated package. Please check https://github.com/neos/timeable-node-visibility for further details.';
        if ($visitor->isGetter === false) {
            $comment .= PHP_EOL . '// Use the "SetNodeProperties" command to change property values for "enableAfterDateTime" or "disableAfterDateTime".';
        }

        return self::withTodoComment(
            $comment,
            $node
        );
    }
}
