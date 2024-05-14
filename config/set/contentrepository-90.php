<?php
declare (strict_types=1);

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use Neos\Neos\Domain\Service\RenderingModeService;
use Neos\Rector\ContentRepository90\Legacy\LegacyContextStub;
use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
use Neos\Rector\ContentRepository90\Rules\ContentDimensionCombinatorGetAllAllowedCombinationsRector;
use Neos\Rector\ContentRepository90\Rules\ContextFactoryToLegacyContextStubRector;
use Neos\Rector\ContentRepository90\Rules\ContextGetCurrentRenderingModeRector;
use Neos\Rector\ContentRepository90\Rules\ContextGetFirstLevelNodeCacheRector;
use Neos\Rector\ContentRepository90\Rules\ContextGetRootNodeRector;
use Neos\Rector\ContentRepository90\Rules\ContextIsInBackendRector;
use Neos\Rector\ContentRepository90\Rules\ContextIsLiveRector;
use Neos\Rector\ContentRepository90\Rules\FusionCachingNodeInEntryIdentifierRector;
use Neos\Rector\ContentRepository90\Rules\FusionContextCurrentRenderingModeRector;
use Neos\Rector\ContentRepository90\Rules\FusionContextCurrentSiteRector;
use Neos\Rector\ContentRepository90\Rules\FusionContextInBackendRector;
use Neos\Rector\ContentRepository90\Rules\FusionContextLiveRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeAggregateIdentifierRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeAutoCreatedRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeContextPathRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeDepthRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenInIndexRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeIdentifierRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeParentRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodePathRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeTypeNameRector;
use Neos\Rector\ContentRepository90\Rules\NodeFactoryResetRector;
use Neos\Rector\ContentRepository90\Rules\NodeFindParentNodeRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetChildNodesRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceNameRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetDepthRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetDimensionsRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetIdentifierRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetParentRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetPathRector;
use Neos\Rector\ContentRepository90\Rules\NodeIsHiddenInIndexRector;
use Neos\Rector\ContentRepository90\Rules\NodeIsHiddenRector;
use Neos\Rector\ContentRepository90\Rules\NodeTypeAllowsGrandchildNodeTypeRector;
use Neos\Rector\ContentRepository90\Rules\NodeTypeGetAutoCreatedChildNodesRector;
use Neos\Rector\ContentRepository90\Rules\NodeTypeGetNameRector;
use Neos\Rector\ContentRepository90\Rules\NodeTypeGetTypeOfAutoCreatedChildNodeRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceGetNameRector;
use Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryCountByNameRector;
use Neos\Rector\ContentRepository90\Rules\YamlDimensionConfigRector;
use Neos\Rector\Generic\Rules\FusionNodePropertyPathToWarningCommentRector;
use Neos\Rector\Generic\Rules\FusionPrototypeNameAddCommentRector;
use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\Rules\MethodCallToWarningCommentRector;
use Neos\Rector\Generic\Rules\RemoveInjectionsRector;
use Neos\Rector\Generic\Rules\ToStringToMethodCallOrPropertyFetchRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Neos\Rector\Generic\ValueObject\FusionFlowQueryNodePropertyToWarningComment;
use Neos\Rector\Generic\ValueObject\FusionNodePropertyPathToWarningComment;
use Neos\Rector\Generic\ValueObject\MethodCallToWarningComment;
use Neos\Rector\Generic\ValueObject\RemoveInjection;
use Neos\Rector\Generic\ValueObject\RemoveParentClass;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\MethodCall\MethodCallToPropertyFetchRector;
use Rector\Transform\ValueObject\MethodCallToPropertyFetch;
use Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenAfterDateTimeRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenBeforeDateTimeRector;
use Neos\Rector\Generic\Rules\FusionReplacePrototypeNameRector;
use Neos\Rector\Generic\ValueObject\FusionPrototypeNameReplacement;
use Neos\Rector\Generic\ValueObject\FusionPrototypeNameAddComment;

return static function (RectorConfig $rectorConfig): void {
    // Register FusionFileProcessor. All Fusion Rectors will be auto-registered at this processor.
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();
    $services->set(\Neos\Rector\Core\FusionProcessing\FusionFileProcessor::class);
    $services->set(\Neos\Rector\Core\YamlProcessing\YamlFileProcessor::class);
    $rectorConfig->disableParallel(); // parallel does not work for non-PHP-Files, so we need to disable it - see https://github.com/rectorphp/rector-src/pull/2597#issuecomment-1190120688


    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Neos\\ContentRepository\\Domain\\Model\\NodeInterface' => NodeLegacyStub::class,
        'Neos\\ContentRepository\\Domain\\Projection\\Content\\NodeInterface' => NodeLegacyStub::class,
        'Neos\\ContentRepository\\Domain\\Projection\\Content\\TraversableNodeInterface' => NodeLegacyStub::class,

        'Neos\ContentRepository\Domain\Service\Context' => LegacyContextStub::class,
        'Neos\Neos\Domain\Service\ContentContext' => LegacyContextStub::class,

        'Neos\ContentRepository\Domain\Model\NodeType' => \Neos\ContentRepository\Core\NodeType\NodeType::class,
        'Neos\ContentRepository\Domain\Service\NodeTypeManager' => \Neos\ContentRepository\Core\NodeType\NodeTypeManager::class,

        'Neos\ContentRepository\Utility' => \Neos\ContentRepositoryRegistry\Utility::class,

        'Neos\ContentRepository\Domain\Model\Workspace' => \Neos\ContentRepository\Core\Projection\Workspace\Workspace::class,
    ]);

    $rectorConfig->ruleWithConfiguration(FusionReplacePrototypeNameRector::class, [
        new FusionPrototypeNameReplacement('Neos.Fusion:Array', 'Neos.Fusion:Join'),
        new FusionPrototypeNameReplacement('Neos.Fusion:RawArray', 'Neos.Fusion:DataStructure'),
        new FusionPrototypeNameReplacement('Neos.Fusion:Collection', 'Neos.Fusion:Loop',
            'Migration of Neos.Fusion:Collection to Neos.Fusion:Loop needs manual action. The key `children` has to be renamed to `items` which cannot be done automatically'
        ),
        new FusionPrototypeNameReplacement('Neos.Fusion:RawCollection', 'Neos.Fusion:Map',
            'Migration of Neos.Fusion:RawCollection to Neos.Fusion:Map needs manual action. The key `children` has to be renamed to `items` which cannot be done automatically'
        ),
    ]);


    /** @var MethodCallToPropertyFetch[] $methodCallToPropertyFetches */
    $methodCallToPropertyFetches = [];

    /** @var MethodCallToWarningComment[] $methodCallToWarningComments */
    $methodCallToWarningComments = [];


    $fusionFlowQueryPropertyToComments = [];
    /**
     * Neos\ContentRepository\Domain\Model\NodeInterface
     */
    // setName
    // getName
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(NodeLegacyStub::class, 'getName', 'nodeName');
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_name', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_name")" to "VARIABLE.nodeName.value". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.');
    // getLabel -> compatible with ES CR node (nothing to do)
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_label', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_label")" to "VARIABLE.label". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.');
    // setProperty
    // hasProperty -> compatible with ES CR Node (nothing to do)
    // getProperty -> compatible with ES CR Node (nothing to do)
    // removeProperty
    // getProperties -> PropertyCollectionInterface
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(NodeLegacyStub::class, 'getProperties', 'properties');
    // getPropertyNames
    // setContentObject -> DEPRECATED / NON-FUNCTIONAL
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setContentObject', '!! Node::setContentObject() is not supported by the new CR. Referencing objects can be done by storing them in Node::properties (and the serialization/deserialization is extensible).');
    // getContentObject -> DEPRECATED / NON-FUNCTIONAL
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getContentObject', '!! Node::getContentObject() is not supported by the new CR. Referencing objects can be done by storing them in Node::properties (and the serialization/deserialization is extensible).');
    // unsetContentObject -> DEPRECATED / NON-FUNCTIONAL
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'unsetContentObject', '!! Node::unsetContentObject() is not supported by the new CR. Referencing objects can be done by storing them in Node::properties (and the serialization/deserialization is extensible).');
    // setNodeType
    // getNodeType: NodeType
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(NodeLegacyStub::class, 'getNodeType', 'nodeType');
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_nodeType', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_nodeType")" to "VARIABLE.nodeType". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.');
    // setHidden
    // isHidden
    $rectorConfig->rule(NodeIsHiddenRector::class);
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_hidden', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_hidden")". Use the NodeHiddenStateFinder of CR to determine the hidden state. ');
    // TODO: Fusion NodeAccess
    // setHiddenBeforeDateTime
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setHiddenBeforeDateTime', '!! Node::setHiddenBeforeDateTime() is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.');
    // getHiddenBeforeDateTime
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getHiddenBeforeDateTime', '!! Node::getHiddenBeforeDateTime() is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.');
    $rectorConfig->rule(FusionNodeHiddenBeforeDateTimeRector::class);
    // setHiddenAfterDateTime
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setHiddenAfterDateTime', '!! Node::setHiddenAfterDateTime() is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.');
    // getHiddenAfterDateTime
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getHiddenAfterDateTime', '!! Node::getHiddenAfterDateTime() is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.');
    $rectorConfig->rule(FusionNodeHiddenAfterDateTimeRector::class);
    // setHiddenInIndex
    // isHiddenInIndex
    $rectorConfig->rule(NodeIsHiddenInIndexRector::class);
    // Fusion: .hiddenInIndex -> node.properties._hiddenInIndex
    $rectorConfig->rule(FusionNodeHiddenInIndexRector::class);
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_hiddenInIndex', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_hiddenInIndex")" to "VARIABLE.property(\'hiddenInMenu\')". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.');
    // setAccessRoles
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setAccessRoles', '!! Node::setAccessRoles() is not supported by the new CR.');
    // getAccessRoles
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getAccessRoles', '!! Node::getAccessRoles() is not supported by the new CR.');
    // getPath
    $rectorConfig->rule(NodeGetPathRector::class);
    $rectorConfig->rule(FusionNodePathRector::class);
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_path', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_path")" to "Neos.Node.path(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.');
    // getContextPath
    // TODO: PHP
    $rectorConfig->rule(FusionNodeContextPathRector::class);
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_contextPath', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_contextPath")" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.');
    // getDepth
    $rectorConfig->rule(NodeGetDepthRector::class);
    $rectorConfig->rule(FusionNodeDepthRector::class);
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_depth', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_depth")" to "Neos.Node.depth(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.');
    // setWorkspace -> internal
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setWorkspace', '!! Node::setWorkspace() was always internal, and the workspace system has been fundamentally changed with the new CR. Try to rewrite your code around Content Streams.');
    // getWorkspace
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getWorkspace', '!! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.');
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_workspace', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_workspace")". It does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.');
    // getIdentifier
    $rectorConfig->rule(NodeGetIdentifierRector::class);
    $rectorConfig->rule(FusionNodeIdentifierRector::class);
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_identifier', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_identifier")" to "VARIABLE.nodeAggregateId.value". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.');
    // setIndex -> internal
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'setIndex', '!! Node::setIndex() was always internal. To reorder nodes, use the "MoveNodeAggregate" command');
    // getIndex
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getIndex', '!! Node::getIndex() is not supported. You can fetch all siblings and inspect the ordering');
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_index', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_index")". You can fetch all siblings and inspect the ordering.');
    // getParent -> Node
    $rectorConfig->rule(NodeGetParentRector::class);
    $rectorConfig->rule(FusionNodeParentRector::class);
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_parent', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_parent")" to "q(VARIABLE).parent().get(0)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.');
    // getParentPath - deprecated
    // createNode
    // createSingleNode -> internal
    // createNodeFromTemplate
    // getNode(relative path) - deprecated
    // getPrimaryChildNode() - deprecated
    // getChildNodes($nodeTypeFilter, $limit, $offset) - deprecated
    $rectorConfig->rule(NodeGetChildNodesRector::class);
    // hasChildNodes($nodeTypeFilter) - deprecated
    // remove()
    // setRemoved()
    // isRemoved()
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'isRemoved', '!! Node::isRemoved() - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.');
    $fusionNodePropertyPathToWarningComments[] = new FusionNodePropertyPathToWarningComment('removed', 'Line %LINE: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.');
    // isVisible()
    // isAccessible()
    // hasAccessRestrictions()
    // isNodeTypeAllowedAsChildNode()
    // moveBefore()
    // moveAfter()
    // moveInto()
    // copyBefore()
    // copyAfter()
    // copyInto()
    // getNodeData()
    $methodCallToWarningComments[] = new MethodCallToWarningComment(NodeLegacyStub::class, 'getNodeData', '!! Node::getNodeData() - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.');
    // getContext()
    // getContext()->getWorkspace()
    $rectorConfig->rule(NodeGetContextGetWorkspaceRector::class);
    // TODO: Fusion
    // getContext()->getWorkspaceName()
    $rectorConfig->rule(NodeGetContextGetWorkspaceNameRector::class);
    // TODO: Fusion
    // getDimensions()
    $rectorConfig->rule(NodeGetDimensionsRector::class);
    // TODO: Fusion
    // createVariantForContext()
    // isAutoCreated()
    // TODO: PHP
    $rectorConfig->rule(FusionNodeAutoCreatedRector::class);
    $fusionFlowQueryPropertyToComments[] = new FusionFlowQueryNodePropertyToWarningComment('_autoCreated', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_autoCreated")" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.');

    // getOtherNodeVariants()

    $rectorConfig->ruleWithConfiguration(FusionNodePropertyPathToWarningCommentRector::class, $fusionFlowQueryPropertyToComments);


    /**
     * Neos\ContentRepository\Domain\Projection\Content\NodeInterface
     */
    // isRoot()
    // isTethered()
    // getContentStreamIdentifier() -> threw exception in <= Neos 8.0 - so nobody could have used this
    // getNodeAggregateIdentifier()
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(NodeLegacyStub::class, 'getNodeAggregateIdentifier', 'nodeAggregateId');
    $rectorConfig->rule(rectorClass: FusionNodeAggregateIdentifierRector::class);
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
    $rectorConfig->rule(FusionNodeTypeNameRector::class);
    $rectorConfig->rule(NodeTypeGetNameRector::class);
    // getAutoCreatedChildNodes
    $rectorConfig->rule(NodeTypeGetAutoCreatedChildNodesRector::class);
    // hasAutoCreatedChildNode
    $rectorConfig->ruleWithConfiguration(RenameMethodRector::class, [new MethodCallRename(
        NodeType::class,
        'hasAutoCreatedChildNode',
        'hasTetheredNode'
    )]);
    // getTypeOfAutoCreatedChildNode
    $rectorConfig->rule(NodeTypeGetTypeOfAutoCreatedChildNodeRector::class);
    // allowsGrandchildNodeType
    $rectorConfig->rule(NodeTypeAllowsGrandchildNodeTypeRector::class);


    /**
     * Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface
     */
    // getDimensionSpacePoint() -> threw exception in <= Neos 8.0 - so nobody could have used this
    // findParentNode() -> TraversableNodeInterface
    $rectorConfig->rule(NodeFindParentNodeRector::class);
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
    $rectorConfig->rule(ContextFactoryToLegacyContextStubRector::class);
    // Context::getWorkspaceName()
    // TODO: PHP
    // TODO: Fusion
    // Context::getRootNode()
    $rectorConfig->rule(ContextGetRootNodeRector::class);
    // TODO: Fusion
    // Context::getNode()
    // TODO: PHP
    // Context::getNodeByIdentifier()
    // TODO: PHP
    // Context::getNodeVariantsByIdentifier()
    // TODO: PHP
    // Context::getNodesOnPath()
    // TODO: PHP
    // Context::adoptNode()
    // Context::getFirstLevelNodeCache()
    $rectorConfig->rule(ContextGetFirstLevelNodeCacheRector::class);

    /**
     * ContentContext
     */
    // ContentContext::getCurrentSite
    $methodCallToWarningComments[] = new MethodCallToWarningComment(LegacyContextStub::class, 'getCurrentSite', '!! ContentContext::getCurrentSite() is removed in Neos 9.0.');
    $rectorConfig->rule(FusionContextCurrentSiteRector::class);
    // ContentContext::getCurrentDomain
    $methodCallToWarningComments[] = new MethodCallToWarningComment(LegacyContextStub::class, 'getCurrentDomain', '!! ContentContext::getCurrentDomain() is removed in Neos 9.0.');
    $fusionNodePropertyPathToWarningComments[] = new FusionNodePropertyPathToWarningComment('context.currentDomain', 'Line %LINE: !! node.context.currentDomain is removed in Neos 9.0.');
    // ContentContext::getCurrentSiteNode
    $methodCallToWarningComments[] = new MethodCallToWarningComment(LegacyContextStub::class, 'getCurrentSiteNode', '!! ContentContext::getCurrentSiteNode() is removed in Neos 9.0. Use Subgraph and traverse up to "Neos.Neos:Site" node.');
    $fusionNodePropertyPathToWarningComments[] = new FusionNodePropertyPathToWarningComment('context.currentSiteNode', 'Line %LINE: !! node.context.currentSiteNode is removed in Neos 9.0.');
    // ContentContext::isLive -> renderingMode.isLive
    $rectorConfig->rule(ContextIsLiveRector::class);
    $rectorConfig->rule(FusionContextLiveRector::class);
    // ContentContext::isInBackend -> renderingMode.inBackend
    $rectorConfig->rule(ContextIsInBackendRector::class);
    $rectorConfig->rule(FusionContextInBackendRector::class);
    // ContentContext::getCurrentRenderingMode... -> renderingMode...
    $rectorConfig->rule(ContextGetCurrentRenderingModeRector::class);
    $rectorConfig->rule(FusionContextCurrentRenderingModeRector::class);


    /**
     * ContentDimensionCombinator
     */
    // ContentDimensionCombinator::getAllAllowedCombinations
    $rectorConfig->rule(ContentDimensionCombinatorGetAllAllowedCombinationsRector::class);


    /**
     * Neos\ContentRepository\Domain\Factory\NodeFactory
     */
    // TODO: What other methods?
    // NodeFactory::reset
    $rectorConfig->rule(NodeFactoryResetRector::class);

    /**
     * Neos\ContentRepository\Domain\Repository\WorkspaceRepository
     */
    $rectorConfig->rule(WorkspaceRepositoryCountByNameRector::class);

    /**
     * Neos\ContentRepository\Domain\Model\Workspace
     */
    $rectorConfig->rule(WorkspaceGetNameRector::class);

    /**
     * Neos.Neos:PrimaryContent
     * Neos.Fusion:Attributes
     */
    $rectorConfig->ruleWithConfiguration(FusionPrototypeNameAddCommentRector::class, [
        new FusionPrototypeNameAddComment('Neos.Neos:PrimaryContent', 'TODO 9.0 migration: You need to refactor "Neos.Neos:PrimaryContent" to use "Neos.Neos:ContentCollection" instead.'),
        new FusionPrototypeNameAddComment('Neos.Fusion:Attributes', 'TODO 9.0 migration: Neos.Fusion:Attributes has been removed without a replacement. You need to replace it by the property attributes in Neos.Fusion:Tag')
    ]);

    /**
     * SPECIAL rules
     */
    $rectorConfig->rule(FusionCachingNodeInEntryIdentifierRector::class);
    $rectorConfig->ruleWithConfiguration(\Neos\Rector\Generic\Rules\RemoveParentClassRector::class, [
        new RemoveParentClass(
            parentClassName: Neos\ContentRepository\Migration\Transformations\AbstractTransformation::class,
            comment: '// TODO 9.0 migration: You need to convert your AbstractTransformation to an implementation of Neos\ContentRepository\NodeMigration\Transformation\TransformationFactoryInterface'
        )
    ]);

    $rectorConfig->rule(YamlDimensionConfigRector::class);

    /**
     * CLEAN UP / END GLOBAL RULES
     */
    $rectorConfig->ruleWithConfiguration(MethodCallToPropertyFetchRector::class, $methodCallToPropertyFetches);
    $rectorConfig->ruleWithConfiguration(MethodCallToWarningCommentRector::class, $methodCallToWarningComments);
    $rectorConfig->ruleWithConfiguration(FusionNodePropertyPathToWarningCommentRector::class, $fusionNodePropertyPathToWarningComments);

    // Remove injections to classes which are gone now
    $rectorConfig->ruleWithConfiguration(RemoveInjectionsRector::class, [
        new RemoveInjection(\Neos\ContentRepository\Domain\Service\ContextFactoryInterface::class),
        new RemoveInjection(\Neos\ContentRepository\Domain\Service\ContentDimensionCombinator::class),
        new RemoveInjection(\Neos\ContentRepository\Domain\Factory\NodeFactory::class),
        new RemoveInjection(\Neos\ContentRepository\Domain\Repository\WorkspaceRepository::class)
    ]);

    $rectorConfig->ruleWithConfiguration(ToStringToMethodCallOrPropertyFetchRector::class, [
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
        \Neos\ContentRepository\Core\Projection\ContentGraph\NodeTypeConstraints::class => 'toFilterString()',
        \Neos\ContentRepository\Core\Projection\ContentGraph\NodeTypeConstraintsWithSubNodeTypes::class => 'toFilterString()',
        \Neos\ContentRepository\Core\DimensionSpace\AbstractDimensionSpacePoint::class => 'toJson()',
        \Neos\ContentRepository\Core\DimensionSpace\ContentSubgraphVariationWeight::class => 'toJson()',
        \Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePointSet::class => 'toJson()',
        \Neos\ContentRepository\Core\DimensionSpace\OriginDimensionSpacePointSet::class => 'toJson()',
        \Neos\ContentRepository\Core\Feature\NodeMove\Dto\ParentNodeMoveDestination::class => 'toJson()',
        \Neos\ContentRepository\Core\Feature\NodeMove\Dto\SucceedingSiblingNodeMoveDestination::class => 'toJson()',
        \Neos\ContentRepository\Core\Projection\ContentGraph\CoverageByOrigin::class => 'toJson()',
        \Neos\ContentRepository\Core\Projection\ContentGraph\OriginByCoverage::class => 'toJson()',
        \Neos\ContentRepository\Core\SharedModel\Node\NodeAggregateIds::class => 'toJson()',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        NodeLegacyStub::class => Node::class,
    ]);

    // Should run LAST - as other rules above might create $this->contentRepositoryRegistry calls.
    $rectorConfig->ruleWithConfiguration(InjectServiceIfNeededRector::class, [
        new AddInjection('contentRepositoryRegistry', ContentRepositoryRegistry::class),
        new AddInjection('renderingModeService', RenderingModeService::class),
    ]);
    // TODO: does not fully seem to work.$rectorConfig->rule(RemoveDuplicateCommentRector::class);
};
