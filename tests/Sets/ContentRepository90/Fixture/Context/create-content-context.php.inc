<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\Neos\Controller\CreateContentContextTrait;

class CreateContentContextClassTest extends AnotherClass
{
    use CreateContentContextTrait;

    public function foo() {
        $workspaceName = "workspacename";
        $dimensions = [];
        $context = $this->createContentContext($workspaceName, $dimensions);
        $context->getCurrentSite();
    }

    public function bar(NodeData $nodeData)
    {
        $context = $this->createContextMatchingNodeData($nodeData);
        $context->getCurrentSite();
    }
}

-----
<?php

namespace Neos\Rector\Test;

use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\Neos\Controller\CreateContentContextTrait;

class CreateContentContextClassTest extends AnotherClass
{
    public function foo() {
        $workspaceName = "workspacename";
        $dimensions = [];
        // TODO 9.0 migration: !! CreateContentContextTrait::createContentContext() is removed in Neos 9.0.

        $context = $this->createContentContext($workspaceName, $dimensions);
        // TODO 9.0 migration: !! ContentContext::getCurrentSite() is removed in Neos 9.0.

        $context->getCurrentSite();
    }

    public function bar(NodeData $nodeData)
    {
        // TODO 9.0 migration: !! CreateContentContextTrait::createContextMatchingNodeData() is removed in Neos 9.0.

        $context = $this->createContextMatchingNodeData($nodeData);
        // TODO 9.0 migration: !! ContentContext::getCurrentSite() is removed in Neos 9.0.

        $context->getCurrentSite();
    }
}

