<?php
declare (strict_types=1);

use Neos\Rector\ContentRepository90\Legacy\LegacyContextStub;
use Neos\Rector\ContentRepository90\Rules\ContentDimensionCombinatorGetAllAllowedCombinationsRector;
use Neos\Rector\ContentRepository90\Rules\ContextFactoryToLegacyContextStubRector;
use Neos\Rector\ContentRepository90\Rules\ContextGetFirstLevelNodeCacheRector;
use Neos\Rector\ContentRepository90\Rules\ContextGetRootNodeRector;
use Neos\Rector\ContentRepository90\Rules\FusionContextInBackendRector;
use Neos\Rector\ContentRepository90\Rules\FusionContextLiveRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeDepthRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodeParentRector;
use Neos\Rector\ContentRepository90\Rules\FusionNodePathRector;
use Neos\Rector\ContentRepository90\Rules\InjectContentRepositoryRegistryIfNeededRector;
use Neos\Rector\ContentRepository90\Rules\NodeFactoryResetRector;
use Neos\Rector\ContentRepository90\Rules\NodeFindParentNodeRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetChildNodesRector;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceNameRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetDepthRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetDimensionsRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetParentRector;
use Neos\Rector\ContentRepository90\Rules\NodeGetPathRector;
use Neos\Rector\ContentRepository90\Rules\NodeIsHiddenRector;
use Neos\Rector\Generic\Rules\FusionNodePropertyPathToWarningCommentRector;
use Neos\Rector\Generic\Rules\MethodCallToWarningCommentRector;
use Neos\Rector\Generic\Rules\RemoveDuplicateCommentRector;
use Neos\Rector\Generic\Rules\RemoveInjectionsRector;
use Neos\Rector\Generic\ValueObject\FusionNodePropertyPathToWarningComment;
use Neos\Rector\Generic\ValueObject\MethodCallToWarningComment;
use Neos\Rector\Generic\ValueObject\RemoveInjection;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Transform\Rector\MethodCall\MethodCallToPropertyFetchRector;
use Rector\Transform\ValueObject\MethodCallToPropertyFetch;

return static function (RectorConfig $rectorConfig): void {
    // Register FusionFileProcessor. All Fusion Rectors will be auto-registered at this processor.
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();
    $services->set(\Neos\Rector\Core\FusionProcessing\FusionFileProcessor::class);
    $rectorConfig->disableParallel(); // parallel does not work for non-PHP-Files, so we need to disable it - see https://github.com/rectorphp/rector-src/pull/2597#issuecomment-1190120688


    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Neos\\ContentRepository\\Domain\\Model\\NodeInterface' => Node::class,
        'Neos\\ContentRepository\\Domain\\Projection\\Content\\NodeInterface' => Node::class,
        'Neos\\ContentRepository\\Domain\\Projection\\Content\\TraversableNodeInterface' => Node::class,

        'Neos\ContentRepository\Domain\Service\Context' => LegacyContextStub::class,
        'Neos\Neos\Domain\Service\ContentContext' => LegacyContextStub::class,

        'Neos\ContentRepository\Domain\Model\NodeType' => \Neos\ContentRepository\Core\NodeType\NodeType::class,
        'Neos\ContentRepository\Domain\Service\NodeTypeManager' => \Neos\ContentRepository\Core\NodeType\NodeTypeManager::class
    ]);


    /** @var $methodCallToPropertyFetches MethodCallToPropertyFetch[] */
    $methodCallToPropertyFetches = [];

    /** @var $methodCallToWarningComments MethodCallToWarningComment[] */
    $methodCallToWarningComments = [];


    /**
     * Neos\ContentRepository\Domain\Model\NodeInterface
     */
    // setName
    // getName
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(Node::class, 'getName', 'nodeName');
    // getLabel -> compatible with ES CR node (nothing to do)
    // setProperty
    // hasProperty -> compatible with ES CR Node (nothing to do)
    // getProperty -> compatible with ES CR Node (nothing to do)
    // removeProperty
    // getProperties -> PropertyCollectionInterface
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(Node::class, 'getProperties', 'properties');
    // getPropertyNames
    // setContentObject -> DEPRECATED / NON-FUNCTIONAL
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'setContentObject', '!! Node::setContentObject() is not supported by the new CR. Referencing objects can be done by storing them in Node::properties (and the serialization/deserialization is extensible).');
    // getContentObject -> DEPRECATED / NON-FUNCTIONAL
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'getContentObject', '!! Node::getContentObject() is not supported by the new CR. Referencing objects can be done by storing them in Node::properties (and the serialization/deserialization is extensible).');
    // unsetContentObject -> DEPRECATED / NON-FUNCTIONAL
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'unsetContentObject', '!! Node::unsetContentObject() is not supported by the new CR. Referencing objects can be done by storing them in Node::properties (and the serialization/deserialization is extensible).');
    // setNodeType
    // getNodeType: NodeType
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(Node::class, 'getNodeType', 'nodeType');
    // setHidden
    // isHidden
    $rectorConfig->rule(NodeIsHiddenRector::class);
        // TODO: Fusion NodeAccess
    // setHiddenBeforeDateTime
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'setHiddenBeforeDateTime', '!! Node::setHiddenBeforeDateTime() is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.');
    // getHiddenBeforeDateTime
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'getHiddenBeforeDateTime', '!! Node::getHiddenBeforeDateTime() is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.');
    $fusionNodePropertyPathToWarningComments[] = new FusionNodePropertyPathToWarningComment('getHiddenBeforeDateTime', 'Line %LINE: !! node.getHiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.');
    // setHiddenAfterDateTime
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'setHiddenAfterDateTime', '!! Node::setHiddenAfterDateTime() is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.');
    // getHiddenAfterDateTime
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'getHiddenAfterDateTime', '!! Node::getHiddenAfterDateTime() is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.');
    $fusionNodePropertyPathToWarningComments[] = new FusionNodePropertyPathToWarningComment('getHiddenAfterDateTime', 'Line %LINE: !! node.getHiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.');
    // setHiddenInIndex
    // isHiddenInIndex
        // ToDo PHP Node::properties['_isHiddenInIndex']
        // ToDo Fusion --> node.properties._isHiddenInIndex
    // setAccessRoles
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'setAccessRoles', '!! Node::setAccessRoles() is not supported by the new CR.');
    // getAccessRoles
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'getAccessRoles', '!! Node::getAccessRoles() is not supported by the new CR.');
    // getPath
    $rectorConfig->rule(NodeGetPathRector::class);
    // Fusion: .depth -> Neos.NodeAccess.depth(node)
    $rectorConfig->rule(FusionNodePathRector::class);
    // getContextPath
        // TODO: PHP
        // TODO: Fusion
        // - NodeAddress + LOG (WARNING)
    // getDepth
    $rectorConfig->rule(NodeGetDepthRector::class);
    // Fusion: .depth -> Neos.NodeInfo.depth(node)
    $rectorConfig->rule(FusionNodeDepthRector::class);
    // setWorkspace -> internal
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'setWorkspace', '!! Node::setWorkspace() was always internal, and the workspace system has been fundamentally changed with the new CR. Try to rewrite your code around Content Streams.');
    // getWorkspace
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'getWorkspace', '!! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.');
    // getIdentifier
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(Node::class, 'getIdentifier', 'nodeAggregateId');
    // setIndex -> internal
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'setIndex', '!! Node::setIndex() was always internal. To reorder nodes, use the "MoveNodeAggregate" command');
    // getIndex
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'getIndex', '!! Node::getIndex() is not supported. You can fetch all siblings and inspect the ordering');
    // getParent -> Node
    $rectorConfig->rule(NodeGetParentRector::class);
    // Fusion: .parent -> Neos.NodeAccess.findParent(node)
    $rectorConfig->rule(FusionNodeParentRector::class);
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
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'isRemoved', '!! Node::isRemoved() - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.');
    $fusionNodePropertyPathToWarningComments[] = new FusionNodePropertyPathToWarningComment('isRemoved', 'Line %LINE: !! node.isRemoved - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.');
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
    $methodCallToWarningComments[] = new MethodCallToWarningComment(Node::class, 'getNodeData', '!! Node::getNodeData() - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.');
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
    // getOtherNodeVariants()

    /**
     * Neos\ContentRepository\Domain\Projection\Content\NodeInterface
     */
    // isRoot()
    // isTethered()
    // getContentStreamIdentifier() -> threw exception in <= Neos 8.0 - so nobody could have used this
    // getNodeAggregateIdentifier()
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(Node::class, 'getNodeAggregateIdentifier', 'nodeAggregateId');
        // TODO: Fusion
    // getNodeTypeName()
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(Node::class, 'getNodeTypeName', 'nodeTypeName');
    // getNodeType() ** (included/compatible in old NodeInterface)
    // getNodeName()
    $methodCallToPropertyFetches[] = new MethodCallToPropertyFetch(Node::class, 'getNodeName', 'nodeName');
    // getOriginDimensionSpacePoint() -> threw exception in <= Neos 8.0 - so nobody could have used this
    // getProperties() ** (included/compatible in old NodeInterface)
    // getProperty() ** (included/compatible in old NodeInterface)
    // hasProperty() ** (included/compatible in old NodeInterface)
    // getLabel() ** (included/compatible in old NodeInterface)

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
    // TODO: PHP
    // TODO: Fusion
    // ContentContext::getCurrentDomain
    // TODO: PHP
    // TODO: Fusion
    // ContentContext::getCurrentSiteNode
    // TODO: PHP
    // TODO: Fusion
    // ContentContext::isLive -> Neos.Ui.NodeInfo.isLive(...) (TODO - should this be part of Neos.Ui or Neos Namespace?)
    // TODO: PHP
    $rectorConfig->rule(FusionContextLiveRector::class);
    // ContentContext::isInBackend -> Neos.Ui.NodeInfo.inBackend(...) (TODO - should this be part of Neos.Ui or Neos Namespace?)
    // TODO: PHP
    $rectorConfig->rule(FusionContextInBackendRector::class);
    // ContentContext::getCurrentRenderingMode
    // TODO: PHP
    // TODO: Fusion

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
     * CLEAN UP / END GLOBAL RULES
     */
    $rectorConfig->ruleWithConfiguration(MethodCallToPropertyFetchRector::class, $methodCallToPropertyFetches);
    $rectorConfig->ruleWithConfiguration(MethodCallToWarningCommentRector::class, $methodCallToWarningComments);
    $rectorConfig->ruleWithConfiguration(FusionNodePropertyPathToWarningCommentRector::class, $fusionNodePropertyPathToWarningComments);

    // Remove injections to classes which are gone now
    $rectorConfig->ruleWithConfiguration(RemoveInjectionsRector::class, [
        new RemoveInjection(\Neos\ContentRepository\Domain\Service\ContextFactoryInterface::class),
        new RemoveInjection(\Neos\ContentRepository\Domain\Service\ContentDimensionCombinator::class),
        new RemoveInjection(\Neos\ContentRepository\Domain\Factory\NodeFactory::class)
    ]);

    // Should run LAST - as other rules above might create $this->contentRepositoryRegistry calls.
    $rectorConfig->rule(InjectContentRepositoryRegistryIfNeededRector::class);
    // TODO: does not fully seem to work.$rectorConfig->rule(RemoveDuplicateCommentRector::class);
};
