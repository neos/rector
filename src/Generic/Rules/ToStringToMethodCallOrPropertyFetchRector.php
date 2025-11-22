<?php

declare (strict_types=1);

namespace Neos\Rector\Generic\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ToStringToMethodCallOrPropertyFetchRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var array<string, string>
     */
    private array $methodAndPropertyNamesByType = [];

    public function getRuleDefinition() : RuleDefinition
    {
        return new RuleDefinition('Turns defined code uses of "__toString()" method to specific method calls or property fetches.', [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
$someValue = new SomeObject;
$result = (string) $someValue;
$result = $someValue->__toString();
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
$someValue = new SomeObject;
$result = $someValue->getPath();
$result = $someValue->getPath();
CODE_SAMPLE
            , ['SomeObject' => 'getPath()'])]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [String_::class, MethodCall::class, Concat::class, FuncCall::class];
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
        if ($node instanceof FuncCall) {
            return $this->processFuncCallNode($node);
        }
        return $this->processMethodCall($node);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration) : void
    {
        Assert::allString(\array_keys($configuration));
        Assert::allString($configuration);
        /** @var array<string, string> $configuration */
        $this->methodAndPropertyNamesByType = $configuration;
    }

    private function processStringNode(String_ $string) : ?Node
    {
        foreach ($this->methodAndPropertyNamesByType as $type => $methodOrPropertyName) {
            if (!$this->isObjectType($string->expr, new ObjectType($type))) {
                continue;
            }
            return $this->replaceByMethodCallOrPropertyFetch($string->expr, $methodOrPropertyName);
        }
        return null;
    }

    private function processConcatNode(Concat $concat) : ?Node
    {
        foreach ($this->methodAndPropertyNamesByType as $type => $methodOrPropertyName) {
            if ($this->isObjectType($concat->left, new ObjectType($type))) {
                $concat->left = $this->replaceByMethodCallOrPropertyFetch($concat->left, $methodOrPropertyName);
            }
            if ($this->isObjectType($concat->right, new ObjectType($type))) {
                $concat->right = $this->replaceByMethodCallOrPropertyFetch($concat->right, $methodOrPropertyName);
            }
        }
        return $concat;
    }

    private function processFuncCallNode(FuncCall $funcCall) : ?Node
    {
        if (!$this->isName($funcCall, 'sprintf')) {
            return null;
        }
        foreach ($funcCall->args as $index => $arg) {
            if ($index === 0) {
                continue;
            }
            foreach ($this->methodAndPropertyNamesByType as $type => $methodOrPropertyName) {
                if ($this->isObjectType($arg->value, new ObjectType($type))) {
                    $arg->value = $this->replaceByMethodCallOrPropertyFetch($arg->value, $methodOrPropertyName);
                }
            }
        }
        return $funcCall;
    }

    private function processMethodCall(MethodCall $methodCall) : ?Node
    {
        foreach ($this->methodAndPropertyNamesByType as $type => $methodOrPropertyName) {
            if (!$this->isObjectType($methodCall->var, new ObjectType($type))) {
                continue;
            }
            if (!$this->isName($methodCall->name, '__toString')) {
                continue;
            }
            if (str_ends_with($methodOrPropertyName, '()')) {
                $methodCall->name = new Identifier(substr($methodOrPropertyName, 0, -2));
                return $methodCall;
            }
            return $this->nodeFactory->createPropertyFetch($methodCall->var, $methodOrPropertyName);
        }
        return null;
    }

    private function replaceByMethodCallOrPropertyFetch(Expr $expr, string $methodOrPropertyName): Expr
    {
        if (str_ends_with($methodOrPropertyName, '()')) {
            return $this->nodeFactory->createMethodCall($expr, substr($methodOrPropertyName, 0, -2));
        }
        return $this->nodeFactory->createPropertyFetch($expr, $methodOrPropertyName);
    }
}
