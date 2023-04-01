<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ToStringToPropertyFetchRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<string, string>
     */
    private array $propertyNamesByType = [];

    public function getRuleDefinition() : RuleDefinition
    {
        return new RuleDefinition('Turns defined code uses of "__toString()" method to specific property fetches.', [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
$someValue = new SomeObject;
$result = (string) $someValue;
$result = $someValue->__toString();
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
$someValue = new SomeObject;
$result = $someValue->someProperty;
$result = $someValue->someProperty;
CODE_SAMPLE
            , ['SomeObject' => 'someProperty'])]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [String_::class, MethodCall::class, Concat::class];
    }
    /**
     * @param String_|MethodCall|Concat $node
     */
    public function refactor(Node $node) : ?Node
    {
        if ($node instanceof String_) {
            return $this->processStringNode($node);
        }
        if ($node instanceof Concat) {
            return $this->processConcatNode($node);
        }
        return $this->processMethodCall($node);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration) : void
    {
        \RectorPrefix202211\Webmozart\Assert\Assert::allString(\array_keys($configuration));
        Assert::allString($configuration);
        /** @var array<string, string> $configuration */
        $this->propertyNamesByType = $configuration;
    }

    private function processStringNode(String_ $string) : ?Node
    {
        foreach ($this->propertyNamesByType as $type => $propertyName) {
            if (!$this->isObjectType($string->expr, new ObjectType($type))) {
                continue;
            }
            return $this->nodeFactory->createPropertyFetch($string->expr, $propertyName);
        }
        return null;
    }

    private function processConcatNode(Concat $concat) : ?Node
    {
        foreach ($this->propertyNamesByType as $type => $propertyName) {
            if ($this->isObjectType($concat->right, new ObjectType($type))) {
                $concat->right = $this->nodeFactory->createPropertyFetch($concat->right, $propertyName);
            }
            if ($this->isObjectType($concat->left, new ObjectType($type))) {
                $concat->left = $this->nodeFactory->createPropertyFetch($concat->left, $propertyName);
            }
        }
        return $concat;
    }

    private function processMethodCall(MethodCall $methodCall) : ?Node
    {
        foreach ($this->propertyNamesByType as $type => $propertyName) {
            if (!$this->isObjectType($methodCall->var, new ObjectType($type))) {
                continue;
            }
            if (!$this->isName($methodCall->name, '__toString')) {
                continue;
            }
            return $this->nodeFactory->createPropertyFetch($methodCall->var, $propertyName);
        }
        return null;
    }
}
