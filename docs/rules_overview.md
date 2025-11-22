# 53 Rules Overview

## ContentDimensionCombinatorGetAllAllowedCombinationsRector

`"ContentDimensionCombinator::getAllAllowedCombinations()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\ContentDimensionCombinatorGetAllAllowedCombinationsRector`](../src/ContentRepository90/Rules/ContentDimensionCombinatorGetAllAllowedCombinationsRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Service\ContentDimensionCombinator;

 class SomeClass
 {
     /**
      * @var ContentDimensionCombinator
      * @Flow\Inject
      */
     protected $contentDimensionCombinator;
     public function run()
     {
-        $combinations = $this->contentDimensionCombinator->getAllAllowedCombinations();
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $dimensionSpacePoints = $contentRepository->getVariationGraph()->getDimensionSpacePoints();
+        // TODO 9.0 migration: try to directly work with $dimensionSpacePoints, instead of converting them to the legacy dimension format
+
+        $combinations = array_map(fn(\Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePoint $dimensionSpacePoint) => $dimensionSpacePoint->toLegacyDimensionArray(), iterator_to_array($dimensionSpacePoints));
         foreach ($combinations as $combination) {
         }
     }
 }

 ?>
```

<br>

## ContentRepositoryUtilityRenderValidNodeNameRector

Replaces Utility::renderValidNodeName(...) into NodeName::fromString(...)->value.

- class: [`Neos\Rector\ContentRepository90\Rules\ContentRepositoryUtilityRenderValidNodeNameRector`](../src/ContentRepository90/Rules/ContentRepositoryUtilityRenderValidNodeNameRector.php)

```diff
-\Neos\ContentRepository\Utility::renderValidNodeName('foo');
+\Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString('foo')->value;
```

<br>

## ContextFactoryToLegacyContextStubRector

`"ContextFactory::create()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\ContextFactoryToLegacyContextStubRector`](../src/ContentRepository90/Rules/ContextFactoryToLegacyContextStubRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;

 class SomeClass
 {
     protected ContextFactoryInterface $contextFactory;
-    public function run(string $workspace): Neos\ContentRepository\Domain\Service\Context
+    public function run(string $workspace): \Neos\Rector\ContentRepository90\Legacy\LegacyContextStub
     {
-        return $this->contextFactory->create([
+        return new \Neos\Rector\ContentRepository90\Legacy\LegacyContextStub([
           'workspace' => $workspace,
           'dimensions' => [
             'language' => ['de_DE']
           ]
         ]);
     }

-  public function run2(): Neos\Neos\Domain\Service\ContentContext {
+  public function run2(): \Neos\Rector\ContentRepository90\Legacy\LegacyContextStub {
   }
 }

 ?>
```

<br>

## ContextGetCurrentRenderingModeRector

`"ContentContext::getCurrentRenderingMode()"` will be replaced with `RenderingModeService::findByCurrentUser().`

- class: [`Neos\Rector\ContentRepository90\Rules\ContextGetCurrentRenderingModeRector`](../src/ContentRepository90/Rules/ContextGetCurrentRenderingModeRector.php)

```diff
 <?php

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\Neos\Domain\Service\RenderingModeService $renderingModeService;
     public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
     {
-        $renderingMode = $context->getCurrentRenderingMode();
+        $renderingMode = $this->renderingModeService->findByCurrentUser();
     }
 }

 ?>
```

<br>

## ContextGetFirstLevelNodeCacheRector

`"Context::getFirstLevelNodeCache()"` will be removed.

- class: [`Neos\Rector\ContentRepository90\Rules\ContextGetFirstLevelNodeCacheRector`](../src/ContentRepository90/Rules/ContextGetFirstLevelNodeCacheRector.php)

```diff
 <?php

 class SomeClass
 {
     public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
     {
-        $context->getFirstLevelNodeCache()->reset();
-        $context->getFirstLevelNodeCache()->someOtherMethod();
     }
 }

 ?>
```

<br>

## ContextGetRootNodeRector

`"Context::getRootNode()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\ContextGetRootNodeRector`](../src/ContentRepository90/Rules/ContextGetRootNodeRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;

 class SomeClass
 {
     public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
     {
-        return $context->getRootNode();
+
+        // TODO 9.0 migration: !! MEGA DIRTY CODE! Ensure to rewrite this; by getting rid of LegacyContextStub.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $workspace = $contentRepository->findWorkspaceByName(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($context->workspaceName ?? 'live'));
+        $rootNodeAggregate = $contentRepository->getContentGraph($workspace->workspaceName)->findRootNodeAggregateByType(\Neos\ContentRepository\Core\NodeType\NodeTypeName::fromString('Neos.Neos:Sites'));
+        $subgraph = $contentRepository->getContentGraph($workspace->workspaceName)->getSubgraph(\Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePoint::fromLegacyDimensionArray($context->dimensions ?? []), $context->invisibleContentShown ? \Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints::withoutRestrictions() : \Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints::default());
+        return $subgraph->findNodeById($rootNodeAggregate->nodeAggregateId);
     }
 }

 ?>
```

<br>

## ContextIsInBackendRector

`"ContentContext::isLive()"` will be replaced with `RenderingModeService::findByCurrentUser().`

- class: [`Neos\Rector\ContentRepository90\Rules\ContextIsInBackendRector`](../src/ContentRepository90/Rules/ContextIsInBackendRector.php)

```diff
 <?php

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\Neos\Domain\Service\RenderingModeService $renderingModeService;
     public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
     {
-        $isInBackend = $context->isInBackend();
-        if ($context->isInBackend() && $foo == 'bar') {
+        $isInBackend = $this->renderingModeService->findByCurrentUser()->isEdit;
+        if ($this->renderingModeService->findByCurrentUser()->isEdit && $foo == 'bar') {
             return true;
         }
     }
 }

 ?>
```

<br>

## ContextIsLiveRector

`"ContentContext::isLive()"` will be replaced with `RenderingModeService::findByCurrentUser().`

- class: [`Neos\Rector\ContentRepository90\Rules\ContextIsLiveRector`](../src/ContentRepository90/Rules/ContextIsLiveRector.php)

```diff
 <?php

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\Neos\Domain\Service\RenderingModeService $renderingModeService;
     public function run(\Neos\Rector\ContentRepository90\Legacy\LegacyContextStub $context)
     {
-        $isLive = $context->isLive();
-        if ($context->isLive() && $foo == 'bar') {
+        $isLive = !$this->renderingModeService->findByCurrentUser()->isEdit && !$this->renderingModeService->findByCurrentUser()->isPreview;
+        if (!$this->renderingModeService->findByCurrentUser()->isEdit && !$this->renderingModeService->findByCurrentUser()->isPreview && $foo == 'bar') {
             return true;
         }
     }
 }

 ?>
```

<br>

## InjectServiceIfNeededRector

add injection for `$contentRepositoryRegistry` if in use.

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\InjectServiceIfNeededRector`](../src/Generic/Rules/InjectServiceIfNeededRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\Neos\Domain\Service\RenderingModeService $renderingModeService;
     public function run(Node $node)
     {
         $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
         $currentRenderingMode = $this->renderingModeService->findByCurrentUser();
     }
 }

 ?>
```

<br>

## MethodCallToPropertyFetchRector

Turn method call `$this->getFirstname()` to property fetch `$this->firstname`

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\MethodCallToPropertyFetchRector`](../src/Generic/Rules/MethodCallToPropertyFetchRector.php)

```diff
 class SomeClass
 {
     public function run()
     {
-        $this->getFirstname();
+        $this->firstname;
     }
 }
```

<br>

## MethodCallToWarningCommentRector

"Warning comments for various non-supported use cases

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\MethodCallToWarningCommentRector`](../src/Generic/Rules/MethodCallToWarningCommentRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
+        // TODO 9.0 migration: !! Neos\ContentRepository\Domain\Model\Node::getNode() has been removed.
         $node->getNode();
+        // TODO 9.0 migration: !! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.
         return $node->getWorkspace();
     }
 }

 ?>
```

<br>

## NodeFactoryResetRector

`"NodeFactory::reset()"` will be removed.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeFactoryResetRector`](../src/ContentRepository90/Rules/NodeFactoryResetRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Factory\NodeFactory;

 class SomeClass
 {
     /**
      * @var NodeFactory
      * @Flow\Inject
      */
     protected $nodeFactory;
     public function run()
     {
-        $this->nodeFactory->reset();
-        return $this->nodeFactory->reset();
+        return;
     }
 }

 ?>
```

<br>

## NodeFindParentNodeRector

`"NodeInterface::findParentNode()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeFindParentNodeRector`](../src/ContentRepository90/Rules/NodeFindParentNodeRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        $parentNode = $node->findParentNode();
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        $parentNode = $subgraph->findParentNode($node->aggregateId);
     }
 }

 ?>
```

<br>

## NodeGetChildNodesRector

`"NodeInterface::getChildNodes()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetChildNodesRector`](../src/ContentRepository90/Rules/NodeGetChildNodesRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        foreach ($node->getChildNodes(offset: 100, limit: 10) as $node) {
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        // TODO 9.0 migration: Try to remove the iterator_to_array($nodes) call.
+        foreach (iterator_to_array($subgraph->findChildNodes($node->aggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter::create(pagination: ['limit' => 10, 'offset' => 100]))) as $node) {
         }
     }
 }

 ?>
```

<br>

## NodeGetContextGetWorkspaceNameRector

`"NodeInterface::getContext()::getWorkspaceName()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceNameRector`](../src/ContentRepository90/Rules/NodeGetContextGetWorkspaceNameRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->getContext()->getWorkspaceName();
+        return $node->workspaceName;
     }
 }

 ?>
```

<br>

## NodeGetContextGetWorkspaceRector

`"NodeInterface::getContext()::getWorkspace()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceRector`](../src/ContentRepository90/Rules/NodeGetContextGetWorkspaceRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public Node $node;

     public function getNode(): Node {
         return $this->node;
     }

     public function run(Node $node)
     {
-        $workspace = $this->getNode()->getContext()->getWorkspace();
+        $contentRepository = $this->contentRepositoryRegistry->get($this->getNode()->contentRepositoryId);
+        $workspace = $contentRepository->findWorkspaceByName($this->getNode()->workspaceName);
+        $contentRepository = $this->contentRepositoryRegistry->get($this->node->contentRepositoryId);

-        $workspace = $this->node->getContext()->getWorkspace();
+        $workspace = $contentRepository->findWorkspaceByName($this->node->workspaceName);
+        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);

-        return $node->getContext()->getWorkspace();
+        return $contentRepository->findWorkspaceByName($node->workspaceName);
     }
 }

 ?>
```

<br>

## NodeGetContextPathRector

`"NodeInterface::getContextPath()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetContextPathRector`](../src/ContentRepository90/Rules/NodeGetContextPathRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->getContextPath();
+        return \Neos\ContentRepository\Core\SharedModel\Node\NodeAddress::fromNode($node)->toJson();
     }
 }

 ?>
```

<br>

## NodeGetDepthRector

`"NodeInterface::getDepth()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetDepthRector`](../src/ContentRepository90/Rules/NodeGetDepthRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->getDepth();
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        return $subgraph->findNodePath($node->aggregateId)->getDepth();
     }
 }

 ?>
```

<br>

## NodeGetDimensionsRector

`"NodeInterface::getDimensions()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetDimensionsRector`](../src/ContentRepository90/Rules/NodeGetDimensionsRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        $dimension = $node->getDimensions()[0];
+        // TODO 9.0 migration: Try to remove the toLegacyDimensionArray() call and make your codebase more typesafe.
+        $dimension = $node->originDimensionSpacePoint->toLegacyDimensionArray()[0];

-        $dimensions = MyFoo::do($node->getDimensions());
+        // TODO 9.0 migration: Try to remove the toLegacyDimensionArray() call and make your codebase more typesafe.
+        $dimensions = MyFoo::do($node->originDimensionSpacePoint->toLegacyDimensionArray());

-        return $node->getDimensions();
+        // TODO 9.0 migration: Try to remove the toLegacyDimensionArray() call and make your codebase more typesafe.
+        return $node->originDimensionSpacePoint->toLegacyDimensionArray();
     }
 }

 ?>
```

<br>

## NodeGetHiddenBeforeAfterDateTimeRector

`"NodeInterface::getHiddenBeforeDateTime()",` `"NodeInterface::setHiddenBeforeDateTime()",` `"NodeInterface::getHiddenAfterDateTime()"` and `"NodeInterface::setHiddenAfterDateTime()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetHiddenBeforeAfterDateTimeRector`](../src/ContentRepository90/Rules/NodeGetHiddenBeforeAfterDateTimeRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function nodeHiddenBeforeDateTime(Node $node)
     {
-        $dateTime = $node->getHiddenBeforeDateTime();
+        // TODO 9.0 migration: Timed publishing has been conceptually changed and has been extracted into a dedicated package. Please check https://github.com/neos/timeable-node-visibility for further details.
+        $dateTime = $node->getProperty('enableAfterDateTime');

+        // TODO 9.0 migration: Timed publishing has been conceptually changed and has been extracted into a dedicated package. Please check https://github.com/neos/timeable-node-visibility for further details.
+        // Use the "SetNodeProperties" command to change property values for "enableAfterDateTime" or "disableAfterDateTime".
         $node->setHiddenBeforeDateTime($dateTime);
     }

     public function nodeHiddenAfterDateTime(Node $node)
     {
-        $dateTime = $node->getHiddenAfterDateTime();
+        // TODO 9.0 migration: Timed publishing has been conceptually changed and has been extracted into a dedicated package. Please check https://github.com/neos/timeable-node-visibility for further details.
+        $dateTime = $node->getProperty('disableAfterDateTime');

+        // TODO 9.0 migration: Timed publishing has been conceptually changed and has been extracted into a dedicated package. Please check https://github.com/neos/timeable-node-visibility for further details.
+        // Use the "SetNodeProperties" command to change property values for "enableAfterDateTime" or "disableAfterDateTime".
         $node->setHiddenAfterDateTime($dateTime);
     }
 }

 ?>
```

<br>

## NodeGetIdentifierRector

`"NodeInterface::getIdentifier()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetIdentifierRector`](../src/ContentRepository90/Rules/NodeGetIdentifierRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        $nodeIdentifier = $node->getIdentifier();
+        // TODO 9.0 migration: Check if you could change your code to work with the NodeAggregateId value object instead.
+        $nodeIdentifier = $node->aggregateId->value;
     }
 }

 ?>
```

<br>

## NodeGetNodeTypeGetNameRector

`"NodeInterface::getNodeType()->getName()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetNodeTypeGetNameRector`](../src/ContentRepository90/Rules/NodeGetNodeTypeGetNameRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
         $nodeType = $node->getNodeType();
         $nodeTypeName = $nodeType->getName();

-        $nodeTypeName = $node->getNodeType()->getName();
+        $nodeTypeName = $node->nodeTypeName->value;
     }
 }

 ?>
```

<br>

## NodeGetNodeTypeRector

`"NodeInterface::getNodeType()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetNodeTypeRector`](../src/ContentRepository90/Rules/NodeGetNodeTypeRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        $nodeType = $node->getNodeType();
+        $contentRepository = $this->contentRepositoryRegistry->get($node->contentRepositoryId);
+        $nodeType = $contentRepository->getNodeTypeManager()->getNodeType($node->nodeTypeName);
     }
 }

 ?>
```

<br>

## NodeGetParentRector

`"NodeInterface::getParent()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetParentRector`](../src/ContentRepository90/Rules/NodeGetParentRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->getParent();
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        return $subgraph->findParentNode($node->aggregateId);
     }
 }

 ?>
```

<br>

## NodeGetPathRector

`"NodeInterface::getPath()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetPathRector`](../src/ContentRepository90/Rules/NodeGetPathRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function getNode():Node {
         return new Node();
     }

     public function getPathByNode(Node $node)
     {
-        $path = $node->getPath();
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
+        $path = (string) $subgraph->findNodePath($node->aggregateId);
     }

     public function getPathByGetNode()
     {
-        $path = $this->getNode()->getPath();
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($this->getNode());
+        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
+        $path = (string) $subgraph->findNodePath($this->getNode()->aggregateId);
     }

     public function getPath(Node $node)
     {
-        return $node->getPath();
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
+        return (string) $subgraph->findNodePath($node->aggregateId);
     }

     public function getPathAsParameter(Node $node)
     {
-        $path = MyFoo::do($node->getPath());
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
+        $path = MyFoo::do((string) $subgraph->findNodePath($node->aggregateId));
     }
 }

 ?>
```

<br>

## NodeGetPropertyNamesRector

"$nodeType->allowsGrandchildNodeType($parentNodeName, `$nodeType)"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetPropertyNamesRector`](../src/ContentRepository90/Rules/NodeGetPropertyNamesRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        $propertyNames = $node->getPropertyNames();
+        $propertyNames = array_keys(iterator_to_array($node->properties));
     }
 }

 ?>
```

<br>

## NodeIsAutoCreatedRector

"NodeInterface::isAutoCreated" will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeIsAutoCreatedRector`](../src/ContentRepository90/Rules/NodeIsAutoCreatedRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        $bool = $node->isAutoCreated();
+        $bool = $node->classification->isTethered();
     }
 }

 ?>
```

<br>

## NodeIsHiddenInIndexRector

`"NodeInterface::isHiddenInIndex()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeIsHiddenInIndexRector`](../src/ContentRepository90/Rules/NodeIsHiddenInIndexRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->isHiddenInIndex();
+        return $node->getProperty('hiddenInMenu');
     }
 }

 ?>
```

<br>

## NodeIsHiddenRector

`"NodeInterface::isHidden()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeIsHiddenRector`](../src/ContentRepository90/Rules/NodeIsHiddenRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->isHidden();
+        return $node->tags->contain(\Neos\Neos\Domain\SubtreeTagging\NeosSubtreeTag::disabled());
     }
 }

 ?>
```

<br>

## NodeLabelGeneratorRector

`"$node->getLabel()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeLabelGeneratorRector`](../src/ContentRepository90/Rules/NodeLabelGeneratorRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\Neos\Domain\NodeLabel\NodeLabelGeneratorInterface $nodeLabelGenerator;
     public function run(Node $node)
     {
-        $label = $node->getLabel();
+        $label = $this->nodeLabelGenerator->getLabel($node);
     }
 }

 ?>
```

<br>

## NodeSearchServiceRector

`"NodeSearchService::findDescendantNodes()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeSearchServiceRector`](../src/ContentRepository90/Rules/NodeSearchServiceRector.php)

```diff
 <?php

 namespace Neos\Rector\Test;

 use Neos\ContentRepository\Domain\Model\Node;
 use Neos\ContentRepository\Domain\Service\Context;

 class SomeClass extends AnotherClass
 {
     /**
      * @var \Neos\Neos\Domain\Service\NodeSearchServiceInterface
      */
     private $nodeSearchServiceInterface;

     public function startingPointNodeIsGiven(Node $startingNode, Context $context)
     {
         $term = "term";
         $searchNodeTypes = [];
-        $nodes = $this->nodeSearchServiceInterface->findByProperties($term, $searchNodeTypes, $context, $startingNode);
+
+        // TODO 9.0 migration: This could be a suitable replacement. Please check if all your requirements are still fulfilled.
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($startingNode);
+        $nodes = $subgraph->findDescendantNodes($startingNode->aggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindDescendantNodesFilter::create(nodeTypes: \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\NodeType\NodeTypeCriteria::create(\Neos\ContentRepository\Core\NodeType\NodeTypeNames::fromStringArray($searchNodeTypes), \Neos\ContentRepository\Core\NodeType\NodeTypeNames::createEmpty()), searchTerm: $term));
     }

     public function startingPointNodeIsNotGiven(Context $context)
     {
         $term = "term";
         $searchNodeTypes = [];
-        $nodes = $this->nodeSearchServiceInterface->findByProperties($term, $searchNodeTypes, $context);
+
+        // TODO 9.0 migration: The replacement needs a node as starting point for the search. Please provide a node, to make this replacement working.
+        $node = 'we-need-a-node-here';
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        $nodes = $subgraph->findDescendantNodes($node->aggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindDescendantNodesFilter::create(nodeTypes: \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\NodeType\NodeTypeCriteria::create(\Neos\ContentRepository\Core\NodeType\NodeTypeNames::fromStringArray($searchNodeTypes), \Neos\ContentRepository\Core\NodeType\NodeTypeNames::createEmpty()), searchTerm: $term));
     }
 }
```

<br>

## NodeTypeAllowsGrandchildNodeTypeRector

"$nodeType->allowsGrandchildNodeType($parentNodeName, `$nodeType)"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeAllowsGrandchildNodeTypeRector`](../src/ContentRepository90/Rules/NodeTypeAllowsGrandchildNodeTypeRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;
 use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
     public function run(Node $node)
     {
         $parentNodeName = 'name';
         $nodeType = $node->getNodeType();
         $grandParentsNodeType = $node->getParent()->getParent()->getNodeType();

-        $grandParentsNodeType->allowsGrandchildNodeType($parentNodeName, $nodeType);
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+
+        $contentRepository->getNodeTypeManager()->isNodeTypeAllowedAsChildToTetheredNode($grandParentsNodeType->name, \Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString($parentNodeName), $nodeType->name);
     }
 }

 ?>
```

<br>

## NodeTypeGetAutoCreatedChildNodesRector

`"$nodeType->getAutoCreatedChildNodes()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeGetAutoCreatedChildNodesRector`](../src/ContentRepository90/Rules/NodeTypeGetAutoCreatedChildNodesRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
         $nodeType = $node->getNodeType();
-        $childNodes = $nodeType->getAutoCreatedChildNodes();
+        // TODO 9.0 migration: NodeType::tetheredNodeTypeDefinitions() is not a 1:1 replacement of NodeType::getAutoCreatedChildNodes(). You need to change your code to work with new TetheredNodeTypeDefinition object.
+        $childNodes = $nodeType->tetheredNodeTypeDefinitions;
     }
 }

 ?>
```

<br>

## NodeTypeGetNameRector

`"NodeType::getName()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeGetNameRector`](../src/ContentRepository90/Rules/NodeTypeGetNameRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\NodeType\NodeType;

 class SomeClass
 {
     public function run(NodeType $nodetype)
     {
-        $nodetype = $nodetype->getName();
+        $nodetype = $nodetype->name->value;
     }
 }

 ?>
```

<br>

## NodeTypeGetTypeOfAutoCreatedChildNodeRector

"$nodeType->getTypeOfAutoCreatedChildNode($nodeName)" will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeGetTypeOfAutoCreatedChildNodeRector`](../src/ContentRepository90/Rules/NodeTypeGetTypeOfAutoCreatedChildNodeRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Model\Node;
 use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
     public function run(Node $node)
     {
         $nodeName = NodeName::fromString('name');
         $nodeType = $node->getNodeType();
-        $type = $nodeType->getTypeOfAutoCreatedChildNode($nodeName);
+
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories. If you have a Node object around you can use $node->contentRepositoryId.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $type = $contentRepository->getNodeTypeManager()->getNodeType($nodeType->tetheredNodeTypeDefinitions->get($nodeName));
     }
 }

 ?>
```

<br>

## NodeTypeManagerAccessRector

"$this->nodeTypeManager" will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeManagerAccessRector`](../src/ContentRepository90/Rules/NodeTypeManagerAccessRector.php)

```diff
 <?php

 class SomeClass
 {
     /**
      * @var \Neos\ContentRepository\Domain\Service\NodeTypeManager
      * @Flow\Inject
      */
     protected $nodeTypeManager;
     public function run()
     {
-        $nt = $this->nodeTypeManager->getNodeTypes(false);
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $nt = $contentRepository->getNodeTypeManager()->getNodeTypes(false);
     }
 }

 ?>
```

<br>

## ObjectInstantiationToWarningCommentRector

"Warning comments for various non-supported signals

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\ObjectInstantiationToWarningCommentRector`](../src/Generic/Rules/ObjectInstantiationToWarningCommentRector.php)

```diff
 <?php

 use Neos\Flow\Package\Package as BasePackage;

 class Package extends BasePackage
 {
     public function boot()
     {
+        // TODO 9.0 migration: This is a comment
         $myComment = new \My\Class\To\Comment();
     }
 }

 ?>
```

<br>

## RemoveInjectionsRector

Remove properties marked with a @Flow\Inject annotation and a certain type

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\RemoveInjectionsRector`](../src/Generic/Rules/RemoveInjectionsRector.php)

```diff
 <?php

 use \Foo\Bar\Baz;
 use Neos\Flow\Annotations as Flow;

 class SomeClass
 {
   /**
-   * @Flow\Inject
-   * @var \Foo\Bar\Baz
-   */
-  protected $foo = null;
-
-  /**
    * @var Baz
    */
   protected $foo2 = null;

-  /**
-   * @Flow\Inject
-   * @var Baz
-   */
-  protected $foo3 = null;
-
   protected \Foo\Bar\Baz $foo4;
-
-  #[Flow\Inject]
-  protected \Foo\Bar\Baz $foo5;
-
-  #[Flow\Inject]
-  protected Baz $foo6;
 }

 ?>
```

<br>

## RemoveParentClassRector

Remove "extends BLABLA" from classes

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\RemoveParentClassRector`](../src/Generic/Rules/RemoveParentClassRector.php)

```diff
 <?php

-class SomeClass extends \Foo\Bar\Baz
+// TODO: Neos 9.0 Migration: Stuff
+class SomeClass
 {
 }

 ?>
```

<br>

## SignalSlotToWarningCommentRector

"Warning comments for various non-supported signals

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\SignalSlotToWarningCommentRector`](../src/Generic/Rules/SignalSlotToWarningCommentRector.php)

```diff
 <?php

 use Neos\Flow\Core\Bootstrap;
 use Neos\Flow\Package\Package as BasePackage;
 use Neos\Flow\SignalSlot\Dispatcher;
 use Neos\ContentRepository\Domain\Model\Node;

 class Package extends BasePackage
 {
     public function boot(Bootstrap $bootstrap)
     {
         /** @var Dispatcher $dispatcher */
         $dispatcher = $bootstrap->getSignalSlotDispatcher();

+        // TODO 9.0 migration: Signal "beforeMove" doesn't exist anymore
         $dispatcher->connect(
             Node::class,
             'beforeMove',
             SomeOtherClass::class,
             'someMethod'
         );

+        // TODO 9.0 migration: Signal "afterMove" doesn't exist anymore
         $dispatcher->connect(
             'Neos\ContentRepository\Domain\Model\Node',
             'afterMove',
             SomeOtherClass::class,
             'someMethod'
         );

         $dispatcher->connect(
             Node::class,
             'otherMethod',
             SomeOtherClass::class,
             'someMethod'
         );

         $dispatcher->connect(
             OtherClass::class,
             'afterMove',
             SomeOtherClass::class,
             'someMethod'
         );
     }
 }

 ?>
```

<br>

## ToStringToMethodCallOrPropertyFetchRector

Turns defined code uses of `"__toString()"` method to specific method calls or property fetches.

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\ToStringToMethodCallOrPropertyFetchRector`](../src/Generic/Rules/ToStringToMethodCallOrPropertyFetchRector.php)

```diff
 $someValue = new SomeObject;
-$result = (string) $someValue;
-$result = $someValue->__toString();
+$result = $someValue->getPath();
+$result = $someValue->getPath();
```

<br>

## WorkspaceGetBaseWorkspaceRector

`"Workspace::getBaseWorkspace()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceGetBaseWorkspaceRector`](../src/ContentRepository90/Rules/WorkspaceGetBaseWorkspaceRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

 class SomeClass
 {
     public function run(Workspace $workspace)
     {
-        $baseWorkspace = $workspace->getBaseWorkspace();

-        return $workspace->getBaseWorkspace();
+        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead and make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $baseWorkspace = $contentRepository->findWorkspaceByName($workspace->baseWorkspaceName);
+
+        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead and make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+
+        return $contentRepository->findWorkspaceByName($workspace->baseWorkspaceName);
     }
 }

 ?>
```

<br>

## WorkspaceGetBaseWorkspacesRector

`"Workspace::getBaseWorkspaces()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceGetBaseWorkspacesRector`](../src/ContentRepository90/Rules/WorkspaceGetBaseWorkspacesRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

 class SomeClass
 {
     public function run(Workspace $workspace)
     {
-        $baseWorkspaces = $workspace->getBaseWorkspaces();

-        return $workspace->getBaseWorkspaces();
+        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead and make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $baseWorkspaces = $contentRepository->findWorkspaces()->getBaseWorkspaces($workspace->workspaceName);
+
+        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead and make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+
+        return $contentRepository->findWorkspaces()->getBaseWorkspaces($workspace->workspaceName);
     }
 }

 ?>
```

<br>

## WorkspaceGetDescriptionRector

`"Workspace::getDescription()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceGetDescriptionRector`](../src/ContentRepository90/Rules/WorkspaceGetDescriptionRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

 class SomeClass
 {
     public function run(Workspace $workspace)
     {
-        $description = $workspace->getDescription();
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $description = $this->workspaceService->getWorkspaceMetadata(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName)->description->value;
     }
 }

 ?>
```

<br>

## WorkspaceGetNameRector

`"Workspace::getName()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceGetNameRector`](../src/ContentRepository90/Rules/WorkspaceGetNameRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

 class SomeClass
 {
     public function run(Workspace $workspace)
     {
-        $workspaceName = $workspace->getName();
+        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead.
+        $workspaceName = $workspace->workspaceName->value;
     }
 }

 ?>
```

<br>

## WorkspaceGetTitleRector

`"Workspace::getTitle()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceGetTitleRector`](../src/ContentRepository90/Rules/WorkspaceGetTitleRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

 class SomeClass
 {
     public function run(Workspace $workspace)
     {
-        $title = $workspace->getTitle();
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $title = $this->workspaceService->getWorkspaceMetadata(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName)->title->value;
     }
 }

 ?>
```

<br>

## WorkspacePublishNodeRector

`"Workspace::publishNode()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspacePublishNodeRector`](../src/ContentRepository90/Rules/WorkspacePublishNodeRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;
 use Neos\ContentRepository\Domain\Model\NodeInterface;

 class SomeClass
 {
     public function run(Workspace $workspace, NodeInterface $node)
     {
-        $workspace->publishNode($node);
+        // TODO 9.0 migration: Check if this matches your requirements as this is not a 100% replacement. Make this code aware of multiple Content Repositories.
+        $this->workspacePublishingService->publishChangesInDocument(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, $node);
     }
 }

 ?>
```

<br>

## WorkspacePublishRector

`"Workspace::publish()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspacePublishRector`](../src/ContentRepository90/Rules/WorkspacePublishRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

 class SomeClass
 {
     public function run(Workspace $workspace)
     {
-        $workspace->publish();
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $this->workspacePublishingService->publishWorkspace(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName);
     }
 }

 ?>
```

<br>

## WorkspaceRepositoryCountByNameRector

`"WorkspaceRepository::countByName()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryCountByNameRector`](../src/ContentRepository90/Rules/WorkspaceRepositoryCountByNameRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
 use Neos\Flow\Annotations as Flow;

 class SomeClass
 {
     /**
      * @var WorkspaceRepository
      * @Flow\Inject
      */
     protected $workspaceRepository;
     public function run(string $workspace)
     {
-        return $this->workspaceRepository->countByName($workspace);
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        // TODO 9.0 migration: remove ternary operator (...? 1 : 0 ) - unnecessary complexity
+        return $contentRepository->findWorkspaceByName(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($workspace)) !== null ? 1 : 0;
     }
 }

 ?>
```

<br>

## WorkspaceRepositoryFindByBaseWorkspaceRector

`"WorkspaceRepository::findByBaseWorkspace()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryFindByBaseWorkspaceRector`](../src/ContentRepository90/Rules/WorkspaceRepositoryFindByBaseWorkspaceRector.php)

```diff
 <?php

 namespace Neos\Rector\Test;

 use Neos\Flow\Annotations as Flow;

 class SomeClass extends AnotherClass
 {
     /**
      * @Flow\Inject
      * @var \Neos\ContentRepository\Domain\Repository\WorkspaceRepository
      */
     private $workspaceRepository;

     public function findWorkspace($workspaceIdentifier)
     {
-        $dependentWorkspaces = $this->workspaceRepository->findByBaseWorkspace($workspaceIdentifier);
+
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $dependentWorkspaces = $contentRepository->findWorkspaces()->getDependantWorkspaces(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($workspaceIdentifier));
     }
 }
```

<br>

## WorkspaceRepositoryFindByIdentifierRector

`"WorkspaceRepository::findByIdentifier()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryFindByIdentifierRector`](../src/ContentRepository90/Rules/WorkspaceRepositoryFindByIdentifierRector.php)

```diff
 <?php

 namespace Neos\Rector\Test;

 use Neos\Flow\Annotations as Flow;

 class SomeClass extends AnotherClass
 {
     /**
      * @Flow\Inject
      * @var \Neos\ContentRepository\Domain\Repository\WorkspaceRepository
      */
     private $workspaceRepository;

     public function findWorkspace($workspaceIdentifier)
     {
-        $workspace = $this->workspaceRepository->findByIdentifier($workspaceIdentifier);
+
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $workspace = $contentRepository->findWorkspaceByName(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($workspaceIdentifier));
     }
 }
```

<br>

## WorkspaceSetDescriptionRector

`"Workspace::setDescription()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceSetDescriptionRector`](../src/ContentRepository90/Rules/WorkspaceSetDescriptionRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

 class SomeClass
 {
     public function run(Workspace $workspace)
     {
-        $workspace->setDescription("description");
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $this->workspaceService->setWorkspaceDescription(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, \Neos\Neos\Domain\Model\WorkspaceDescription::fromString("description"));
     }

     public function get(Workspace $workspace)
     {
-        return $workspace->setDescription("description");
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        return $this->workspaceService->setWorkspaceDescription(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, \Neos\Neos\Domain\Model\WorkspaceDescription::fromString("description"));
     }
 }

 ?>
```

<br>

## WorkspaceSetTitleRector

`"Workspace::setTitle()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceSetTitleRector`](../src/ContentRepository90/Rules/WorkspaceSetTitleRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\SharedModel\Workspace\Workspace;

 class SomeClass
 {
     public function run(Workspace $workspace)
     {
-        $workspace->setTitle("title");
-        $bar = $workspace->setTitle("title");
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $this->workspaceService->setWorkspaceTitle(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, \Neos\Neos\Domain\Model\WorkspaceTitle::fromString("title"));
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $bar = $this->workspaceService->setWorkspaceTitle(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, \Neos\Neos\Domain\Model\WorkspaceTitle::fromString("title"));

-        $foo = $this->getWorkspace()->setTitle("title");
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $foo = $this->workspaceService->setWorkspaceTitle(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $this->getWorkspace()->workspaceName, \Neos\Neos\Domain\Model\WorkspaceTitle::fromString("title"));
     }

     public function get(Workspace $workspace)
     {
-        return $workspace->setTitle("title");
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        return $this->workspaceService->setWorkspaceTitle(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'), $workspace->workspaceName, \Neos\Neos\Domain\Model\WorkspaceTitle::fromString("title"));
     }

     public function getWorkspace(): Workspace {
         return Workspace::create('default');
     }
 }

 ?>
```

<br>
