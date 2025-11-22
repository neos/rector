<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use Neos\Rector\Generic\ValueObject\RemoveParentClass;
use Neos\Rector\Utility\CodeSampleLoader;
use PhpParser\Comment;
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * This is a re-write of {@see RemoveParentRector}, but working with non existing parent classes as well (and
 * supports adding comments)
 */
final class RemoveParentClassRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{

    /**
     * @var RemoveParentClass[]
     */
    private array $parentClassesToRemove = [];

    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return CodeSampleLoader::fromFile('Remove "extends BLABLA" from classes', __CLASS__, [
            new RemoveParentClass(\Foo\Bar\Baz::class, '// TODO: Neos 9.0 Migration: Stuff')
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Stmt\Class_);
        foreach ($this->parentClassesToRemove as $parentClassToRemove) {
            if ($node->extends === null) {
                continue;
            }
            $objectType = $this->nodeTypeResolver->getType($node->extends);
            assert($objectType instanceof ObjectType);
            if ($objectType->getClassName() !== $parentClassToRemove->parentClassName) {
                continue;
            }

            // remove parent class
            $node->extends = null;
            $node->setAttribute('comments', [
                new Comment($parentClassToRemove->comment)
            ]);
            return $node;
        }
        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, RemoveParentClass::class);
        $this->parentClassesToRemove = $configuration;
    }
}
