# 74 Rules Overview

## ContentDimensionCombinatorGetAllAllowedCombinationsRector

`"ContentDimensionCombinator::getAllAllowedCombinations()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\ContentDimensionCombinatorGetAllAllowedCombinationsRector`](../src/ContentRepository90/Rules/ContentDimensionCombinatorGetAllAllowedCombinationsRector.php)

<br>

## ContentRepositoryUtilityRenderValidNodeNameRector

Replaces Utility::renderValidNodeName(...) into NodeName::fromString(...)->value.

- class: [`Neos\Rector\ContentRepository90\Rules\ContentRepositoryUtilityRenderValidNodeNameRector`](../src/ContentRepository90/Rules/ContentRepositoryUtilityRenderValidNodeNameRector.php)

<br>

## ContextFactoryToLegacyContextStubRector

`"ContextFactory::create()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\ContextFactoryToLegacyContextStubRector`](../src/ContentRepository90/Rules/ContextFactoryToLegacyContextStubRector.php)

<br>

## ContextGetCurrentRenderingModeRector

`"ContentContext::getCurrentRenderingMode()"` will be replaced with `RenderingModeService::findByCurrentUser().`

- class: [`Neos\Rector\ContentRepository90\Rules\ContextGetCurrentRenderingModeRector`](../src/ContentRepository90/Rules/ContextGetCurrentRenderingModeRector.php)

<br>

## ContextGetFirstLevelNodeCacheRector

`"Context::getFirstLevelNodeCache()"` will be removed.

- class: [`Neos\Rector\ContentRepository90\Rules\ContextGetFirstLevelNodeCacheRector`](../src/ContentRepository90/Rules/ContextGetFirstLevelNodeCacheRector.php)

<br>

## ContextGetRootNodeRector

`"Context::getRootNode()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\ContextGetRootNodeRector`](../src/ContentRepository90/Rules/ContextGetRootNodeRector.php)

<br>

## ContextIsInBackendRector

`"ContentContext::isLive()"` will be replaced with `RenderingModeService::findByCurrentUser().`

- class: [`Neos\Rector\ContentRepository90\Rules\ContextIsInBackendRector`](../src/ContentRepository90/Rules/ContextIsInBackendRector.php)

<br>

## ContextIsLiveRector

`"ContentContext::isLive()"` will be replaced with `RenderingModeService::findByCurrentUser().`

- class: [`Neos\Rector\ContentRepository90\Rules\ContextIsLiveRector`](../src/ContentRepository90/Rules/ContextIsLiveRector.php)

<br>

## FusionCacheLifetimeRector

Fusion: Add comment if `.cacheLifetime()` is used.

- class: [`Neos\Rector\ContentRepository90\Rules\FusionCacheLifetimeRector`](../src/ContentRepository90/Rules/FusionCacheLifetimeRector.php)

<br>

## FusionCachingNodeInEntryIdentifierRector

Fusion: Rewrite node to Neos.Caching.entryIdentifierForNode(...) in @cache.entryIdentifier segments

- class: [`Neos\Rector\ContentRepository90\Rules\FusionCachingNodeInEntryIdentifierRector`](../src/ContentRepository90/Rules/FusionCachingNodeInEntryIdentifierRector.php)

<br>

## FusionContextCurrentRenderingModeRector

Fusion: Rewrite node.context.currentRenderingMode... to renderingMode...

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextCurrentRenderingModeRector`](../src/ContentRepository90/Rules/FusionContextCurrentRenderingModeRector.php)

<br>

## FusionContextCurrentSiteRector

Fusion: Rewrite node.context.currentSite to Neos.Site.findBySiteNode(site)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextCurrentSiteRector`](../src/ContentRepository90/Rules/FusionContextCurrentSiteRector.php)

<br>

## FusionContextGetWorkspaceNameRector

Fusion: Rewrite "node.context.workspaceName" to "node.workspaceName"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextGetWorkspaceNameRector`](../src/ContentRepository90/Rules/FusionContextGetWorkspaceNameRector.php)

<br>

## FusionContextGetWorkspaceRector

Fusion: Add comment to "node.context.workspace"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextGetWorkspaceRector`](../src/ContentRepository90/Rules/FusionContextGetWorkspaceRector.php)

<br>

## FusionContextInBackendRector

Fusion: Rewrite "node.context.inBackend" to "renderingMode.isEdit"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextInBackendRector`](../src/ContentRepository90/Rules/FusionContextInBackendRector.php)

<br>

## FusionContextLiveRector

Fusion: Rewrite "node.context.live" to "!renderingMode.isEdit"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextLiveRector`](../src/ContentRepository90/Rules/FusionContextLiveRector.php)

<br>

## FusionFlowQueryContextRector

Fusion: Add comments for q(node).context({targetDimensions|currentDateTime|removedContentShown|inaccessibleContentShown: ...})

- class: [`Neos\Rector\ContentRepository90\Rules\FusionFlowQueryContextRector`](../src/ContentRepository90/Rules/FusionFlowQueryContextRector.php)

<br>

## FusionFlowQueryNodePropertyToWarningCommentRector

Fusion: Adds a warning comment when the defined property is used within an FlowQuery `"property()".`

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\FusionFlowQueryNodePropertyToWarningCommentRector`](../src/Generic/Rules/FusionFlowQueryNodePropertyToWarningCommentRector.php)

<br>

## FusionNodeAggregateIdentifierRector

Fusion: Rewrite node.nodeAggregateIdentifier to node.aggregateId

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeAggregateIdentifierRector`](../src/ContentRepository90/Rules/FusionNodeAggregateIdentifierRector.php)

<br>

## FusionNodeAutoCreatedRector

Fusion: Rewrite node.autoCreated to node.classification.tethered

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeAutoCreatedRector`](../src/ContentRepository90/Rules/FusionNodeAutoCreatedRector.php)

<br>

## FusionNodeContextPathRector

Fusion: Rewrite node.contextPath to Neos.Node.serializedNodeAddress(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeContextPathRector`](../src/ContentRepository90/Rules/FusionNodeContextPathRector.php)

<br>

## FusionNodeDepthRector

Fusion: Rewrite node.depth and q(node).property("_depth") to Neos.Node.depth(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeDepthRector`](../src/ContentRepository90/Rules/FusionNodeDepthRector.php)

<br>

## FusionNodeHiddenAfterDateTimeRector

Fusion: Rewrite node.hiddenAfterDateTime to q(node).property("disableAfterDateTime")

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenAfterDateTimeRector`](../src/ContentRepository90/Rules/FusionNodeHiddenAfterDateTimeRector.php)

<br>

## FusionNodeHiddenBeforeDateTimeRector

Fusion: Rewrite node.hiddenBeforeDateTime to q(node).property("enableAfterDateTime")

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenBeforeDateTimeRector`](../src/ContentRepository90/Rules/FusionNodeHiddenBeforeDateTimeRector.php)

<br>

## FusionNodeHiddenInIndexRector

Fusion: Rewrite node.hiddenInIndex and q(node).property("_hiddenInIndex") to node.property('hiddenInIndex')

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenInIndexRector`](../src/ContentRepository90/Rules/FusionNodeHiddenInIndexRector.php)

<br>

## FusionNodeHiddenRector

Fusion: Rewrite node.hidden and q(node).property("_hidden") to Neos.Node.isDisabled(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenRector`](../src/ContentRepository90/Rules/FusionNodeHiddenRector.php)

<br>

## FusionNodeIdentifierRector

Fusion: Rewrite "node.identifier" and "q(node).property('_identifier')" to "node.aggregateId"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeIdentifierRector`](../src/ContentRepository90/Rules/FusionNodeIdentifierRector.php)

<br>

## FusionNodeLabelRector

Fusion: Rewrite "node.label" and "q(node).property('_label')" to "Neos.Node.label(node)"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeLabelRector`](../src/ContentRepository90/Rules/FusionNodeLabelRector.php)

<br>

## FusionNodeNodeTypeRector

Fusion: Rewrite "node.nodeType" and "q(node).property('_nodeType')" to "Neos.Node.nodeType(node)"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeNodeTypeRector`](../src/ContentRepository90/Rules/FusionNodeNodeTypeRector.php)

<br>

## FusionNodeParentRector

Fusion: Rewrite node.parent to `q(node).parent().get(0)`

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeParentRector`](../src/ContentRepository90/Rules/FusionNodeParentRector.php)

<br>

## FusionNodePathRector

Fusion: Rewrite node.path and q(node).property("_path") to Neos.Node.path(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodePathRector`](../src/ContentRepository90/Rules/FusionNodePathRector.php)

<br>

## FusionNodePropertyPathToWarningCommentRector

Fusion: Adds a warning comment when the defined path is used within an Eel expression.

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\FusionNodePropertyPathToWarningCommentRector`](../src/Generic/Rules/FusionNodePropertyPathToWarningCommentRector.php)

<br>

## FusionNodeTypeNameRector

Fusion: Rewrite node.nodeType.name to node.nodeTypeName

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeTypeNameRector`](../src/ContentRepository90/Rules/FusionNodeTypeNameRector.php)

<br>

## FusionPrototypeNameAddCommentRector

Fusion: Add comment to file if prototype name matches at least once.

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\FusionPrototypeNameAddCommentRector`](../src/Generic/Rules/FusionPrototypeNameAddCommentRector.php)

<br>

## FusionReplacePrototypeNameRector

Fusion: Rewrite prototype names form e.g Foo.Bar:Boo to Boo.Bar:Foo

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\FusionReplacePrototypeNameRector`](../src/Generic/Rules/FusionReplacePrototypeNameRector.php)

<br>

## InjectServiceIfNeededRector

add injection for `$contentRepositoryRegistry` if in use.

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\InjectServiceIfNeededRector`](../src/Generic/Rules/InjectServiceIfNeededRector.php)

<br>

## MethodCallToWarningCommentRector

"Warning comments for various non-supported use cases

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\MethodCallToWarningCommentRector`](../src/Generic/Rules/MethodCallToWarningCommentRector.php)

<br>

## NodeFactoryResetRector

`"NodeFactory::reset()"` will be removed.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeFactoryResetRector`](../src/ContentRepository90/Rules/NodeFactoryResetRector.php)

<br>

## NodeFindParentNodeRector

`"NodeInterface::findParentNode()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeFindParentNodeRector`](../src/ContentRepository90/Rules/NodeFindParentNodeRector.php)

<br>

## NodeGetChildNodesRector

`"NodeInterface::getChildNodes()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetChildNodesRector`](../src/ContentRepository90/Rules/NodeGetChildNodesRector.php)

<br>

## NodeGetContextGetWorkspaceNameRector

`"NodeInterface::getContext()::getWorkspaceName()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceNameRector`](../src/ContentRepository90/Rules/NodeGetContextGetWorkspaceNameRector.php)

<br>

## NodeGetContextGetWorkspaceRector

`"NodeInterface::getContext()::getWorkspace()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceRector`](../src/ContentRepository90/Rules/NodeGetContextGetWorkspaceRector.php)

<br>

## NodeGetContextPathRector

`"NodeInterface::getContextPath()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetContextPathRector`](../src/ContentRepository90/Rules/NodeGetContextPathRector.php)

<br>

## NodeGetDepthRector

`"NodeInterface::getDepth()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetDepthRector`](../src/ContentRepository90/Rules/NodeGetDepthRector.php)

<br>

## NodeGetDimensionsRector

`"NodeInterface::getDimensions()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetDimensionsRector`](../src/ContentRepository90/Rules/NodeGetDimensionsRector.php)

<br>

## NodeGetHiddenBeforeAfterDateTimeRector

`"NodeInterface::getHiddenBeforeDateTime()",` `"NodeInterface::setHiddenBeforeDateTime()",` `"NodeInterface::getHiddenAfterDateTime()"` and `"NodeInterface::setHiddenAfterDateTime()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetHiddenBeforeAfterDateTimeRector`](../src/ContentRepository90/Rules/NodeGetHiddenBeforeAfterDateTimeRector.php)

<br>

## NodeGetIdentifierRector

`"NodeInterface::getIdentifier()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetIdentifierRector`](../src/ContentRepository90/Rules/NodeGetIdentifierRector.php)

<br>

## NodeGetNodeTypeGetNameRector

`"NodeInterface::getNodeType()->getName()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetNodeTypeGetNameRector`](../src/ContentRepository90/Rules/NodeGetNodeTypeGetNameRector.php)

<br>

## NodeGetNodeTypeRector

`"NodeInterface::getNodeType()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetNodeTypeRector`](../src/ContentRepository90/Rules/NodeGetNodeTypeRector.php)

<br>

## NodeGetParentRector

`"NodeInterface::getParent()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetParentRector`](../src/ContentRepository90/Rules/NodeGetParentRector.php)

<br>

## NodeGetPathRector

`"NodeInterface::getPath()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetPathRector`](../src/ContentRepository90/Rules/NodeGetPathRector.php)

<br>

## NodeGetPropertyNamesRector

"$nodeType->allowsGrandchildNodeType($parentNodeName, `$nodeType)"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetPropertyNamesRector`](../src/ContentRepository90/Rules/NodeGetPropertyNamesRector.php)

<br>

## NodeIsAutoCreatedRector

"NodeInterface::isAutoCreated" will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeIsAutoCreatedRector`](../src/ContentRepository90/Rules/NodeIsAutoCreatedRector.php)

<br>

## NodeIsHiddenInIndexRector

`"NodeInterface::isHiddenInIndex()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeIsHiddenInIndexRector`](../src/ContentRepository90/Rules/NodeIsHiddenInIndexRector.php)

<br>

## NodeIsHiddenRector

`"NodeInterface::isHidden()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeIsHiddenRector`](../src/ContentRepository90/Rules/NodeIsHiddenRector.php)

<br>

## NodeLabelGeneratorRector

`"$node->getLabel()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeLabelGeneratorRector`](../src/ContentRepository90/Rules/NodeLabelGeneratorRector.php)

<br>

## NodeSearchServiceRector

`"NodeSearchService::findDescendantNodes()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeSearchServiceRector`](../src/ContentRepository90/Rules/NodeSearchServiceRector.php)

<br>

## NodeTypeAllowsGrandchildNodeTypeRector

"$nodeType->allowsGrandchildNodeType($parentNodeName, `$nodeType)"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeAllowsGrandchildNodeTypeRector`](../src/ContentRepository90/Rules/NodeTypeAllowsGrandchildNodeTypeRector.php)

<br>

## NodeTypeGetAutoCreatedChildNodesRector

`"$nodeType->getAutoCreatedChildNodes()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeGetAutoCreatedChildNodesRector`](../src/ContentRepository90/Rules/NodeTypeGetAutoCreatedChildNodesRector.php)

<br>

## NodeTypeGetNameRector

`"NodeType::getName()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeGetNameRector`](../src/ContentRepository90/Rules/NodeTypeGetNameRector.php)

<br>

## NodeTypeGetTypeOfAutoCreatedChildNodeRector

"$nodeType->getTypeOfAutoCreatedChildNode($nodeName)" will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeGetTypeOfAutoCreatedChildNodeRector`](../src/ContentRepository90/Rules/NodeTypeGetTypeOfAutoCreatedChildNodeRector.php)

<br>

## NodeTypeManagerAccessRector

"$this->nodeTypeManager" will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeManagerAccessRector`](../src/ContentRepository90/Rules/NodeTypeManagerAccessRector.php)

<br>

## ObjectInstantiationToWarningCommentRector

"Warning comments for various non-supported signals

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\ObjectInstantiationToWarningCommentRector`](../src/Generic/Rules/ObjectInstantiationToWarningCommentRector.php)

<br>

## RemoveDuplicateCommentRector

"Warning comments for various non-supported use cases

- class: [`Neos\Rector\Generic\Rules\RemoveDuplicateCommentRector`](../src/Generic/Rules/RemoveDuplicateCommentRector.php)

<br>

## RemoveInjectionsRector

Remove properties marked with a @Flow\Inject annotation and a certain type

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\RemoveInjectionsRector`](../src/Generic/Rules/RemoveInjectionsRector.php)

<br>

## RemoveParentClassRector

Remove "extends BLABLA" from classes

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\RemoveParentClassRector`](../src/Generic/Rules/RemoveParentClassRector.php)

<br>

## SignalSlotToWarningCommentRector

"Warning comments for various non-supported signals

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\SignalSlotToWarningCommentRector`](../src/Generic/Rules/SignalSlotToWarningCommentRector.php)

<br>

## ToStringToMethodCallOrPropertyFetchRector

Turns defined code uses of `"__toString()"` method to specific method calls or property fetches.

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\ToStringToMethodCallOrPropertyFetchRector`](../src/Generic/Rules/ToStringToMethodCallOrPropertyFetchRector.php)

<br>

## WorkspaceGetNameRector

`"Workspace::getName()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceGetNameRector`](../src/ContentRepository90/Rules/WorkspaceGetNameRector.php)

<br>

## WorkspaceRepositoryCountByNameRector

`"WorkspaceRepository::countByName()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryCountByNameRector`](../src/ContentRepository90/Rules/WorkspaceRepositoryCountByNameRector.php)

<br>

## WorkspaceRepositoryFindByBaseWorkspaceRector

`"WorkspaceRepository::findByBaseWorkspace()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryFindByBaseWorkspaceRector`](../src/ContentRepository90/Rules/WorkspaceRepositoryFindByBaseWorkspaceRector.php)

<br>

## WorkspaceRepositoryFindByIdentifierRector

`"WorkspaceRepository::findByIdentifier()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryFindByIdentifierRector`](../src/ContentRepository90/Rules/WorkspaceRepositoryFindByIdentifierRector.php)

<br>

## YamlDimensionConfigRector

Fusion: Rewrite Settings.yaml config to new language

- class: [`Neos\Rector\ContentRepository90\Rules\YamlDimensionConfigRector`](../src/ContentRepository90/Rules/YamlDimensionConfigRector.php)

<br>

## YamlRoutePartHandlerRector

Fusion: Rewrite Routes.yaml config to use Neos\Neos\FrontendRouting\FrontendNodeRoutePartHandlerInterface as route part handler

- class: [`Neos\Rector\ContentRepository90\Rules\YamlRoutePartHandlerRector`](../src/ContentRepository90/Rules/YamlRoutePartHandlerRector.php)

<br>
