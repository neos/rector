<?php
declare (strict_types=1);

namespace Neos\Rector\ContentRepository90\Rules\Traits;

use Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePoint;
use Neos\ContentRepository\Core\NodeType\NodeTypeName;
use Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId;
use Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar\String_;
use Rector\PhpParser\Node\NodeFactory;

trait ValueObjectTrait
{

    protected NodeFactory $nodeFactory;

    private function contentRepositoryId_fromString(string $contentRepositoryName)
    {
        return $this->nodeFactory->createStaticCall(ContentRepositoryId::class, 'fromString', [new String_($contentRepositoryName)]);
    }

    private function workspaceName_fromString(Expr $expr): Expr
    {
        return $this->nodeFactory->createStaticCall(WorkspaceName::class, 'fromString', [$expr]);
    }

    private function nodeTypeName_fromString(string $param)
    {
        return $this->nodeFactory->createStaticCall(NodeTypeName::class, 'fromString', [new String_($param)]);
    }


    private function dimensionSpacePoint_fromLegacyDimensionArray(Expr $legacyDimensionArray)
    {
        return $this->nodeFactory->createStaticCall(DimensionSpacePoint::class, 'fromLegacyDimensionArray', [$legacyDimensionArray]);
    }
}
