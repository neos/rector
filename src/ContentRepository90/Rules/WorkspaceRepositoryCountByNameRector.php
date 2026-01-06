<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr;
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

final class WorkspaceRepositoryCountByNameRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }


    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"WorkspaceRepository::countByName()" will be rewritten.', __CLASS__);
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
                        $node->name->toString() === 'countByName'
                    ) {
                        if ($this->nodeTypeResolver->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Domain\Repository\WorkspaceRepository::class))) {
                            $this->changed = true;

                            return new Node\Expr\Ternary(
                                new Node\Expr\BinaryOp\NotIdentical(
                                    $this->contentRepository_findWorkspaceByName($this->workspaceName_fromString($node->args[0]->value)),
                                    new Expr\ConstFetch(new Node\Name('null'))
                                ),
                                new Node\Scalar\LNumber(1),
                                new Node\Scalar\LNumber(0)
                            );
                        }
                    }
                    return null;
                }
            });

        if (in_array('expr', $node->getSubNodeNames())) {
            /** @var Node\Expr $newExpr */
            $newExpr = $traverser->traverse([$node->expr])[0];
            $node->expr = $newExpr;
        } elseif (in_array('cond', $node->getSubNodeNames())) {
            /** @var Node\Expr $newCond */
            $newCond = $traverser->traverse([$node->cond])[0];
            $node->cond = $newCond;
        } else {
            return null;
        }

        if (!$visitor->changed) {
            return null;
        }

        return [
            self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
            self::withTodoComment(
                'remove ternary operator (...? 1 : 0 ) - unnecessary complexity',
                $node
            ),
        ];
    }
}
