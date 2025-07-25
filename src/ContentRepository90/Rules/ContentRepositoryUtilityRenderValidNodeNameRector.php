<?php

declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use PHPStan\Type\ObjectType;

final class ContentRepositoryUtilityRenderValidNodeNameRector extends AbstractRector
{

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces Utility::renderValidNodeName(...) into NodeName::fromString(...)->value.', [new CodeSample('\Neos\ContentRepository\Utility::renderValidNodeName(\'foo\');', '\Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString(\'foo\')->value;')]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node->name, 'renderValidNodeName')) {
            return null;
        }
        if (!$this->isObjectType($node->class, new ObjectType(\Neos\ContentRepository\Utility::class))) {
            return null;
        }
        $staticCall = $this->rename($node, \Neos\ContentRepository\Core\SharedModel\Node\NodeName::class, 'fromString');
        return $this->nodeFactory->createPropertyFetch($staticCall, 'value');
    }

    private function rename(StaticCall $staticCall, $newClassName, $newMethodName): StaticCall
    {
        $staticCall->name = new Identifier($newMethodName);
        $staticCall->class = new FullyQualified($newClassName);
        return $staticCall;
    }
}
