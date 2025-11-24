<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\Generic\ValueObject\RemoveInjection;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Node;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class RemoveInjectionsRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    use AllTraits;

    /**
     * @var RemoveInjection[]
     */
    private array $injectionsToRemove = [];

    public function __construct(
        protected PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Remove properties marked with a @Flow\Inject annotation and a certain type', __CLASS__, [
            new RemoveInjection(\Foo\Bar\Baz::class)
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Property::class];
    }

    /**
     * @param Node\Stmt\Property $node
     */
    public function refactor(Node $node)
    {
        assert($node instanceof Node\Stmt\Property);
        foreach ($this->injectionsToRemove as $removeInjection) {
            if ($this->isObjectType($node, new ObjectType($removeInjection->objectType))) {
                if (self::hasFlowInjectAttribute($node->attrGroups) || $this->hasFlowInjectDocComment($node)) {
                    return NodeVisitor::REMOVE_NODE;
                }
            }
        }
        return null;
    }

    /**
     * @param Node\AttributeGroup[] $attrGroups
     * @return void
     */
    private static function hasFlowInjectAttribute(array $attrGroups): bool
    {
        foreach ($attrGroups as $group) {
            foreach ($group->attrs as $attr) {
                if ((string)$attr->name === 'Neos\Flow\Annotations\Inject') {
                    return true;
                }
            }
        }
        return false;
    }

    private function hasFlowInjectDocComment(Node\Stmt\Property $node): bool
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if ($phpDocInfo === null) {
            return false;
        }
        return $phpDocInfo->findOneByAnnotationClass('Neos\Flow\Annotations\Inject') !== null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, RemoveInjection::class);
        $this->injectionsToRemove = $configuration;
    }
}
