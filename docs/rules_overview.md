# 34 Rules Overview

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
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\Factory\ContentRepositoryId::fromString('default'));
+        $dimensionSpacePoints = $contentRepository->getInterDimensionalVariationGraph()->getDimensionSpacePoints();
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
+        // TODO 9.0 migration: !! MEGA DIRTY CODE! Ensure to rewrite this; by getting rid of LegacyContextStub.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\Factory\ContentRepositoryId::fromString('default'));
+        $workspace = $contentRepository->getWorkspaceFinder()->findOneByName(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($context->workspaceName ?? 'live'));
+        $rootNodeAggregate = $contentRepository->getContentGraph()->findRootNodeAggregateByType($workspace->currentContentStreamId, \Neos\ContentRepository\Core\NodeType\NodeTypeName::fromString('Neos.Neos:Sites'));
+        $subgraph = $contentRepository->getContentGraph()->getSubgraph($workspace->currentContentStreamId, \Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePoint::fromLegacyDimensionArray($context->dimensions ?? []), $context->invisibleContentShown ? \Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints::withoutRestrictions() : \Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints::frontend());
+        return $subgraph->findNodeById($rootNodeAggregate->nodeAggregateId);
     }
 }

 ?>
```

<br>

## FusionCachingNodeInEntryIdentifierRector

Fusion: Rewrite node to Neos.Caching.entryIdentifierForNode(...) in @cache.entryIdentifier segments

- class: [`Neos\Rector\ContentRepository90\Rules\FusionCachingNodeInEntryIdentifierRector`](../src/ContentRepository90/Rules/FusionCachingNodeInEntryIdentifierRector.php)

```diff
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {
     @cache {
       entryIdentifier {
-        foo = ${node}
+        foo = ${Neos.Caching.entryIdentifierForNode(node)}
       }
     }
-    @cache.entryIdentifier.foo2 = ${documentNode}
+    @cache.entryIdentifier.foo2 = ${Neos.Caching.entryIdentifierForNode(documentNode)}
     @cache {
-      entryIdentifier.foo3 = ${site}
+      entryIdentifier.foo3 = ${Neos.Caching.entryIdentifierForNode(site)}
       entryIdentifier.foo4 = ${someOtherObject}
     }
   }
 }
```

<br>

## FusionContextCurrentSiteRector

Fusion: Rewrite node.context.inBackend to Neos.Node.inBackend(...)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextCurrentSiteRector`](../src/ContentRepository90/Rules/FusionContextCurrentSiteRector.php)

```diff
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {
   renderer = Neos.Fusion:Component {
-    attributes = ${node.context.currentSite.siteResourcesPackageKey || site.context.currentSite.siteResourcesPackageKey || documentNode.context.currentSite.siteResourcesPackageKey}
+    attributes = ${Neos.Site.findBySiteNode(site).siteResourcesPackageKey || Neos.Site.findBySiteNode(site).siteResourcesPackageKey || Neos.Site.findBySiteNode(site).siteResourcesPackageKey}
     renderer = afx`
       <input
-        name={node.context.currentSite.siteResourcesPackageKey}
-        value={someOtherVariable.context.currentSite.siteResourcesPackageKey}
-        {...node.context.currentSite.siteResourcesPackageKey}
+        name={Neos.Site.findBySiteNode(site).siteResourcesPackageKey}
+        value={Neos.Site.findBySiteNode(site).siteResourcesPackageKey}
+        {...Neos.Site.findBySiteNode(site).siteResourcesPackageKey}
       />
     `
   }
 }
```

<br>

## FusionContextInBackendRector

Fusion: Rewrite node.context.inBackend to Neos.Node.inBackend(...)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextInBackendRector`](../src/ContentRepository90/Rules/FusionContextInBackendRector.php)

```diff
+// TODO 9.0 migration: Line 26: You very likely need to rewrite "VARIABLE.context.inBackend" to Neos.Node.inBackend(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.context.inBackend || site.context.inBackend || documentNode.context.inBackend}
+    attributes = ${Neos.Node.inBackend(node) || Neos.Node.inBackend(site) || Neos.Node.inBackend(documentNode)}

     #
     # the `checked` state is calculated outside the renderer to allow` overriding via `attributes`
     #
     checked = false
     checked.@process.checkMultiValue = ${Array.indexOf(field.getCurrentMultivalueStringified(), field.getTargetValueStringified()) > -1}
     checked.@process.checkMultiValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkMultiValue.@if.isMultiple = ${field.isMultiple()}
     checked.@process.checkSingleValue = ${field.getCurrentValueStringified() == field.getTargetValueStringified()}
     checked.@process.checkSingleValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkSingleValue.@if.isSingle = ${!field.isMultiple()}

     renderer = afx`
       <input
         type="checkbox"
-        name={node.context.inBackend}
+        name={Neos.Node.inBackend(node)}
         value={someOtherVariable.context.inBackend}
         checked={props.checked}
-        {...node.context.inBackend}
+        {...Neos.Node.inBackend(node)}
       />
     `
   }
 }
```

<br>

## FusionContextLiveRector

Fusion: Rewrite node.context.live to Neos.Node.isLive(...)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextLiveRector`](../src/ContentRepository90/Rules/FusionContextLiveRector.php)

```diff
+// TODO 9.0 migration: Line 26: You very likely need to rewrite "VARIABLE.context.live" to Neos.Node.isLive(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.context.live || site.context.live || documentNode.context.live}
+    attributes = ${Neos.Node.isLive(node) || Neos.Node.isLive(site) || Neos.Node.isLive(documentNode)}

     #
     # the `checked` state is calculated outside the renderer to allow` overriding via `attributes`
     #
     checked = false
     checked.@process.checkMultiValue = ${Array.indexOf(field.getCurrentMultivalueStringified(), field.getTargetValueStringified()) > -1}
     checked.@process.checkMultiValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkMultiValue.@if.isMultiple = ${field.isMultiple()}
     checked.@process.checkSingleValue = ${field.getCurrentValueStringified() == field.getTargetValueStringified()}
     checked.@process.checkSingleValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkSingleValue.@if.isSingle = ${!field.isMultiple()}

     renderer = afx`
       <input
         type="checkbox"
-        name={node.context.live}
+        name={Neos.Node.isLive(node)}
         value={someOtherVariable.context.live}
         checked={props.checked}
-        {...node.context.live}
+        {...Neos.Node.isLive(node)}
       />
     `
   }
 }
```

<br>

## FusionNodeAggregateIdentifierRector

Fusion: Rewrite node.nodeAggregateIdentifier to node.nodeAggregateId.value

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeAggregateIdentifierRector`](../src/ContentRepository90/Rules/FusionNodeAggregateIdentifierRector.php)

```diff
+// TODO 9.0 migration: Line 13: You may need to rewrite "VARIABLE.nodeAggregateIdentifier" to VARIABLE.nodeAggregateId.value. We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.nodeAggregateIdentifier || documentNode.nodeAggregateIdentifier}
+    attributes = ${node.nodeAggregateId.value || documentNode.nodeAggregateId.value}
     renderer = afx`
       <input
-        name={node.nodeAggregateIdentifier}
+        name={node.nodeAggregateId.value}
         value={someOtherVariable.nodeAggregateIdentifier}
-        {...node.nodeAggregateIdentifier}
+        {...node.nodeAggregateId.value}
       />
     `
   }
 }
```

<br>

## FusionNodeDepthRector

Fusion: Rewrite node.depth to Neos.Node.depth(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeDepthRector`](../src/ContentRepository90/Rules/FusionNodeDepthRector.php)

```diff
+// TODO 9.0 migration: Line 26: You may need to rewrite "VARIABLE.depth" to Neos.Node.depth(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.depth || documentNode.depth}
+    attributes = ${Neos.Node.depth(node) || Neos.Node.depth(documentNode)}

     #
     # the `checked` state is calculated outside the renderer to allow` overriding via `attributes`
     #
     checked = false
     checked.@process.checkMultiValue = ${Array.indexOf(field.getCurrentMultivalueStringified(), field.getTargetValueStringified()) > -1}
     checked.@process.checkMultiValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkMultiValue.@if.isMultiple = ${field.isMultiple()}
     checked.@process.checkSingleValue = ${field.getCurrentValueStringified() == field.getTargetValueStringified()}
     checked.@process.checkSingleValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkSingleValue.@if.isSingle = ${!field.isMultiple()}

     renderer = afx`
       <input
         type="checkbox"
-        name={node.depth}
+        name={Neos.Node.depth(node)}
         value={someOtherVariable.depth}
         checked={props.checked}
-        {...node.depth}
+        {...Neos.Node.depth(node)}
       />
     `
   }
 }
```

<br>

## FusionNodeHiddenInIndexRector

Fusion: Rewrite node.hiddenInIndex to node.properties._hiddenInIndex

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenInIndexRector`](../src/ContentRepository90/Rules/FusionNodeHiddenInIndexRector.php)

```diff
+// TODO 9.0 migration: Line 26: You may need to rewrite "VARIABLE.hiddenInIndex" to VARIABLE.properties._hiddenInIndex. We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.hiddenInIndex || documentNode.hiddenInIndex || site.hiddenInIndex}
+    attributes = ${node.properties._hiddenInIndex || documentNode.properties._hiddenInIndex || site.properties._hiddenInIndex}

     #
     # the `checked` state is calculated outside the renderer to allow` overriding via `attributes`
     #
     checked = false
     checked.@process.checkMultiValue = ${Array.indexOf(field.getCurrentMultivalueStringified(), field.getTargetValueStringified()) > -1}
     checked.@process.checkMultiValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkMultiValue.@if.isMultiple = ${field.isMultiple()}
     checked.@process.checkSingleValue = ${field.getCurrentValueStringified() == field.getTargetValueStringified()}
     checked.@process.checkSingleValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkSingleValue.@if.isSingle = ${!field.isMultiple()}

     renderer = afx`
       <input
         type="checkbox"
-        name={node.hiddenInIndex}
+        name={node.properties._hiddenInIndex}
         value={someOtherVariable.hiddenInIndex}
         checked={props.checked}
-        {...node.hiddenInIndex}
+        {...node.properties._hiddenInIndex}
       />
     `
   }
 }
```

<br>

## FusionNodeIdentifierRector

Fusion: Rewrite node.identifier to node.nodeAggregateId.value

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeIdentifierRector`](../src/ContentRepository90/Rules/FusionNodeIdentifierRector.php)

```diff
+// TODO 9.0 migration: Line 13: You may need to rewrite "VARIABLE.identifier" to VARIABLE.nodeAggregateId.value. We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.identifier || documentNode.identifier}
+    attributes = ${node.nodeAggregateId.value || documentNode.nodeAggregateId.value}
     renderer = afx`
       <input
-        name={node.identifier}
+        name={node.nodeAggregateId.value}
         value={someOtherVariable.identifier}
-        {...node.identifier}
+        {...node.nodeAggregateId.value}
       />
     `
   }
 }
```

<br>

## FusionNodeParentRector

Fusion: Rewrite node.parent to Neos.NodeAccess.findParent(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeParentRector`](../src/ContentRepository90/Rules/FusionNodeParentRector.php)

```diff
+// TODO 9.0 migration: Line 26: You may need to rewrite "VARIABLE.parent" to Neos.NodeAccess.findParent(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.parent || documentNode.parent}
+    attributes = ${Neos.NodeAccess.findParent(node) || Neos.NodeAccess.findParent(documentNode)}

     #
     # the `checked` state is calculated outside the renderer to allow` overriding via `attributes`
     #
     checked = false
     checked.@process.checkMultiValue = ${Array.indexOf(field.getCurrentMultivalueStringified(), field.getTargetValueStringified()) > -1}
     checked.@process.checkMultiValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkMultiValue.@if.isMultiple = ${field.isMultiple()}
     checked.@process.checkSingleValue = ${field.getCurrentValueStringified() == field.getTargetValueStringified()}
     checked.@process.checkSingleValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkSingleValue.@if.isSingle = ${!field.isMultiple()}

     renderer = afx`
       <input
         type="checkbox"
-        name={node.parent}
+        name={Neos.NodeAccess.findParent(node)}
         value={someOtherVariable.parent}
         checked={props.checked}
-        {...node.parent}
+        {...Neos.NodeAccess.findParent(node)}
       />
     `
   }
 }
```

<br>

## FusionNodePathRector

Fusion: Rewrite node.path to Neos.Node.path(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodePathRector`](../src/ContentRepository90/Rules/FusionNodePathRector.php)

```diff
+// TODO 9.0 migration: Line 26: You may need to rewrite "VARIABLE.path" to Neos.Node.path(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.path || documentNode.path}
+    attributes = ${Neos.Node.path(node) || Neos.Node.path(documentNode)}

     #
     # the `checked` state is calculated outside the renderer to allow` overriding via `attributes`
     #
     checked = false
     checked.@process.checkMultiValue = ${Array.indexOf(field.getCurrentMultivalueStringified(), field.getTargetValueStringified()) > -1}
     checked.@process.checkMultiValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkMultiValue.@if.isMultiple = ${field.isMultiple()}
     checked.@process.checkSingleValue = ${field.getCurrentValueStringified() == field.getTargetValueStringified()}
     checked.@process.checkSingleValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkSingleValue.@if.isSingle = ${!field.isMultiple()}

     renderer = afx`
       <input
         type="checkbox"
-        name={node.path}
+        name={Neos.Node.path(node)}
         value={someOtherVariable.path}
         checked={props.checked}
-        {...node.path}
+        {...Neos.Node.path(node)}
       />
     `
   }
 }
```

<br>

## FusionNodePropertyPathToWarningCommentRector

Fusion: Adds a warning comment when the defined path is used within an Eel expression.

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\FusionNodePropertyPathToWarningCommentRector`](../src/Generic/Rules/FusionNodePropertyPathToWarningCommentRector.php)

```php
<?php

declare(strict_types=1);

use Neos\Rector\Generic\Rules\FusionNodePropertyPathToWarningCommentRector;
use Neos\Rector\Generic\ValueObject\FusionNodePropertyPathToWarningComment;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(FusionNodePropertyPathToWarningCommentRector::class, [
        new FusionNodePropertyPathToWarningComment('removed', 'Line %LINE: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.'),
        new FusionNodePropertyPathToWarningComment('hiddenBeforeDateTime', 'Line %LINE: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.'),
        new FusionNodePropertyPathToWarningComment('hiddenAfterDateTime', 'Line %LINE: !! node.hiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.'),
        new FusionNodePropertyPathToWarningComment('foo.bar', 'Line %LINE: !! node.foo.bar is not supported anymore.'),
    ]);
};
```

↓

```diff
+// TODO 9.0 migration: Line 20: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.
+// TODO 9.0 migration: Line 21: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.
+// TODO 9.0 migration: Line 42: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.
+// TODO 9.0 migration: Line 54: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.
+// TODO 9.0 migration: Line 20: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
+// TODO 9.0 migration: Line 21: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
+// TODO 9.0 migration: Line 46: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
+// TODO 9.0 migration: Line 48: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
+// TODO 9.0 migration: Line 22: !! node.hiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
+// TODO 9.0 migration: Line 40: !! node.hiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
+// TODO 9.0 migration: Line 52: !! node.hiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
+// TODO 9.0 migration: Line 23: !! node.foo.bar is not supported anymore.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
     attributes = ${node.removed || site.removed || documentNode.hiddenBeforeDateTime}
     attributes2 = ${node.hiddenBeforeDateTime || site.hiddenBeforeDateTime || documentNode.removed}
     attributes3 = ${node.hiddenAfterDateTime || site.hiddenAfterDateTime || documentNode.hiddenAfterDateTime}
     attributes4 = ${node.foo.bar}
     attributes5 = ${node.fooXbar}

     #
     # the `checked` state is calculated outside the renderer to allow` overriding via `attributes`
     #
     checked = false
     checked.@process.checkMultiValue = ${Array.indexOf(field.getCurrentMultivalueStringified(), field.getTargetValueStringified()) > -1}
     checked.@process.checkMultiValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkMultiValue.@if.isMultiple = ${field.isMultiple()}
     checked.@process.checkSingleValue = ${field.getCurrentValueStringified() == field.getTargetValueStringified()}
     checked.@process.checkSingleValue.@if.hasValue = ${field.hasCurrentValue()}
     checked.@process.checkSingleValue.@if.isSingle = ${!field.isMultiple()}

     renderer = afx`
       <input
         type="checkbox"
         name={node.hiddenAfterDateTime}
         checked={props.checked}
         {...node.removed}
       />
       <input
         type="checkbox"
         name={node.hiddenBeforeDateTime}
         checked={props.checked}
         {...node.hiddenBeforeDateTime}
       />
       <input
         type="checkbox"
         name={node.hiddenAfterDateTime}
         checked={props.checked}
         {...node.removed}
       />
     `
   }
 }
```

<br>

## InjectContentRepositoryRegistryIfNeededRector

add injection for `$contentRepositoryRegistry` if in use.

- class: [`Neos\Rector\ContentRepository90\Rules\InjectContentRepositoryRegistryIfNeededRector`](../src/ContentRepository90/Rules/InjectContentRepositoryRegistryIfNeededRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
     public function run(Node $node)
     {
         $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
     }
 }

 ?>
```

<br>

## MethodCallToWarningCommentRector

"Warning comments for various non-supported use cases

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\MethodCallToWarningCommentRector`](../src/Generic/Rules/MethodCallToWarningCommentRector.php)

```php
<?php

declare(strict_types=1);

use Neos\Rector\Generic\Rules\MethodCallToWarningCommentRector;
use Neos\Rector\Generic\ValueObject\MethodCallToWarningComment;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(MethodCallToWarningCommentRector::class, [
        new MethodCallToWarningComment('PhpParser\Node', 'getWorkspace', '!! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.'),
    ]);
};
```

↓

```diff
 <?php

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
+        // TODO 9.0 migration: !! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.
+
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

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        $parentNode = $node->findParentNode();
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        $parentNode = $subgraph->findParentNode($node->nodeAggregateId);
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

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        foreach ($node->getChildNodes() as $node) {
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        // TODO 9.0 migration: Try to remove the iterator_to_array($nodes) call.
+
+        foreach (iterator_to_array($subgraph->findChildNodes($node->nodeAggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter::all())) as $node) {
         }
     }
 }

 ?>
```

<br>

## NodeGetContextGetWorkspaceNameRector

`"NodeInterface::getContext()::getWorkspace()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetContextGetWorkspaceNameRector`](../src/ContentRepository90/Rules/NodeGetContextGetWorkspaceNameRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->getContext()->getWorkspaceName();
+        $contentRepository = $this->contentRepositoryRegistry->get($node->subgraphIdentity->contentRepositoryId);
+        return $contentRepository->getWorkspaceFinder()->findOneByCurrentContentStreamId($node->subgraphIdentity->contentStreamId)->workspaceName;
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

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->getContext()->getWorkspace();
+        $contentRepository = $this->contentRepositoryRegistry->get($node->subgraphIdentity->contentRepositoryId);
+        return $contentRepository->getWorkspaceFinder()->findOneByCurrentContentStreamId($node->subgraphIdentity->contentStreamId);
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

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->getDepth();
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        return $subgraph->findNodePath($node->nodeAggregateId)->getDepth();
     }
 }

 ?>
```

<br>

## NodeGetDimensionsRector

`"NodeInterface::getChildNodes()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetDimensionsRector`](../src/ContentRepository90/Rules/NodeGetDimensionsRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->getDimensions();
+        // TODO 9.0 migration: Try to remove the toLegacyDimensionArray() call and make your codebase more typesafe.
+
+        return $node->originDimensionSpacePoint->toLegacyDimensionArray();
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

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->getParent();
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        return $subgraph->findParentNode($node->nodeAggregateId);
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

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->getPath();
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        // TODO 9.0 migration: Try to remove the (string) cast and make your code more type-safe.
+
+        return (string) $subgraph->findNodePath($node->nodeAggregateId);
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

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->isHiddenInIndex();
+        return $node->getProperty('_hiddenInIndex');
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

 use Neos\ContentRepository\Core\Projection\ContentGraph\Node;

 class SomeClass
 {
     public function run(Node $node)
     {
-        return $node->isHidden();
+        $contentRepository = $this->contentRepositoryRegistry->get($node->subgraphIdentity->contentRepositoryId);
+        $nodeHiddenStateFinder = $contentRepository->projectionState(\Neos\ContentRepository\Core\Projection\NodeHiddenState\NodeHiddenStateFinder::class);
+        $hiddenState = $nodeHiddenStateFinder->findHiddenState($node->subgraphIdentity->contentStreamId, $node->subgraphIdentity->dimensionSpacePoint, $node->nodeAggregateId);
+        return $hiddenState->isHidden();
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
      * @var \Neos\ContentRepository\Core\NodeType\NodeTypeManager
      * @Flow\Inject
      */
     protected $nodeTypeManager;
     public function run()
     {
-        $nt = $this->nodeTypeManager->getNodeTypes(false);
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\Factory\ContentRepositoryId::fromString('default'));
+        $nt = $contentRepository->getNodeTypeManager()->getNodeTypes(false);
     }
 }

 ?>
```

<br>

## RemoveDuplicateCommentRector

"Warning comments for various non-supported use cases

- class: [`Neos\Rector\Generic\Rules\RemoveDuplicateCommentRector`](../src/Generic/Rules/RemoveDuplicateCommentRector.php)

```diff
 <?php

 // TODO 9.0 Some comment 1
-// TODO 9.0 Some comment 1
 class SomeClass
 {
 }

 ?>
```

<br>

## RemoveInjectionsRector

Remove properties marked with a @Flow\Inject annotation and a certain type

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\RemoveInjectionsRector`](../src/Generic/Rules/RemoveInjectionsRector.php)

```php
<?php

declare(strict_types=1);

use Neos\Rector\Generic\Rules\RemoveInjectionsRector;
use Neos\Rector\Generic\ValueObject\RemoveInjection;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RemoveInjectionsRector::class, [
        new RemoveInjection('Foo\Bar\Baz'),
    ]);
};
```

↓

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
    * @var Baz;
    */
   protected $foo2 = null;

-  /**
-   * @Flow\Inject
-   * @var Baz;
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

```php
<?php

declare(strict_types=1);

use Neos\Rector\Generic\Rules\RemoveParentClassRector;
use Neos\Rector\Generic\ValueObject\RemoveParentClass;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RemoveParentClassRector::class, [
        new RemoveParentClass('Foo\Bar\Baz', '// TODO: Neos 9.0 Migration: Stuff'),
    ]);
};
```

↓

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

## WorkspaceRepositoryCountByNameRector

`"WorkspaceRepository::countByName()"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceRepositoryCountByNameRector`](../src/ContentRepository90/Rules/WorkspaceRepositoryCountByNameRector.php)

```diff
 <?php

 use Neos\ContentRepository\Domain\Repository\WorkspaceRepository;
 use Neos\Flow\Annotations as Flow;

 class SomeClass
 {
-    /**
-     * @var WorkspaceRepository
-     * @Flow\Inject
-     */
-    protected $workspaceRepository;
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
     public function run(string $workspace)
     {
-        return $this->workspaceRepository->countByName($workspace);
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\Factory\ContentRepositoryId::fromString('default'));
+        // TODO 9.0 migration: remove ternary operator (...? 1 : 0 ) - unnecessary complexity
+
+        return $contentRepository->getWorkspaceFinder()->findOneByName(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($workspace)) !== null ? 1 : 0;
     }
 }

 ?>
```

<br>

## YamlDimensionConfigRector

Fusion: Rewrite Settings.yaml config to new language

- class: [`Neos\Rector\ContentRepository90\Rules\YamlDimensionConfigRector`](../src/ContentRepository90/Rules/YamlDimensionConfigRector.php)

```diff
-# some YAML with comments
 Neos:
-  ContentRepository:
-    contentDimensions:
-      language:
-        label: 'Neos.Demo:Main:contentDimensions.language'
-        icon: icon-language
-        default: en_US
-        defaultPreset: en_US
-        presets:
-          en_US:
-            label: 'English (US)'
+  ContentRepositoryRegistry:
+    contentRepositories:
+      default:
+        contentDimensions:
+          language:
+            label: 'Neos.Demo:Main:contentDimensions.language'
+            icon: icon-language
             values:
-              - en_US
-            # The default preset can also have an empty uriSegment value.
-            # https://docs.neos.io/cms/manual/content-repository/content-dimensions#behind-the-scenes-routing
-            uriSegment: en
-          en_UK:
-            label: 'English (UK)'
-            values:
-              - en_UK
-              - en_US
-            uriSegment: uk
-          de:
-            label: Deutsch
-            values:
-              - de
-            uriSegment: de
+              en_US:
+                label: 'English (US)'
+                specializations:
+                  en_UK:
+                    label: 'English (UK)'
+              de:
+                label: Deutsch
+  Neos:
+    sites:
+      '*':
+        contentDimensions:
+          # defaultDimensionSpacePoint is used for the homepage (URL /)
+          defaultDimensionSpacePoint:
+            language: en_US
+          resolver:
+            factoryClassName: Neos\Neos\FrontendRouting\DimensionResolution\Resolver\UriPathResolverFactory
+            options:
+              segments:
+                -
+                  dimensionIdentifier: language
+                  # dimensionValue => uriPathSegment (empty uriPathSegment allowed)
+                  dimensionValueMapping:
+                    en_US: en
+                    en_UK: uk
+                    de: de
```

<br>
