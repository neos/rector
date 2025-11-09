<?php
declare (strict_types=1);

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use Neos\Neos\Domain\NodeLabel\NodeLabelGeneratorInterface;
use Neos\Neos\Domain\Service\RenderingModeService;
use Neos\Neos\Domain\Service\WorkspacePublishingService;
use Neos\Neos\Domain\Service\WorkspaceService;
use Neos\Rector\ContentRepository90\Legacy\LegacyContextStub;
use Neos\ContentRepository\Domain\Model\Node as NodeLegacyStub;
use Neos\Rector\ContentRepository90\Rules\ContentDimensionCombinatorGetAllAllowedCombinationsRector;
use Neos\Rector\ContentRepository90\Rules\ContentRepositoryUtilityRenderValidNodeNameRector;
use Neos\Rector\ContentRepository90\Rules\ContextFactoryToLegacyContextStubRector;
use Neos\Rector\ContentRepository90\Rules\ContextGetCurrentRenderingModeRector;
use Neos\Rector\ContentRepository90\Rules\ContextGetFirstLevelNodeCacheRector;
use Neos\Rector\ContentRepository90\Rules\ContextGetRootNodeRector;
use Neos\Rector\ContentRepository90\Rules\ContextIsInBackendRector;
use Neos\Rector\ContentRepository90\Rules\ContextIsLiveRector;
use Neos\Rector\ContentRepository90\Rules\NodeFactoryResetRector;
use Neos\Rector\ContentRepository90\Rules\NodeFindParentNodeRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetChildNodesRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceNameRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetContextPathRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetDepthRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetDimensionsRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetHiddenBeforeAfterDateTimeRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetIdentifierRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetNodeTypeGetNameRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetNodeTypeRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetParentRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetPathRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetPropertyNamesRector;
use Neos\Rector\ContentRepository90\Rules\NodeIsAutoCreatedRector;
use Neos\Rector\ContentRepository90\Rules\NodeIsHiddenInIndexRector;
use Neos\Rector\ContentRepository90\Rules\NodeIsHiddenRector;
use Neos\Rector\ContentRepository90\Rules\NodeLabelGeneratorRector;
use Neos\Rector\ContentRepository90\Rules\NodeSearchServiceRector;
use Neos\Rector\ContentRepository90\Rules\NodeTypeAllowsGrandchildNodeTypeRector;
use Neos\Rector\ContentRepository90\Rules\NodeTypeGetAutoCreatedChildNodesRector;
use Neos\Rector\ContentRepository90\Rules\NodeTypeGetNameRector;
use Neos\Rector\ContentRepository90\Rules\NodeTypeGetTypeOfAutoCreatedChildNodeRector;
use Neos\Rector\ContentRepository90\Rules\NodeTypeManagerAccessRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceGetBaseWorkspaceRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceGetBaseWorkspacesRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceGetDescriptionRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceGetNameRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceGetTitleRector;
use Neos\Rector\ContentRepository90\Rules\WorkspacePublishNodeRector;
use Neos\Rector\ContentRepository90\Rules\WorkspacePublishRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryCountByNameRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryFindByBaseWorkspaceRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryFindByIdentifierRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceSetDescriptionRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceSetTitleRector;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\Rules\MethodCallToWarningCommentRector;
use Neos\Rector\Generic\Rules\ObjectInstantiationToWarningCommentRector;
use Neos\Rector\Generic\Rules\RemoveInjectionsRector;
use Neos\Rector\Generic\Rules\SignalSlotToWarningCommentRector;
use Neos\Rector\Generic\Rules\ToStringToMethodCallOrPropertyFetchRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Neos\Rector\Generic\ValueObject\MethodCallToWarningComment;
use Neos\Rector\Generic\ValueObject\ObjectInstantiationToWarningComment;
use Neos\Rector\Generic\ValueObject\RemoveInjection;
use Neos\Rector\Generic\ValueObject\RemoveParentClass;
use Neos\Rector\Generic\ValueObject\SignalSlotToWarningComment;
use Rector\Config\RectorConfig;
use Rector\Removing\Rector\Class_\RemoveTraitUseRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Neos\Rector\Generic\Rules\MethodCallToPropertyFetchRector;
use Neos\Rector\Generic\ValueObject\MethodCallToPropertyFetch;


$rectorConfig = RectorConfig::configure()
    ->withoutParallel()
    ->withAutoloadPaths(
        [__DIR__ . '/../../src/ContentRepository90/Legacy']
    );


/** @var MethodCallToPropertyFetch[] $methodCallToPropertyFetches */
$methodCallToPropertyFetches = [];

/** @var MethodCallToWarningComment[] $methodCallToWarningComments */
$methodCallToWarningComments = [];


/**
 * Neos\ContentRepository\Domain\Model\NodeInterface
 */
// setName
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setName', '!! Node::setName() is not supported by the new CR. Use the "ChangeNodeAggregateName" command to change the node name.');
// getName
$methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(NodeLegacyStub::class, 'getName', 'nodeName');
$rectorConfig->withRules([NodeLabelGeneratorRector::class]);
// setProperty
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setProperty', '!! Node::setProperty() is not supported by the new CR. Use the "SetNodeProperties" command to change property values.');
// hasProperty -> compatible with ES CR Node (nothing to do)
// getProperty -> compatible with ES CR Node (nothing to do)
// removeProperty
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'removeProperty', '!! Node::removeProperty() is not supported by the new CR. Use the "SetNodeProperties" command to remove a property values.');
// getProperties -> PropertyCollectionInterface
$methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(NodeLegacyStub::class, 'getProperties', 'properties');
// getPropertyNames
$rectorConfig->withRules([NodeGetPropertyNamesRector::class]);
// setContentObject -> DEPRECATED / NON-FUNCTIONAL
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setContentObject', '!! Node::setContentObject() is not supported by the new CR. Referencing objects can be done by storing them in Node::properties (and the serialization/deserialization is extensible).');
// getContentObject -> DEPRECATED / NON-FUNCTIONAL
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getContentObject', '!! Node::getContentObject() is not supported by the new CR. Referencing objects can be done by storing them in Node::properties (and the serialization/deserialization is extensible).');
// unsetContentObject -> DEPRECATED / NON-FUNCTIONAL
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'unsetContentObject', '!! Node::unsetContentObject() is not supported by the new CR. Referencing objects can be done by storing them in Node::properties (and the serialization/deserialization is extensible).');
// setNodeType
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setNodeType', '!! Node::setNodeType() is not supported by the new CR. Use the "ChangeNodeAggregateType" command to change nodetype.');
// getNodeType: NodeType
// PHP: shortcut to Node->nodeTypeName->value
$rectorConfig->withRules([NodeGetNodeTypeGetNameRector::class]);
$rectorConfig->withRules([NodeGetNodeTypeRector::class]);
// setHidden
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setHidden', '!! Node::setHidden() is not supported by the new CR. Use the "EnableNodeAggregate" or "DisableNodeAggregate" command to change the visibility of the node.');
// isHidden
$rectorConfig->withRules([NodeIsHiddenRector::class]);
// setHiddenBeforeDateTime
$rectorConfig->withRules([NodeGetHiddenBeforeAfterDateTimeRector::class]);
// getHiddenBeforeDateTime
// PHP: Covered by NodeGetHiddenBeforeAfterDateTimeRector
// setHiddenAfterDateTime
// PHP: Covered by NodeGetHiddenBeforeAfterDateTimeRector
// getHiddenAfterDateTime
// PHP: Covered by NodeGetHiddenBeforeAfterDateTimeRector
// setHiddenInIndex
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setHiddenInIndex', '!! Node::setHiddenInIndex() is not supported by the new CR. Use the "SetNodeProperties" command to change the property value for "hiddenInMenu".');
// isHiddenInIndex
$rectorConfig->withRules([NodeIsHiddenInIndexRector::class]);
// setAccessRoles
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setAccessRoles', '!! Node::setAccessRoles() is not supported by the new CR.');
// getAccessRoles
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getAccessRoles', '!! Node::getAccessRoles() is not supported by the new CR.');
// getPath
$rectorConfig->withRules([NodeGetPathRector::class]);
// getContextPath
$rectorConfig->withRules([NodeGetContextPathRector::class]);
// getDepth
$rectorConfig->withRules([NodeGetDepthRector::class]);
// setWorkspace -> internal
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setWorkspace', '!! Node::setWorkspace() was always internal, and the workspace system has been fundamentally changed with the new CR. Try to rewrite your code around Content Streams.');
// getWorkspace
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getWorkspace', '!! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.');
// getIdentifier
$rectorConfig->withRules([NodeGetIdentifierRector::class]);
// setIndex -> internal
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setIndex', '!! Node::setIndex() was always internal. To reorder nodes, use the "MoveNodeAggregate" command');
// getIndex
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getIndex', '!! Node::getIndex() is not supported. You can fetch all siblings and inspect the ordering');
// getParent -> Node
$rectorConfig->withRules([NodeGetParentRector::class]);
// getParentPath - deprecated
// createNode
// createSingleNode -> internal
// createNodeFromTemplate
// getNode(relative path) - deprecated
// getPrimaryChildNode() - deprecated
// getChildNodes($nodeTypeFilter, $limit, $offset) - deprecated
$rectorConfig->withRules([NodeGetChildNodesRector::class]);
// hasChildNodes($nodeTypeFilter) - deprecated
// remove()
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'remove', '!! Node::remove() is not supported by the new CR. Use the "RemoveNodeAggregate" command to remove a node.');
// setRemoved()
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setRemoved', '!! Node::setRemoved() is not supported by the new CR. Use the "RemoveNodeAggregate" command to remove a node.');
// isRemoved()
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'isRemoved', '!! Node::isRemoved() - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.');
// isVisible()
// isAccessible()
// hasAccessRestrictions()
// isNodeTypeAllowedAsChildNode()
// moveBefore()
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'moveBefore', '!! Node::moveBefore() is not supported by the new CR. Use the "MoveNodeAggregate" command to move a node.');
// moveAfter()
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'moveAfter', '!! Node::moveAfter() is not supported by the new CR. Use the "MoveNodeAggregate" command to move a node.');
// moveInto()
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'moveInto', '!! Node::moveInto() is not supported by the new CR. Use the "MoveNodeAggregate" command to move a node.');
// copyBefore()
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'copyBefore', '!! Node::copyBefore() is not supported by the new CR. Use the "NodeDuplicationService::copyNodesRecursively" to copy a node.');
// copyAfter()
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'copyAfter', '!! Node::copyAfter() is not supported by the new CR. Use the "NodeDuplicationService::copyNodesRecursively" to copy a node.');
// copyInto()
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'copyInto', '!! Node::copyInto() is not supported by the new CR. Use the "NodeDuplicationService::copyNodesRecursively" to copy a node.');
// getNodeData()
$methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getNodeData', '!! Node::getNodeData() - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.');
// getContext()
// getContext()->getWorkspace()
$rectorConfig->withRules([NodeGetContextGetWorkspaceRector::class]);
// getContext()->getWorkspaceName()
$rectorConfig->withRules([NodeGetContextGetWorkspaceNameRector::class]);
// getDimensions()
$rectorConfig->withRules([NodeGetDimensionsRector::class]);
// createVariantForContext()
// isAutoCreated()
$rectorConfig->withRules([NodeIsAutoCreatedRector::class]);

// getOtherNodeVariants()


/**
 * Neos\ContentRepository\Domain\Projection\Content\NodeInterface
 */
// isRoot()
// isTethered()
// getContentStreamIdentifier() -> threw exception in <= Neos 8.0 - so nobody could have used this
// getNodeAggregateIdentifier()
$methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(NodeLegacyStub::class, 'getNodeAggregateIdentifier', 'aggregateId');
// getNodeTypeName()
$methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(NodeLegacyStub::class, 'getNodeTypeName', 'nodeTypeName');
// getNodeType() ** (included/compatible in old NodeInterface)
// getNodeName()
$methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(NodeLegacyStub::class, 'getNodeName', 'nodeName');
// getOriginDimensionSpacePoint() -> threw exception in <= Neos 8.0 - so nobody could have used this
// getProperties() ** (included/compatible in old NodeInterface)
// getProperty() ** (included/compatible in old NodeInterface)
// hasProperty() ** (included/compatible in old NodeInterface)
// getLabel() ** (included/compatible in old NodeInterface)

/**
 * Neos\ContentRepository\Core\NodeType\NodeType
 */
// getName()
$rectorConfig->withRules([NodeTypeGetNameRector::class]);
// getAutoCreatedChildNodes
$rectorConfig->withRules([NodeTypeGetAutoCreatedChildNodesRector::class]);
// hasAutoCreatedChildNode
$rectorConfig->withConfiguredRule(RenameMethodRector::class, [new MethodCallRename(
    NodeType::class,
    'hasAutoCreatedChildNode',
    'hasTetheredNode'
)]);
// getTypeOfAutoCreatedChildNode
$rectorConfig->withRules([NodeTypeGetTypeOfAutoCreatedChildNodeRector::class]);
// allowsGrandchildNodeType
$rectorConfig->withRules([NodeTypeAllowsGrandchildNodeTypeRector::class]);


/**
 * Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface
 */
// getDimensionSpacePoint() -> threw exception in <= Neos 8.0 - so nobody could have used this
// findParentNode() -> TraversableNodeInterface
$rectorConfig->withRules([NodeFindParentNodeRector::class]);
// findNodePath() -> NodePath
// TODO: PHP
// findNamedChildNode(NodeName $nodeName): TraversableNodeInterface;
// TODO: PHP
// findChildNodes(NodeTypeConstraints $nodeTypeConstraints = null, int $limit = null, int $offset = null): TraversableNodes;
// TODO: PHP
// countChildNodes(NodeTypeConstraints $nodeTypeConstraints = null): int;
// TODO: PHP
// findReferencedNodes(): TraversableNodes;
// TODO: PHP
// findNamedReferencedNodes(PropertyName $edgeName): TraversableNodes;
// TODO: PHP
// findReferencingNodes() -> threw exception in <= Neos 8.0 - so nobody could have used this
// findNamedReferencingNodes() -> threw exception in <= Neos 8.0 - so nobody could have used this


/**
 * Context
 */
$rectorConfig->withRules([ContextFactoryToLegacyContextStubRector::class]);
// Context::getWorkspaceName()
// TODO: PHP
// Context::getRootNode()
$rectorConfig->withRules([ContextGetRootNodeRector::class]);
// Context::getNode()
// TODO: PHP
// Context::getNodeByIdentifier()
// TODO: PHP
// Context::getNodeVariantsByIdentifier()
// TODO: PHP
// Context::getNodesOnPath()
// TODO: PHP
// Context::adoptNode()
// TODO: PHP
// Context::getFirstLevelNodeCache()
$rectorConfig->withRules([ContextGetFirstLevelNodeCacheRector::class]);
// getCurrentDateTime(): DateTime|DateTimeInterface
// TODO: PHP
// getDimensions(): array
// TODO: PHP
// getNodeByIdentifier(identifier: string): NodeInterface|null
// TODO: PHP
// getProperties(): array
// TODO: PHP
// getTargetDimensions(): array
// TODO: PHP
// getTargetDimensionValues(): array
// TODO: PHP
// getWorkspace([createWorkspaceIfNecessary: bool = true]): Workspace
// TODO: PHP
// isInaccessibleContentShown(): bool
// TODO: PHP
// isInvisibleContentShown(): bool
// TODO: PHP
// isRemovedContentShown(): bool
// TODO: PHP
// validateWorkspace(workspace: Workspace): void
// TODO: PHP


/**
 * ContentContext
 */
// ContentContext::getCurrentSite
$methodCallToWarningComments[] = new MethodCallToWarningComment(LegacyContextStub::class, 'getCurrentSite', '!! ContentContext::getCurrentSite() is removed in Neos 9.0.');
// ContentContext::getCurrentDomain
$methodCallToWarningComments[] = new MethodCallToWarningComment(LegacyContextStub::class, 'getCurrentDomain', '!! ContentContext::getCurrentDomain() is removed in Neos 9.0.');
// ContentContext::getCurrentSiteNode
$methodCallToWarningComments[] = new MethodCallToWarningComment(LegacyContextStub::class, 'getCurrentSiteNode', '!! ContentContext::getCurrentSiteNode() is removed in Neos 9.0. Use Subgraph and traverse up to "Neos.Neos:Site" node.');
// ContentContext::isLive -> renderingMode.isLive
$rectorConfig->withRules([ContextIsLiveRector::class]);
// ContentContext::isInBackend -> renderingMode.inBackend
$rectorConfig->withRules([ContextIsInBackendRector::class]);
// ContentContext::getCurrentRenderingMode... -> renderingMode...
$rectorConfig->withRules([ContextGetCurrentRenderingModeRector::class]);

/**
 * CreateContentContextTrait
 */
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\Neos\Controller\CreateContentContextTrait::class, 'createContentContext', '!! CreateContentContextTrait::createContentContext() is removed in Neos 9.0.');
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\Neos\Controller\CreateContentContextTrait::class, 'createContextMatchingNodeData', '!! CreateContentContextTrait::createContextMatchingNodeData() is removed in Neos 9.0.');

/**
 * CacheLifetimeOperation
 */


/**
 * ContentDimensionCombinator
 */
// ContentDimensionCombinator::getAllAllowedCombinations
$rectorConfig->withRules([ContentDimensionCombinatorGetAllAllowedCombinationsRector::class]);

/**
 * Neos\Neos\Domain\Service\NodeSearchService
 */
$rectorConfig->withRules([NodeSearchServiceRector::class]);


/**
 * Neos\ContentRepository\Domain\Factory\NodeFactory
 */
// TODO: What other methods?
// NodeFactory::reset
$rectorConfig->withRules([NodeFactoryResetRector::class]);

/**
 * Neos\ContentRepository\Domain\Repository\WorkspaceRepository
 */
// countByName(workspace): int
$rectorConfig->withRules([WorkspaceRepositoryCountByNameRector::class]);
// findByBaseWorkspace(baseWorkspace): QueryResultInterface
$rectorConfig->withRules([WorkspaceRepositoryFindByBaseWorkspaceRector::class]);
// findByIdentifier(baseWorkspace): Workspace
$rectorConfig->withRules([WorkspaceRepositoryFindByIdentifierRector::class]);

/**
 * Neos\ContentRepository\Domain\Model\Workspace
 */
$rectorConfig->withRules([WorkspaceGetNameRector::class]);

/**
 * NodeTemplate
 */
$nodeTemplateWarningMessage = '!! NodeTemplate::%2$s is removed in Neos 9.0. Use the "CreateNodeAggregateWithNode" command to create new nodes or "CreateNodeVariant" command to create variants of an existing node in other dimensions.';
// getIdentifier(): string
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeTemplate::class, 'getIdentifier', $nodeTemplateWarningMessage);
// getName(): string
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeTemplate::class, 'getName', $nodeTemplateWarningMessage);
// getWorkspace(): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeTemplate::class, 'getWorkspace', $nodeTemplateWarningMessage);
// setIdentifier(identifier: string): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeTemplate::class, 'setIdentifier', $nodeTemplateWarningMessage);
// setName(newName: string): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeTemplate::class, 'setName', $nodeTemplateWarningMessage);

/**
 * ObjectInstantiationToWarningComment
 */
$rectorConfig->withConfiguredRule(ObjectInstantiationToWarningCommentRector::class, [
        new ObjectInstantiationToWarningComment(\Neos\ContentRepository\Domain\Model\NodeTemplate::class, '!! NodeTemplate is removed in Neos 9.0. Use the "CreateNodeAggregateWithNode" command to create new nodes or "CreateNodeVariant" command to create variants of an existing node in other dimensions.'),
        new ObjectInstantiationToWarningComment(\Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints::class, '!! NodeTypeConstraints is removed in Neos 9.0. Please use the proper filter in subgraph finders e.g. "FindChildNodesFilter" for ContentSubgraphInterface::findChildNodes().')
    ]
);

/**
 * Neos\ContentRepository\Domain\Service\NodeTypeManager
 */
$rectorConfig->withRules([NodeTypeManagerAccessRector::class]);
// createNodeType(nodeTypeName: string): NodeType
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Service\NodeTypeManager::class, 'createNodeType', '!! NodeTypeManager::createNodeType() was never implemented and is removed in Neos 9.0.');
// getNodeType(nodeTypeName: string): NodeType
// --> Compatible with 9.0
// getNodeTypes([includeAbstractNodeTypes: bool = true]): NodeType[]
// --> Compatible with 9.0
// getSubNodeTypes(superTypeName: string, [includeAbstractNodeTypes: bool = true]): NodeType[]
// --> Compatible with 9.0
// hasNodeType(nodeTypeName: string): bool
// --> Compatible with 9.0
// overrideNodeTypes(completeNodeTypeConfiguration: array): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Service\NodeTypeManager::class, 'overrideNodeTypes', '!! NodeTypeManager::createNodeType() was never meant to be used outside of testing and is removed in Neos 9.0.');

/**
 * NodeData
 */
$nodeDataWarningMessage = '!! NodeData::%2$s is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.';
// createNodeData(name: string, [nodeType: NodeType|null = null], [identifier: null|string = null], [workspace: Workspace|null = null], [dimensions: array|null = null]): NodeData
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'createNodeData', $nodeDataWarningMessage);
// createNodeDataFromTemplate(nodeTemplate: NodeTemplate, [nodeName: null|string = null], [workspace: Workspace|null = null], [dimensions: array|null = null]): NodeData
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'createNodeDataFromTemplate', $nodeDataWarningMessage);
// createShadow(path: string): NodeData
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'createShadow', $nodeDataWarningMessage);
// createSingleNodeData(name: string, [nodeType: NodeType|null = null], [identifier: null|string = null], [workspace: Workspace|null = null], [dimensions: array|null = null]): NodeData
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'createSingleNodeData', $nodeDataWarningMessage);
// getContextPath(): string
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getContextPath', $nodeDataWarningMessage);
// getDepth(): int
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getDepth', $nodeDataWarningMessage);
// getDimensions(): NodeDimension[]
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getDimensions', $nodeDataWarningMessage);
// getDimensionsHash(): string
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getDimensionsHash', $nodeDataWarningMessage);
// getDimensionValues(): array
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getDimensionValues', $nodeDataWarningMessage);
// getIdentifier(): string
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getIdentifier', $nodeDataWarningMessage);
// getIndex(): int
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getIndex', $nodeDataWarningMessage);
// getMovedTo(): NodeData
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getMovedTo', $nodeDataWarningMessage);
// getName(): string
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getName', $nodeDataWarningMessage);
// getNumberOfChildNodes(nodeTypeFilter: string, workspace: Workspace, dimensions: array): int
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getNumberOfChildNodes', $nodeDataWarningMessage);
// getParent(): NodeData|null
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getParent', $nodeDataWarningMessage);
// getParentPath(): string
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getParentPath', $nodeDataWarningMessage);
// getPath(): string
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getPath', $nodeDataWarningMessage);
// getWorkspace(): Workspace
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'getWorkspace', $nodeDataWarningMessage);
// hasAccessRestrictions(): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'hasAccessRestrictions', $nodeDataWarningMessage);
// isAccessible(): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'isAccessible', $nodeDataWarningMessage);
// isInternal(): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'isInternal', $nodeDataWarningMessage);
// isRemoved(): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'isRemoved', $nodeDataWarningMessage);
// isVisible(): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'isVisible', $nodeDataWarningMessage);
// matchesWorkspaceAndDimensions(workspace: Workspace, [dimensions: array|null = null]): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'matchesWorkspaceAndDimensions', $nodeDataWarningMessage);
// move(targetPath: string, targetWorkspace: Workspace): NodeData|null
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'targetPath', $nodeDataWarningMessage);
// remove(): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'remove', $nodeDataWarningMessage);
// setDimensions(dimensionsToBeSet: array): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'setDimensions', $nodeDataWarningMessage);
// setIdentifier(identifier: string): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'setIdentifier', $nodeDataWarningMessage);
// setIndex(index: int): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'setIndex', $nodeDataWarningMessage);
// setMovedTo([nodeData: NodeData|null = null]): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'setMovedTo', $nodeDataWarningMessage);
// setPath(path: string, [recursive: bool = true]): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'setPath', $nodeDataWarningMessage);
// setRemoved(removed: bool): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'setRemoved', $nodeDataWarningMessage);
// setWorkspace([workspace: Workspace|null = null]): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'setWorkspace', $nodeDataWarningMessage);
// similarize(sourceNode: AbstractNodeData, [isCopy: bool = false]): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'similarize', $nodeDataWarningMessage);

/**
 * \Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory
 */
// parseFilterStrnig(serializedFilters: string): NodeTypeConstraints
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory::class, 'parseFilterString', '!! The "NodeTypeConstraintFactory" has been removed in Neos 9. Please use the proper filter in subgraph finders e.g. "FindChildNodesFilter" for ContentSubgraphInterface::findChildNodes().');

/**
 * \Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints
 */
$nodeTypeConstraintsWarning = '!! NodeTypeConstraints has been removed in Neos 9. Please use the proper filter in subgraph finders e.g. "FindChildNodesFilter" for ContentSubgraphInterface::findChildNodes().';
// asLegacyNodeTypeFilterString(): string
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints::class, 'asLegacyNodeTypeFilterString', $nodeTypeConstraintsWarning);
// getExplicitlyAllowedNodeTypeNames(): NodeTypeName[]
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints::class, 'getExplicitlyAllowedNodeTypeNames', $nodeTypeConstraintsWarning);
// getExplicitlyDisallowedNodeTypeNames(): NodeTypeName[]
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints::class, 'getExplicitlyDisallowedNodeTypeNames', $nodeTypeConstraintsWarning);
// isWildcardAllowed(): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints::class, 'isWildcardAllowed', $nodeTypeConstraintsWarning);
// matches(nodeTypeName: NodeTypeName): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints::class, 'nodeTypeName', $nodeTypeConstraintsWarning);
// withExplicitlyDisallowedNodeType(nodeTypeName: NodeTypeName): NodeTypeConstraints
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints::class, 'withExplicitlyDisallowedNodeType', $nodeTypeConstraintsWarning);

/**
 * Signals and Slots
 * https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots
 */
$signalsAndSlotsToComment = [];
// Neos\ContentRepository\Domain\Service\PublishingService
// - nodePublished
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Service\PublishingService::class, 'nodePublished', 'The signal "nodePublished" on "PublishingService" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - nodeDiscarded
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Service\PublishingService::class, 'nodeDiscarded', 'The signal "nodeDiscarded" on "PublishingService" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// Neos\Neos\Service\PublishingService
// - nodePublished
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\Neos\Service\PublishingService::class, 'nodePublished', 'The signal "nodePublished" on "PublishingService" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - nodeDiscarded
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\Neos\Service\PublishingService::class, 'nodeDiscarded', 'The signal "nodeDiscarded" on "PublishingService" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// Neos\ContentRepository\Domain\Service\Context
// - beforeAdoptNode
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Service\Context::class, 'beforeAdoptNode', 'The signal "beforeAdoptNode" on "Context" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - afterAdoptNode
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Service\Context::class, 'afterAdoptNode', 'The signal "afterAdoptNode" on "Context" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// Neos\ContentRepository\Domain\Repository\NodeDataRepository
// - repositoryObjectsPersisted
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Repository\NodeDataRepository::class, 'repositoryObjectsPersisted', 'The signal "repositoryObjectsPersisted" on "NodeDataRepository" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// Neos\ContentRepository\Domain\Model\Workspace
// - baseWorkspaceChanged
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'baseWorkspaceChanged', 'The signal "baseWorkspaceChanged" on "Workspace" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - beforeNodePublishing
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'beforeNodePublishing', 'The signal "beforeNodePublishing" on "Workspace" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - afterNodePublishing
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'afterNodePublishing', 'The signal "afterNodePublishing" on "Workspace" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// Neos\ContentRepository\Domain\Model\NodeData
// - nodePathChanged
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\NodeData::class, 'nodePathChanged', 'The signal "nodePathChanged" on "NodeData" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// Neos\ContentRepository\Domain\Model\Node
// - beforeNodeMove
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'beforeNodeMove', 'The signal "beforeNodeMove" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - afterNodeMove
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'afterNodeMove', 'The signal "afterNodeMove" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - beforeNodeCopy
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'beforeNodeCopy', 'The signal "beforeNodeCopy" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - afterNodeCopy
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'afterNodeCopy', 'The signal "afterNodeCopy" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - nodePathChanged
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'nodePathChanged', 'The signal "nodePathChanged" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - beforeNodeCreate
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'beforeNodeCreate', 'The signal "beforeNodeCreate" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - afterNodeCreate
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'afterNodeCreate', 'The signal "afterNodeCreate" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - nodeAdded
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'nodeAdded', 'The signal "nodeAdded" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - nodeUpdated
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'nodeUpdated', 'The signal "nodeUpdated" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - nodeRemoved
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'nodeRemoved', 'The signal "nodeRemoved" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - beforeNodePropertyChange
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'beforeNodePropertyChange', 'The signal "beforeNodePropertyChange" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');
// - nodePropertyChanged
$signalsAndSlotsToComment[] = new SignalSlotToWarningComment(\Neos\ContentRepository\Domain\Model\Node::class, 'nodePropertyChanged', 'The signal "nodePropertyChanged" on "Node" has been removed. Please check https://docs.neos.io/api/upgrade-instructions/9/signals-and-slots for further information, how to replace a signal.');

$rectorConfig->withConfiguredRule(SignalSlotToWarningCommentRector::class, $signalsAndSlotsToComment);


$rectorConfig->withRules([ContentRepositoryUtilityRenderValidNodeNameRector::class]);

/**
 * \Neos\ContentRepository\Domain\Model\Workspace
 */
// getBaseWorkspace(): Workspace|null
$rectorConfig->withRules([WorkspaceGetBaseWorkspaceRector::class]);
// getBaseWorkspaces(): Workspace[]
$rectorConfig->withRules([WorkspaceGetBaseWorkspacesRector::class]);
// getDescription(): null|string
$rectorConfig->withRules([WorkspaceGetDescriptionRector::class]);
// getName(): string
// ->name
// getNodeCount(): int
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'getNodeCount', '!! Workspace::getNodeCount() has been removed in Neos 9.0 without a replacement.');
// getOwner(): UserInterface|null
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'getOwner', '!! Workspace::getOwner() has been removed in Neos 9.0. Use WorkspaceService::getWorkspaceMetadata()->ownerUserId to get the userId of the owner.');
// getRootNodeData(): NodeData
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'getRootNodeData', '!! Workspace::getRootNodeData() has been removed in Neos 9.0 without a replacement.');
// getTitle(): string
$rectorConfig->withRules([WorkspaceGetTitleRector::class]);
// meta->setWorkspaceTitle
// isInternalWorkspace(): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'isInternalWorkspace', '!! Workspace::isInternalWorkspace() has been removed in Neos 9.0. Please use the new Workspace permission api instead. See ContentRepositoryAuthorizationService::getWorkspacePermissions()');
// isPersonalWorkspace(): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'isPersonalWorkspace', '!! Workspace::isPersonalWorkspace() has been removed in Neos 9.0. Please use the new Workspace permission api instead. See ContentRepositoryAuthorizationService::getWorkspacePermissions()');
// isPrivateWorkspace(): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'isPrivateWorkspace', '!! Workspace::isPrivateWorkspace() has been removed in Neos 9.0. Please use the new Workspace permission api instead. See ContentRepositoryAuthorizationService::getWorkspacePermissions()');
// isPublicWorkspace(): bool
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'isPublicWorkspace', '!! Workspace::isPublicWorkspace() has been removed in Neos 9.0. Please use the new Workspace permission api instead. See ContentRepositoryAuthorizationService::getWorkspacePermissions()');
// publish(targetWorkspace: Workspace): void
$rectorConfig->withRules([WorkspacePublishRector::class]);
// publishNode(nodeToPublish: NodeInterface, targetWorkspace: Workspace): void
$rectorConfig->withRules([WorkspacePublishNodeRector::class]);
// publishNodes(nodes: NodeInterface[], targetWorkspace: Workspace): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'publishNodes', '!! Workspace::publishNodes() has been removed in Neos 9.0. Use the \Neos\Neos\Domain\Service\WorkspacePublishingService to publish a workspace or changes in a document.');
// setBaseWorkspace(baseWorkspace: Workspace): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'setBaseWorkspace', '!! Workspace::setBaseWorkspace() is not supported by the new CR. Use the "ChangeBaseWorkspace" command to change the baseWorkspace of a workspace.');
// setDescription(description: string): void
$rectorConfig->withRules([WorkspaceSetDescriptionRector::class]);
// setOwner(user: UserInterface|null|string): void
$methodCallToWarningComments[] = new MethodCallToWarningComment(\Neos\ContentRepository\Domain\Model\Workspace::class, 'setOwner', '!! Workspace::setOwner() has been removed in Neos 9.0. You can set the owner of a workspace during creation WorkspaceService::createPersonalWorkspace().');
// setTitle(title: string): void
$rectorConfig->withRules([WorkspaceSetTitleRector::class]);

$rectorConfig->withConfiguredRule(RenameClassRector::class, [
    \Neos\ContentRepository\Domain\Model\Node::class => Node::class,
    \Neos\ContentRepository\Domain\Model\NodeInterface::class => Node::class,
    \Neos\ContentRepository\Domain\Projection\Content\NodeInterface::class => Node::class,
    \Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface::class => Node::class,

    \Neos\ContentRepository\Domain\Projection\Content\TraversableNodes::class => \Neos\ContentRepository\Core\Projection\ContentGraph\Nodes::class,

    \Neos\ContentRepository\Domain\Service\Context::class => LegacyContextStub::class,
    \Neos\Neos\Domain\Service\ContentContext::class => LegacyContextStub::class,

    \Neos\ContentRepository\Domain\Model\NodeType::class => \Neos\ContentRepository\Core\NodeType\NodeType::class,
    \Neos\ContentRepository\Domain\Service\NodeTypeManager::class => \Neos\ContentRepository\Core\NodeType\NodeTypeManager::class,

    \Neos\ContentRepository\Domain\Model\Workspace::class => \Neos\ContentRepository\Core\SharedModel\Workspace\Workspace::class,
    \Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier::class => \Neos\ContentRepository\Core\SharedModel\Node\NodeAggregateId::class,
    \Neos\ContentRepository\Domain\NodeAggregate\NodeName::class => \Neos\ContentRepository\Core\SharedModel\Node\NodeName::class,
    \Neos\ContentRepository\Domain\NodeType\NodeTypeName::class => \Neos\ContentRepository\Core\NodeType\NodeTypeName::class,
    \Neos\ContentRepository\Domain\Projection\Content\PropertyCollectionInterface::class => \Neos\ContentRepository\Core\Projection\ContentGraph\PropertyCollection::class,
    \Neos\ContentRepository\Domain\Model\ArrayPropertyCollection::class => \Neos\ContentRepository\Core\Projection\ContentGraph\PropertyCollection::class,
    \Neos\Neos\Routing\FrontendNodeRoutePartHandlerInterface::class => \Neos\Neos\FrontendRouting\FrontendNodeRoutePartHandlerInterface::class,
]);

/**
 * SPECIAL rules
 */
$rectorConfig->withConfiguredRule(\Neos\Rector\Generic\Rules\RemoveParentClassRector::class, [
    new RemoveParentClass(
        parentClassName: Neos\ContentRepository\Migration\Transformations\AbstractTransformation::class,
        comment: '// TODO 9.0 migration: You need to convert your AbstractTransformation to an implementation of Neos\ContentRepository\NodeMigration\Transformation\TransformationFactoryInterface'
    )
]);

/**
 * CLEAN UP / END GLOBAL RULES
 */
$rectorConfig->withConfiguredRule(MethodCallToPropertyFetchRector::class, $methodCallToPropertyFetches);
$rectorConfig->withConfiguredRule(MethodCallToWarningCommentRector::class, $methodCallToWarningComments);

// Remove injections to classes which are gone now
$rectorConfig->withConfiguredRule(RemoveInjectionsRector::class, [
    new RemoveInjection(\Neos\ContentRepository\Domain\Service\ContextFactoryInterface::class),
    new RemoveInjection(\Neos\ContentRepository\Domain\Service\ContextFactory::class),
    new RemoveInjection(\Neos\Neos\Domain\Service\ContentContextFactory::class),
    new RemoveInjection(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub::class),
    new RemoveInjection(\Neos\ContentRepository\Domain\Service\ContentDimensionCombinator::class),
    new RemoveInjection(\Neos\ContentRepository\Domain\Factory\NodeFactory::class),
    new RemoveInjection(\Neos\ContentRepository\Domain\Repository\WorkspaceRepository::class),
    new RemoveInjection(\Neos\ContentRepository\Core\NodeType\NodeTypeManager::class),
    new RemoveInjection(\Neos\Neos\Domain\Service\NodeSearchServiceInterface::class),
    new RemoveInjection(\Neos\Neos\Domain\Service\NodeSearchService::class),
    new RemoveInjection(\Neos\ContentRepository\Domain\Repository\NodeDataRepository::class),
    new RemoveInjection(\Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory::class),
]);

// Remove traits which are gone
$rectorConfig->withConfiguredRule(RemoveTraitUseRector::class, [
    \Neos\Neos\Controller\CreateContentContextTrait::class
]);

// todo these ToStringToMethodCallOrPropertyFetchRector rules are likely mostly obsolete and only to migrate from one Neos 9 beta to another but NOT for upgrading from 8.3
// see https://github.com/neos/neos-development-collection/pull/4156
$rectorConfig->withConfiguredRule(ToStringToMethodCallOrPropertyFetchRector::class, [
    \Neos\ContentRepository\Core\Dimension\ContentDimensionId::class => 'value',
    \Neos\ContentRepository\Core\Dimension\ContentDimensionValue::class => 'value',
    \Neos\ContentRepository\Core\Dimension\ContentDimensionValueSpecializationDepth::class => 'value',
    \Neos\ContentRepository\Core\Feature\ContentStreamEventStreamName::class => 'value',
    \Neos\ContentRepository\Core\Infrastructure\Property\PropertyType::class => 'value',
    \Neos\ContentRepository\Core\NodeType\NodeType::class => 'name',
    \Neos\ContentRepository\Core\NodeType\NodeTypeName::class => 'value',
    \Neos\ContentRepository\Core\Projection\ContentGraph\NodePath::class => 'value',
    \Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::class => 'value',
    \Neos\ContentRepository\Core\SharedModel\Node\NodeAggregateId::class => 'value',
    \Neos\ContentRepository\Core\SharedModel\Node\NodeName::class => 'value',
    \Neos\ContentRepository\Core\SharedModel\Node\PropertyName::class => 'value',
    \Neos\ContentRepository\Core\SharedModel\Node\ReferenceName::class => 'value',
    \Neos\ContentRepository\Core\SharedModel\User\UserId::class => 'value',
    \Neos\ContentRepository\Core\SharedModel\Workspace\ContentStreamId::class => 'value',
    \Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceDescription::class => 'value',
    \Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::class => 'value',
    \Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceTitle::class => 'value',
    \Neos\ContentRepository\Core\DimensionSpace\AbstractDimensionSpacePoint::class => 'toJson()',
    \Neos\ContentRepository\Core\DimensionSpace\ContentSubgraphVariationWeight::class => 'toJson()',
    \Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePointSet::class => 'toJson()',
    \Neos\ContentRepository\Core\DimensionSpace\OriginDimensionSpacePointSet::class => 'toJson()',
    \Neos\ContentRepository\Core\Projection\ContentGraph\CoverageByOrigin::class => 'toJson()',
    \Neos\ContentRepository\Core\Projection\ContentGraph\OriginByCoverage::class => 'toJson()',
    \Neos\ContentRepository\Core\SharedModel\Node\NodeAggregateIds::class => 'toJson()',
]);

// Should run LAST - as other rules above might create $this->contentRepositoryRegistry calls.
$rectorConfig->withConfiguredRule(InjectServiceIfNeededRector::class, [
    new AddInjection('contentRepositoryRegistry', ContentRepositoryRegistry::class),
    new AddInjection('renderingModeService', RenderingModeService::class),
    new AddInjection('nodeLabelGenerator', NodeLabelGeneratorInterface::class),
    new AddInjection('workspacePublishingService', WorkspacePublishingService::class),
    new AddInjection('workspaceService', WorkspaceService::class),
]);


return $rectorConfig;