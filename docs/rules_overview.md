# 58 Rules Overview

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
+        // TODO 9.0 migration: !! MEGA DIRTY CODE! Ensure to rewrite this; by getting rid of LegacyContextStub.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $workspace = $contentRepository->getWorkspaceFinder()->findOneByName(\Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName::fromString($context->workspaceName ?? 'live'));
+        $rootNodeAggregate = $contentRepository->getContentGraph()->findRootNodeAggregateByType($workspace->currentContentStreamId, \Neos\ContentRepository\Core\NodeType\NodeTypeName::fromString('Neos.Neos:Sites'));
+        $subgraph = $contentRepository->getContentGraph()->getSubgraph($workspace->currentContentStreamId, \Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePoint::fromLegacyDimensionArray($context->dimensions ?? []), $context->invisibleContentShown ? \Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints::withoutRestrictions() : \Neos\ContentRepository\Core\Projection\ContentGraph\VisibilityConstraints::frontend());
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

## FusionContextCurrentRenderingModeRector

Fusion: Rewrite node.context.currentRenderingMode... to renderingMode...

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextCurrentRenderingModeRector`](../src/ContentRepository90/Rules/FusionContextCurrentRenderingModeRector.php)

```diff
+// TODO 9.0 migration: Line 9: You very likely need to rewrite "VARIABLE.context.currentRenderingMode..." to "renderingMode...". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

-    nodeAttributes = ${node.context.currentRenderingMode.edit || node.context.currentRenderingMode.preview || node.context.currentRenderingMode.title || node.context.currentRenderingMode.name || node.context.currentRenderingMode.fusionPath || node.context.currentRenderingMode.options['foo']}
-    siteAttributes = ${site.context.currentRenderingMode.edit || site.context.currentRenderingMode.preview || site.context.currentRenderingMode.title || site.context.currentRenderingMode.name || site.context.currentRenderingMode.fusionPath || site.context.currentRenderingMode.options['foo']}
-    documentNodeAttributes = ${documentNode.context.currentRenderingMode.edit || documentNode.context.currentRenderingMode.preview || documentNode.context.currentRenderingMode.title || documentNode.context.currentRenderingMode.name || documentNode.context.currentRenderingMode.fusionPath || documentNode.context.currentRenderingMode.options['foo']}
+    nodeAttributes = ${renderingMode.isEdit || renderingMode.isPreview || renderingMode.title || renderingMode.name || renderingMode.fusionPath || renderingMode.options['foo']}
+    siteAttributes = ${renderingMode.isEdit || renderingMode.isPreview || renderingMode.title || renderingMode.name || renderingMode.fusionPath || renderingMode.options['foo']}
+    documentNodeAttributes = ${renderingMode.isEdit || renderingMode.isPreview || renderingMode.title || renderingMode.name || renderingMode.fusionPath || renderingMode.options['foo']}
     other = ${other.context.currentRenderingMode.edit || other.context.currentRenderingMode.preview || other.context.currentRenderingMode.title || other.context.currentRenderingMode.name || other.context.currentRenderingMode.fusionPath || other.context.currentRenderingMode.options['foo']}

     renderer = afx`
       <input
         type="checkbox"
-        name={node.context.currentRenderingMode.name}
-        value={node.context.currentRenderingMode.title}
-        checked={node.context.currentRenderingMode.edit}
-        {...node.context.currentRenderingMode.options}
+        name={renderingMode.name}
+        value={renderingMode.title}
+        checked={renderingMode.isEdit}
+        {...renderingMode.options}
       />
     `
   }
 }
```

<br>

## FusionContextCurrentSiteRector

Fusion: Rewrite node.context.currentSite to Neos.Site.findBySiteNode(site)

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

Fusion: Rewrite "node.context.inBackend" to "renderingMode.isEdit"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextInBackendRector`](../src/ContentRepository90/Rules/FusionContextInBackendRector.php)

```diff
+// TODO 9.0 migration: Line 26: You very likely need to rewrite "VARIABLE.context.inBackend" to "renderingMode.isEdit". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.context.inBackend || site.context.inBackend || documentNode.context.inBackend}
+    attributes = ${renderingMode.isEdit || renderingMode.isEdit || renderingMode.isEdit}

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
+        name={renderingMode.isEdit}
         value={someOtherVariable.context.inBackend}
         checked={props.checked}
-        {...node.context.inBackend}
+        {...renderingMode.isEdit}
       />
     `
   }
 }
```

<br>

## FusionContextLiveRector

Fusion: Rewrite "node.context.live" to "!renderingMode.isEdit"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionContextLiveRector`](../src/ContentRepository90/Rules/FusionContextLiveRector.php)

```diff
+// TODO 9.0 migration: Line 26: You very likely need to rewrite "VARIABLE.context.live" to "!renderingMode.isEdit". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.context.live || site.context.live || documentNode.context.live}
+    attributes = ${!renderingMode.isEdit || !renderingMode.isEdit || !renderingMode.isEdit}

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
+        name={!renderingMode.isEdit}
         value={someOtherVariable.context.live}
         checked={props.checked}
-        {...node.context.live}
+        {...!renderingMode.isEdit}
       />
     `
   }
 }
```

<br>

## FusionFlowQueryNodePropertyToWarningCommentRector

Fusion: Adds a warning comment when the defined property is used within an FlowQuery `"property()".`

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\FusionFlowQueryNodePropertyToWarningCommentRector`](../src/Generic/Rules/FusionFlowQueryNodePropertyToWarningCommentRector.php)

```php
<?php

declare(strict_types=1);

use Neos\Rector\Generic\Rules\FusionFlowQueryNodePropertyToWarningCommentRector;
use Neos\Rector\Generic\ValueObject\FusionFlowQueryNodePropertyToWarningComment;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => FusionFlowQueryNodePropertyToWarningCommentRector::class,
            'configuration' => [
                new FusionFlowQueryNodePropertyToWarningComment('_autoCreated', 'Line %LINE: !! You very likely need to rewrite "q(VARIABLE).property("_autoCreated")" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.'),
            ],
        ],
    ]);
};
```

↓

```diff
+// TODO 9.0 migration: Line 11: !! You very likely need to rewrite "q(VARIABLE).property("_autoCreated")" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
+// TODO 9.0 migration: Line 12: !! You very likely need to rewrite "q(VARIABLE).property("_autoCreated")" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
+// TODO 9.0 migration: Line 13: !! You very likely need to rewrite "q(VARIABLE).property("_autoCreated")" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
+// TODO 9.0 migration: Line 11: !! You very likely need to rewrite "q(VARIABLE).property("_contextPath")" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
+// TODO 9.0 migration: Line 12: !! You very likely need to rewrite "q(VARIABLE).property("_contextPath")" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
+// TODO 9.0 migration: Line 13: !! You very likely need to rewrite "q(VARIABLE).property("_contextPath")" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     attributes = ${q(node).property('_autoCreated') || q(site).property("_contextPath")}
     attributes2 = ${q(site).property('_autoCreated') || q(site).property("_contextPath")}
     attributes3 = ${q(node).parent().property('_autoCreated') || q(node).parent().property("_contextPath")}

   }
 }
```

<br>

## FusionNodeAggregateIdentifierRector

Fusion: Rewrite node.nodeAggregateIdentifier to node.nodeAggregateId

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeAggregateIdentifierRector`](../src/ContentRepository90/Rules/FusionNodeAggregateIdentifierRector.php)

```diff
+// TODO 9.0 migration: Line 13: You may need to rewrite "VARIABLE.nodeAggregateIdentifier" to VARIABLE.nodeAggregateId. We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.nodeAggregateIdentifier || documentNode.nodeAggregateIdentifier}
+    attributes = ${node.nodeAggregateId || documentNode.nodeAggregateId}
     renderer = afx`
       <input
-        name={node.nodeAggregateIdentifier}
+        name={node.nodeAggregateId}
         value={someOtherVariable.nodeAggregateIdentifier}
-        {...node.nodeAggregateIdentifier}
+        {...node.nodeAggregateId}
       />
     `
   }
 }
```

<br>

## FusionNodeAutoCreatedRector

Fusion: Rewrite node.autoCreated to node.classification.tethered

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeAutoCreatedRector`](../src/ContentRepository90/Rules/FusionNodeAutoCreatedRector.php)

```diff
+// TODO 9.0 migration: Line 26: !! You very likely need to rewrite "VARIABLE.autoCreated" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

     renderer = Neos.Fusion:Component {

         #
         # pass down props
         #
-        attributes = ${node.autoCreated || documentNode.autoCreated}
+        attributes = ${node.classification.tethered || documentNode.classification.tethered}

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
-                name={node.autoCreated}
+                name={node.classification.tethered}
                 value={someOtherVariable.autoCreated}
                 checked={props.checked}
-                {...node.autoCreated}
+                {...node.classification.tethered}
         />
         `
     }
 }
```

<br>

## FusionNodeContextPathRector

Fusion: Rewrite node.contextPath to Neos.Node.serializedNodeAddress(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeContextPathRector`](../src/ContentRepository90/Rules/FusionNodeContextPathRector.php)

```diff
+// TODO 9.0 migration: Line 12: !! You very likely need to rewrite "q(VARIABLE).property('_contextPath')" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
+// TODO 9.0 migration: Line 28: !! You very likely need to rewrite "VARIABLE.contextPath" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

     renderer = Neos.Fusion:Component {

         #
         # pass down props
         #
-        attributes = ${node.contextPath || documentNode.contextPath || q(node).property('_contextPath') || q(documentNode).property("_contextPath")}
-        foo = ${q(bar).property('_contextPath') || q(bar).property("_contextPath")}
+        attributes = ${Neos.Node.serializedNodeAddress(node) || Neos.Node.serializedNodeAddress(documentNode) || Neos.Node.serializedNodeAddress(node) || Neos.Node.serializedNodeAddress(documentNode)}
+        foo = ${Neos.Node.serializedNodeAddress(bar) || Neos.Node.serializedNodeAddress(bar)}
         boo = ${q(nodes).first().property('_contextPath') || q(nodes).first().property("_contextPath")}

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
-                name={node.contextPath}
+                name={Neos.Node.serializedNodeAddress(node)}
                 value={someOtherVariable.contextPath}
                 checked={props.checked}
-                {...node.contextPath}
+                {...Neos.Node.serializedNodeAddress(node)}
         />
         `
     }
 }
```

<br>

## FusionNodeDepthRector

Fusion: Rewrite node.depth and q(node).property("_depth") to Neos.Node.depth(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeDepthRector`](../src/ContentRepository90/Rules/FusionNodeDepthRector.php)

```diff
+// TODO 9.0 migration: Line 13: You may need to rewrite "q(VARIABLE).property('_depth')" to Neos.Node.depth(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
+// TODO 9.0 migration: Line 29: You may need to rewrite "VARIABLE.depth" to Neos.Node.depth(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
+// TODO 9.0 migration: Line 30: You may need to rewrite "VARIABLE.depth" to Neos.Node.depth(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.depth || documentNode.depth || q(node).property('_depth') || q(documentNode).property("_depth")}
-    foo = ${q(bar).property('_depth') || q(bar).property("_depth")}
+    attributes = ${Neos.Node.depth(node) || Neos.Node.depth(documentNode) || Neos.Node.depth(node) || Neos.Node.depth(documentNode)}
+    foo = ${Neos.Node.depth(bar) || Neos.Node.depth(bar)}
     boo = ${q(nodes).first().property('_depth') || q(nodes).first().property("_depth")}

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
         value={someOtherVariable.depth || something}
         path={someOtherVariable.depth}
         checked={props.checked}
-        {...node.depth}
+        {...Neos.Node.depth(node)}
       />
     `
   }
 }
```

<br>

## FusionNodeHiddenAfterDateTimeRector

Fusion: Rewrite node.hiddenAfterDateTime to q(node).property("disableAfterDateTime")

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenAfterDateTimeRector`](../src/ContentRepository90/Rules/FusionNodeHiddenAfterDateTimeRector.php)

```diff
+// TODO 9.0 migration: Line 16: You may need to rewrite "VARIABLE.hiddenAfterDateTime" to q(VARIABLE).property("disableAfterDateTime"). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.hiddenAfterDateTime || documentNode.hiddenAfterDateTime}
-    attributes2 = ${q(node).property("_hiddenAfterDateTime")}
+    attributes = ${q(node).property("disableAfterDateTime") || q(documentNode).property("disableAfterDateTime")}
+    attributes2 = ${q(node).property("disableAfterDateTime")}

     renderer = afx`
       <input
         type="checkbox"
-        name={node.hiddenAfterDateTime}
+        name={q(node).property("disableAfterDateTime")}
         value={someOtherVariable.hiddenAfterDateTime}
         checked={props.checked}
-        {...node.hiddenAfterDateTime}
+        {...q(node).property("disableAfterDateTime")}
       />
     `
   }
 }
```

<br>

## FusionNodeHiddenBeforeDateTimeRector

Fusion: Rewrite node.hiddenBeforeDateTime to q(node).property("enableAfterDateTime")

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenBeforeDateTimeRector`](../src/ContentRepository90/Rules/FusionNodeHiddenBeforeDateTimeRector.php)

```diff
+// TODO 9.0 migration: Line 16: You may need to rewrite "VARIABLE.hiddenBeforeDateTime" to q(VARIABLE).property("enableAfterDateTime"). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.hiddenBeforeDateTime || documentNode.hiddenBeforeDateTime}
-    attribute2 = ${q(node).property("_hiddenBeforeDateTime")}
+    attributes = ${q(node).property("enableAfterDateTime") || q(documentNode).property("enableAfterDateTime")}
+    attribute2 = ${q(node).property("enableAfterDateTime")}

     renderer = afx`
       <input
         type="checkbox"
-        name={node.hiddenBeforeDateTime}
+        name={q(node).property("enableAfterDateTime")}
         value={someOtherVariable.hiddenBeforeDateTime}
         checked={props.checked}
-        {...node.hiddenBeforeDateTime}
+        {...q(node).property("enableAfterDateTime")}
       />
     `
   }
 }
```

<br>

## FusionNodeHiddenInIndexRector

Fusion: Rewrite node.hiddenInIndex and q(node).property("_hiddenInIndex") to node.property('hiddenInIndex')

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenInIndexRector`](../src/ContentRepository90/Rules/FusionNodeHiddenInIndexRector.php)

```diff
+// TODO 9.0 migration: Line 26: You may need to rewrite "VARIABLE.hiddenInIndex" to VARIABLE.property('hiddenInMenu'). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.hiddenInIndex || documentNode.hiddenInIndex || site.hiddenInIndex || q(node).property('_hiddenInIndex') || q(documentNode).property("_hiddenInIndex")}
+    attributes = ${node.property('hiddenInMenu') || documentNode.property('hiddenInMenu') || site.property('hiddenInMenu') || q(node).property('hiddenInMenu') || q(documentNode).property('hiddenInMenu')}

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
+        name={node.property('hiddenInMenu')}
         value={someOtherVariable.hiddenInIndex}
         checked={props.checked}
-        {...node.hiddenInIndex}
+        {...node.property('hiddenInMenu')}
       />
     `
   }
 }
```

<br>

## FusionNodeHiddenRector

Fusion: Rewrite node.hidden and q(node).property("_hidden") to Neos.Node.isDisabled(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeHiddenRector`](../src/ContentRepository90/Rules/FusionNodeHiddenRector.php)

```diff
+// TODO 9.0 migration: Line 5: You may need to rewrite "q(VARIABLE).property('_hidden')" to Neos.Node.isDisabled(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
-  node = ${q(node).property('_hidden') || q(documentNode).property("_hidden") || q(site).property("_hidden")}
-  otherVariable = ${q(someOtherVariable).property('_hidden')}
+  node = ${Neos.Node.isDisabled(node) || Neos.Node.isDisabled(documentNode) || Neos.Node.isDisabled(site)}
+  otherVariable = ${Neos.Node.isDisabled(someOtherVariable)}
   flowQuery = ${q(someOtherVariable).first().property('_hidden')}
-  inAfx = afx`<Neos.Fusion:Value value={q(node).property('_hidden')}/>`
+  inAfx = afx`<Neos.Fusion:Value value={Neos.Node.isDisabled(node)}/>`
 }
```

<br>

## FusionNodeIdentifierRector

Fusion: Rewrite "node.identifier" and "q(node).property('_identifier')" to "node.nodeAggregateId"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeIdentifierRector`](../src/ContentRepository90/Rules/FusionNodeIdentifierRector.php)

```diff
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${q(node).property("_identifier") || q(documentNode).property("_identifier")}
+    attributes = ${node.nodeAggregateId || documentNode.nodeAggregateId}
     renderer = afx`
       <input
-        name={q(node).property('_identifier')}
-        value={q(someOtherVariable).property("_identifier")}
-        {...q(node).property("_identifier")}
+        name={node.nodeAggregateId}
+        value={someOtherVariable.nodeAggregateId}
+        {...node.nodeAggregateId}
       />
     `
   }
 }
```

<br>

## FusionNodeLabelRector

Fusion: Rewrite "node.label" and "q(node).property('_label')" to "Neos.Node.label(node)"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeLabelRector`](../src/ContentRepository90/Rules/FusionNodeLabelRector.php)

```diff
 prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
-  node = ${q(node).property('_label') || q(documentNode).property("_label") || q(site).property("_label")}
-  otherVariable = ${q(someOtherVariable).property('_label')}
-  inAfx = afx`<Neos.Fusion:Value value={q(node).property('_label')}/>`
+  node = ${Neos.Node.label(node) || Neos.Node.label(documentNode) || Neos.Node.label(site)}
+  otherVariable = ${Neos.Node.label(someOtherVariable)}
+  inAfx = afx`<Neos.Fusion:Value value={Neos.Node.label(node)}/>`
 }
```

<br>

## FusionNodeNodeTypeRector

Fusion: Rewrite "node.nodeType" and "q(node).property('_nodeType')" to "Neos.Node.nodeType(node)"

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeNodeTypeRector`](../src/ContentRepository90/Rules/FusionNodeNodeTypeRector.php)

```diff
 prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
-  node = ${q(node).property('_nodeType') || q(documentNode).property("_nodeType") || q(site).property("_nodeType")}
-  otherVariable = ${q(someOtherVariable).property('_nodeType')}
-  nested = ${q(someOtherVariable).property('_nodeType.properties')}
-  deepNested = ${q(someOtherVariable).property('_nodeType.options.myOption')}
-  inAfx = afx`<Neos.Fusion:Value value={q(node).property('_nodeType')}/>`
+  node = ${Neos.Node.nodeType(node) || Neos.Node.nodeType(documentNode) || Neos.Node.nodeType(site)}
+  otherVariable = ${Neos.Node.nodeType(someOtherVariable)}
+  nested = ${Neos.Node.nodeType(someOtherVariable).properties}
+  deepNested = ${Neos.Node.nodeType(someOtherVariable).options.myOption}
+  inAfx = afx`<Neos.Fusion:Value value={Neos.Node.nodeType(node)}/>`
 }
```

<br>

## FusionNodeParentRector

Fusion: Rewrite node.parent to `q(node).parent().get(0)`

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeParentRector`](../src/ContentRepository90/Rules/FusionNodeParentRector.php)

```diff
+// TODO 9.0 migration: Line 15: You may need to rewrite "VARIABLE.parent" to "q(VARIABLE).parent().get(0)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.parent || documentNode.parent}
+    attributes = ${q(node).parent().get(0) || q(documentNode).parent().get(0)}

     renderer = afx`
       <input
         type="checkbox"
-        name={node.parent}
+        name={q(node).parent().get(0)}
         value={someOtherVariable.parent}
         data-parents={q(node).parents()}
         checked={props.checked}
-        {...node.parent}
+        {...q(node).parent().get(0)}
       />
     `
   }
 }
```

<br>

## FusionNodePathRector

Fusion: Rewrite node.path and q(node).property("_path") to Neos.Node.path(node)

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodePathRector`](../src/ContentRepository90/Rules/FusionNodePathRector.php)

```diff
+// TODO 9.0 migration: Line 29: You may need to rewrite "VARIABLE.path" to Neos.Node.path(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
+// TODO 9.0 migration: Line 30: You may need to rewrite "VARIABLE.path" to Neos.Node.path(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

   renderer = Neos.Fusion:Component {

     #
     # pass down props
     #
-    attributes = ${node.path || documentNode.path || q(node).property('_path') || q(documentNode).property("_path")}
-    foo = ${q(bar).property('_path') || q(bar).property("_path")}
+    attributes = ${Neos.Node.path(node) || Neos.Node.path(documentNode) || Neos.Node.path(node) || Neos.Node.path(documentNode)}
+    foo = ${Neos.Node.path(bar) || Neos.Node.path(bar)}
     boo = ${q(nodes).first().property('_path') || q(nodes).first().property("_path")}

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
         value={someOtherVariable.path || something}
         path={someOtherVariable.path}
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
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => FusionNodePropertyPathToWarningCommentRector::class,
            'configuration' => [
                new FusionNodePropertyPathToWarningComment('removed', 'Line %LINE: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.'),
                new FusionNodePropertyPathToWarningComment('hiddenBeforeDateTime', 'Line %LINE: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.'),
                new FusionNodePropertyPathToWarningComment('hiddenAfterDateTime', 'Line %LINE: !! node.hiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.'),
                new FusionNodePropertyPathToWarningComment('foo.bar', 'Line %LINE: !! node.foo.bar is not supported anymore.'),
            ],
        ],
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

## FusionNodeTypeNameRector

Fusion: Rewrite node.nodeType.name to node.nodeTypeName

- class: [`Neos\Rector\ContentRepository90\Rules\FusionNodeTypeNameRector`](../src/ContentRepository90/Rules/FusionNodeTypeNameRector.php)

```diff
+// TODO 9.0 migration: Line 13: You may need to rewrite "VARIABLE.nodeType.name" to "VARIABLE.nodeTypeName". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
 prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

 renderer = Neos.Fusion:Component {

 #
 # pass down props
 #
-attributes = ${node.nodeType.name || documentNode.nodeType.name}
+attributes = ${node.nodeTypeName || documentNode.nodeTypeName}
 renderer = afx`
 <input
-        name={node.nodeType.name}
+        name={node.nodeTypeName}
         value={someOtherVariable.nodeType.name}
-        {...node.nodeType.name}
+        {...node.nodeTypeName}
 />
 `
 }
 }
```

<br>

## FusionPrototypeNameAddCommentRector

Fusion: Add comment to file if prototype name matches at least once.

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\FusionPrototypeNameAddCommentRector`](../src/Generic/Rules/FusionPrototypeNameAddCommentRector.php)

```php
<?php

declare(strict_types=1);

use Neos\Rector\Generic\Rules\FusionPrototypeNameAddCommentRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => FusionPrototypeNameAddCommentRector::class,
            'configuration' => [
                'Neos.Neos:Raw',
                'Neos.Neos:Raw: Add this comment to top of file.',
            ],
        ],
    ]);
};
```

↓

```diff
+// TODO 9.0 migration: You need to refactor "Neos.Neos:PrimaryContent" to use "Neos.Neos:ContentCollection" instead.
 prototype(My.Fancy:Component) < prototype(Neos.Fusion:Join) {
   main = Neos.Neos:PrimaryContent {
     nodePath = 'main'
   }

   content = Neos.Neos:PrimaryContent
   content.nodePath = 'content'
 }

 prototype(My.Evil:Component) < prototype(Neos.Neos:PrimaryContent) {

 }
```

<br>

## FusionReplacePrototypeNameRector

Fusion: Rewrite prototype names form e.g Foo.Bar:Boo to Boo.Bar:Foo

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\FusionReplacePrototypeNameRector`](../src/Generic/Rules/FusionReplacePrototypeNameRector.php)

```php
<?php

declare(strict_types=1);

use Neos\Rector\Generic\Rules\FusionReplacePrototypeNameRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => FusionReplacePrototypeNameRector::class,
            'configuration' => [
                'Neos.Neos:Raw',
                'Neos.Neos:NewRaw',
                'Neos.Neos:Raw: This comment should be added on top of the file.',
            ],
        ],
    ]);
};
```

↓

```diff
+// TODO 9.0 migration: Neos.Neos:FooReplaced: This comment should be added on top of the file.
+// TODO 9.0 migration: Neos.Neos:BarReplaced: This comment should be added on top of the file.
 prototype(Neos.Neos:Foo) < prototype(Neos.Neos:Bar) {

-    raw = Neos.Neos:Foo
+    raw = Neos.Neos:FooReplaced
     renderer = afx`
-        <Neos.Neos:Bar />
+        <Neos.Neos:BarReplaced />
     `
 }
```

<br>

## InjectServiceIfNeededRector

add injection for `$contentRepositoryRegistry` if in use.

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\InjectServiceIfNeededRector`](../src/Generic/Rules/InjectServiceIfNeededRector.php)

```php
<?php

declare(strict_types=1);

use Neos\Rector\Generic\Rules\InjectServiceIfNeededRector;
use Neos\Rector\Generic\ValueObject\AddInjection;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => InjectServiceIfNeededRector::class,
            'configuration' => [
                new AddInjection('contentRepositoryRegistry', 'Neos\ContentRepositoryRegistry\ContentRepositoryRegistry'),
            ],
        ],
    ]);
};
```

↓

```diff
 <?php

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\Neos\Domain\Service\RenderingModeService $renderingModeService;
     public function run(NodeLegacyStub $node)
     {
         $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
         $currentRenderingMode = $this->renderingModeService->findByCurrentUser();
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
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => MethodCallToWarningCommentRector::class,
            'configuration' => [
                new MethodCallToWarningComment('Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub', 'getWorkspace', '!! Node::getWorkspace() does not make sense anymore concept-wise. In Neos < 9, it pointed to the workspace where the node was *at home at*. Now, the closest we have here is the node identity.'),
            ],
        ],
    ]);
};
```

↓

```diff
 <?php

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
     {
-        foreach ($node->getChildNodes(offset: 100, limit: 10) as $node) {
+        $subgraph = $this->contentRepositoryRegistry->subgraphForNode($node);
+        // TODO 9.0 migration: Try to remove the iterator_to_array($nodes) call.
+
+        foreach (iterator_to_array($subgraph->findChildNodes($node->nodeAggregateId, \Neos\ContentRepository\Core\Projection\ContentGraph\Filter\FindChildNodesFilter::create(pagination: ['limit' => 10, 'offset' => 100]))) as $node) {
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
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

## NodeGetIdentifierRector

`"NodeInterface::getIdentifier()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\NodeGetIdentifierRector`](../src/ContentRepository90/Rules/NodeGetIdentifierRector.php)

```diff
 <?php

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
     {
-        $nodeIdentifier = $node->getIdentifier();
+        // TODO 9.0 migration: Check if you could change your code to work with the NodeAggregateId value object instead.
+
+        $nodeIdentifier = $node->nodeAggregateId->value;
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
     public function run(NodeLegacyStub $node)
     {
-        return $node->isHidden();
+        return $node->tags->contain(\Neos\ContentRepository\Core\Feature\SubtreeTagging\Dto\SubtreeTag::disabled());
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\Neos\Domain\NodeLabel\NodeLabelGeneratorInterface $nodeLabelGenerator;
     public function run(NodeLegacyStub $node)
     {
-        $label = $node->getLabel();
+        $label = $this->nodeLabelGenerator->getLabel($node);
     }
 }

 ?>
```

<br>

## NodeTypeAllowsGrandchildNodeTypeRector

"$nodeType->allowsGrandchildNodeType($parentNodeName, `$nodeType)"` will be rewritten.

- class: [`Neos\Rector\ContentRepository90\Rules\NodeTypeAllowsGrandchildNodeTypeRector`](../src/ContentRepository90/Rules/NodeTypeAllowsGrandchildNodeTypeRector.php)

```diff
 <?php

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
 use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
     public function run(NodeLegacyStub $node)
     {
         $parentNodeName = 'name';
         $nodeType = $node->getNodeType();
         $grandParentsNodeType = $node->getParent()->getParent()->getNodeType();
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));

-        $grandParentsNodeType->allowsGrandchildNodeType($parentNodeName, $nodeType);
+        $contentRepository->getNodeTypeManager()->isNodeTypeAllowedAsChildToTetheredNode($grandParentsNodeType, \Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString($parentNodeName), $nodeType);
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
     public function run(NodeLegacyStub $node)
     {
         $nodeType = $node->getNodeType();
-        $childNodes = $nodeType->getAutoCreatedChildNodes();
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $childNodes = $contentRepository->getNodeTypeManager()->getTetheredNodesConfigurationForNodeType($nodeType);
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

 use Neos\Rector\ContentRepository90\Legacy\NodeLegacyStub;
 use Neos\ContentRepository\Core\SharedModel\Node\NodeName;

 class SomeClass
 {
+    #[\Neos\Flow\Annotations\Inject]
+    protected \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry $contentRepositoryRegistry;
     public function run(NodeLegacyStub $node)
     {
         $nodeName = NodeName::fromString('name');
         $nodeType = $node->getNodeType();
-        $type = $nodeType->getTypeOfAutoCreatedChildNode($nodeName);
+        // TODO 9.0 migration: Make this code aware of multiple Content Repositories.
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
+        $type = $contentRepository->getNodeTypeManager()->getTypeOfTetheredNode($nodeType, $nodeName);
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
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
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
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => RemoveInjectionsRector::class,
            'configuration' => [
                new RemoveInjection('Foo\Bar\Baz'),
            ],
        ],
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
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => RemoveParentClassRector::class,
            'configuration' => [
                new RemoveParentClass('Foo\Bar\Baz', '// TODO: Neos 9.0 Migration: Stuff'),
            ],
        ],
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

## ToStringToMethodCallOrPropertyFetchRector

Turns defined code uses of `"__toString()"` method to specific method calls or property fetches.

:wrench: **configure it!**

- class: [`Neos\Rector\Generic\Rules\ToStringToMethodCallOrPropertyFetchRector`](../src/Generic/Rules/ToStringToMethodCallOrPropertyFetchRector.php)

```php
<?php

declare(strict_types=1);

use Neos\Rector\Generic\Rules\ToStringToMethodCallOrPropertyFetchRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => ToStringToMethodCallOrPropertyFetchRector::class,
            'configuration' => [
                'SomeObject' => 'getPath()',
            ],
        ],
    ]);
};
```

↓

```diff
 $someValue = new SomeObject;
-$result = (string) $someValue;
-$result = $someValue->__toString();
+$result = $someValue->getPath();
+$result = $someValue->getPath();
```

<br>

## WorkspaceGetNameRector

`"Workspace::getName()"` will be rewritten

- class: [`Neos\Rector\ContentRepository90\Rules\WorkspaceGetNameRector`](../src/ContentRepository90/Rules/WorkspaceGetNameRector.php)

```diff
 <?php

 use Neos\ContentRepository\Core\Projection\Workspace\Workspace;

 class SomeClass
 {
     public function run(Workspace $workspace)
     {
-        $workspaceName = $workspace->getName();
+        // TODO 9.0 migration: Check if you could change your code to work with the WorkspaceName value object instead.
+
+        $workspaceName = $workspace->workspaceName->value;
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
+        $contentRepository = $this->contentRepositoryRegistry->get(\Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId::fromString('default'));
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
