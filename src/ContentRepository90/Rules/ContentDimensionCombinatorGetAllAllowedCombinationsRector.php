<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints;
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

final class ContentDimensionCombinatorGetAllAllowedCombinationsRector extends AbstractRector implements DocumentedRuleInterface
{
    use AllTraits;

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('"ContentDimensionCombinator::getAllAllowedCombinations()" will be rewritten.', __CLASS__);
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
                        $node->name->toString() === 'getAllAllowedCombinations'
                    ) {
                        if ($this->nodeTypeResolver->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Domain\Service\ContentDimensionCombinator::class))) {
                            $this->changed = true;
                            $this->nodeVar = $node->var;

                            return $this->dimensionSpacePoints_toLegacyDimensionArray();
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
            self::assign('dimensionSpacePoints', $this->contentRepository_getVariationGraph_getDimensionSpacePoints()),
            self::withTodoComment(
                'try to directly work with $dimensionSpacePoints, instead of converting them to the legacy dimension format',
                $node
            ),
        ];
    }
}
