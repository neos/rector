<?php

declare (strict_types=1);
namespace Neos\Rector\ContentRepository90\Rules;

use Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints;
use Neos\Rector\ContentRepository90\Legacy\LegacyContextStub;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ContentDimensionCombinatorGetAllAllowedCombinationsRector extends AbstractRector
{
    use AllTraits;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector
    )
    {
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return CodeSampleLoader::fromFile('"ContentDimensionCombinator::getAllAllowedCombinations()" will be rewritten.', __CLASS__);
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

        if (!$this->isObjectType($node->var, new ObjectType(\Neos\ContentRepository\Domain\Service\ContentDimensionCombinator::class))) {
            return null;
        }
        if (!$this->isName($node->name, 'getAllAllowedCombinations')) {
            return null;
        }

        $this->nodesToAddCollector->addNodesBeforeNode(
            [
                self::assign('contentRepository', $this->this_contentRepositoryRegistry_get($this->contentRepositoryId_fromString('default'))),
                self::assign('dimensionSpacePoints', $this->contentRepository_getInterDimensionalVariationGraph_getDimensionSpacePoints()),
                self::todoComment('try to directly work with $dimensionSpacePoints, instead of converting them to the legacy dimension format')
            ],
            $node
        );

        return $this->dimensionSpacePoints_toLegacyDimensionArray();
    }
}
