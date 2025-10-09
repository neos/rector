# 6 Rules Overview

## ContentRepositoryUtilityRenderValidNodeNameRector

Replaces Utility::renderValidNodeName(...) into NodeName::fromString(...)->value.

- class: [`Neos\Rector\ContentRepository90\Rules\ContentRepositoryUtilityRenderValidNodeNameRector`](../src/ContentRepository90/Rules/ContentRepositoryUtilityRenderValidNodeNameRector.php)

```diff
-\Neos\ContentRepository\Utility::renderValidNodeName('foo');
+\Neos\ContentRepository\Core\SharedModel\Node\NodeName::fromString('foo')->value;
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

     public function getWorkspace(): Workspace {
         return Workspace::create('default');
     }
 }

 ?>
```

<br>
