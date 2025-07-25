<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\Utility\CodeSampleLoader;
use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\NodeManipulator\ClassInsertManipulator;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Rector\Contract\Rector\ConfigurableRectorInterface;

// Modelled after https://raw.githubusercontent.com/sabbelasichon/typo3-rector/main/src/Rector/v10/v2/InjectEnvironmentServiceIfNeededInResponseRector.php
final class InjectServiceIfNeededRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var AddInjection[]
     */
    private array $injectionsToAdd;

    public function __construct(
        private readonly ClassInsertManipulator $classInsertManipulator,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('add injection for $contentRepositoryRegistry if in use.', __CLASS__, [
            new AddInjection('contentRepositoryRegistry', ContentRepositoryRegistry::class)
        ]);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, AddInjection::class);
        $this->injectionsToAdd = $configuration;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $modified = false;
        foreach ($this->injectionsToAdd as $addInjection) {
            if (!$this->isObjectInUse($node, $addInjection)) {
                continue;
            }

            // already added
            if ($node->getProperty($addInjection->memberName)) {
                continue;
            }

            $property = $this->createInjectedProperty($addInjection->memberName, new FullyQualified($addInjection->objectType));
            $this->classInsertManipulator->addAsFirstMethod($node, $property);
            $modified = true;
        }

        return $modified ? $node : null;
    }

    private function isObjectInUse(Class_ $class, AddInjection $addInjection): bool
    {
        $inUse = false;
        $this->traverseNodesWithCallable($class->stmts, function (Node $node) use (
            &$inUse,
            $addInjection
        ): ?PropertyFetch {

            if (!$node instanceof PropertyFetch) {
                return null;
            }

            // E.g. $this->contentRepositoryRegistry found somewhere in class
            if ($this->isName($node->name, $addInjection->memberName)) {
                if ($node->var instanceof Node\Expr\Variable && $node->var->name === 'this') {
                    $inUse = true;
                }
            }

            return $node;
        });
        return $inUse;
    }

    private function createInjectedProperty(string $propertyName, FullyQualified $type): Property
    {
        return new Property(Class_::MODIFIER_PROTECTED, [
            new Node\Stmt\PropertyProperty($propertyName)
        ], [], $type, [
            new Node\AttributeGroup([
                new Node\Attribute(
                    new Node\Name('\Neos\Flow\Annotations\Inject')
                )
            ])
        ]);
    }
}
