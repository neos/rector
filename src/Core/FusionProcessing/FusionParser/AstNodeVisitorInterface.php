<?php
declare(strict_types=1);

namespace Neos\Rector\Core\FusionProcessing\FusionParser;

/*
 * This file is part of the Neos.Fusion package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\FusionFile;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\StatementList;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\IncludeStatement;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\ObjectStatement;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\Block;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\ObjectPath;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\MetaPathSegment;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\PrototypePathSegment;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\PathSegment;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\ValueAssignment;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\FusionObjectValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\DslExpressionValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\EelExpressionValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\FloatValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\IntValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\BoolValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\NullValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\StringValue;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\ValueCopy;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\AssignedObjectPath;
use Neos\Rector\Core\FusionProcessing\FusionParser\Ast\ValueUnset;

interface AstNodeVisitorInterface
{
    public function visitFusionFile(FusionFile $fusionFile);
    public function visitStatementList(StatementList $statementList);
    public function visitIncludeStatement(IncludeStatement $includeStatement);
    public function visitObjectStatement(ObjectStatement $objectStatement);
    public function visitBlock(Block $block);
    public function visitObjectPath(ObjectPath $objectPath);
    public function visitMetaPathSegment(MetaPathSegment $metaPathSegment);
    public function visitPrototypePathSegment(PrototypePathSegment $prototypePathSegment);
    public function visitPathSegment(PathSegment $pathSegment);
    public function visitValueAssignment(ValueAssignment $valueAssignment);
    public function visitFusionObjectValue(FusionObjectValue $fusionObjectValue);
    public function visitDslExpressionValue(DslExpressionValue $dslExpressionValue);
    public function visitEelExpressionValue(EelExpressionValue $eelExpressionValue);
    public function visitFloatValue(FloatValue $floatValue);
    public function visitIntValue(IntValue $intValue);
    public function visitBoolValue(BoolValue $boolValue);
    public function visitNullValue(NullValue $nullValue);
    public function visitStringValue(StringValue $stringValue);
    public function visitValueCopy(ValueCopy $valueCopy);
    public function visitAssignedObjectPath(AssignedObjectPath $assignedObjectPath);
    public function visitValueUnset(ValueUnset $valueUnset);
}
