<?php


use Neos\ContentRepository\Domain\Model\NodeData;

class SomeClass {

    public function foo(NodeData $nodeData)
    {
        $nodeData->createNodeData();
        $nodeData->createNodeDataFromTemplate();
        $nodeData->createShadow();
        $nodeData->createSingleNodeData();
        $nodeData->getContextPath();
        $nodeData->getDepth();
        $nodeData->getDimensions();
        $nodeData->getDimensionsHash();
        $nodeData->getDimensionValues();
        $nodeData->getIdentifier();
        $nodeData->getIndex();
        $nodeData->getMovedTo();
        $nodeData->getName();
        $nodeData->getNumberOfChildNodes();
        $nodeData->getParent();
        $nodeData->getParentPath();
        $nodeData->getPath();
        $nodeData->getWorkspace();
        $nodeData->hasAccessRestrictions();
        $nodeData->isAccessible();
        $nodeData->isInternal();
        $nodeData->isRemoved();
        $nodeData->isVisible();
        $nodeData->matchesWorkspaceAndDimensions();
        $nodeData->move();
        $nodeData->remove();
        $nodeData->setDimensions();
        $nodeData->setIdentifier();
        $nodeData->setIndex();
        $nodeData->setMovedTo();
        $nodeData->setPath();
        $nodeData->setRemoved();
        $nodeData->setWorkspace();
        $nodeData->similarize();
    }
}
-----
<?php


use Neos\ContentRepository\Domain\Model\NodeData;

class SomeClass {

    public function foo(NodeData $nodeData)
    {
        // TODO 9.0 migration: !! NodeData::createNodeData is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->createNodeData();
        // TODO 9.0 migration: !! NodeData::createNodeDataFromTemplate is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->createNodeDataFromTemplate();
        // TODO 9.0 migration: !! NodeData::createShadow is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->createShadow();
        // TODO 9.0 migration: !! NodeData::createSingleNodeData is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->createSingleNodeData();
        // TODO 9.0 migration: !! NodeData::getContextPath is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getContextPath();
        // TODO 9.0 migration: !! NodeData::getDepth is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getDepth();
        // TODO 9.0 migration: !! NodeData::getDimensions is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getDimensions();
        // TODO 9.0 migration: !! NodeData::getDimensionsHash is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getDimensionsHash();
        // TODO 9.0 migration: !! NodeData::getDimensionValues is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getDimensionValues();
        // TODO 9.0 migration: !! NodeData::getIdentifier is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getIdentifier();
        // TODO 9.0 migration: !! NodeData::getIndex is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getIndex();
        // TODO 9.0 migration: !! NodeData::getMovedTo is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getMovedTo();
        // TODO 9.0 migration: !! NodeData::getName is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getName();
        // TODO 9.0 migration: !! NodeData::getNumberOfChildNodes is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getNumberOfChildNodes();
        // TODO 9.0 migration: !! NodeData::getParent is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getParent();
        // TODO 9.0 migration: !! NodeData::getParentPath is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getParentPath();
        // TODO 9.0 migration: !! NodeData::getPath is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getPath();
        // TODO 9.0 migration: !! NodeData::getWorkspace is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->getWorkspace();
        // TODO 9.0 migration: !! NodeData::hasAccessRestrictions is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->hasAccessRestrictions();
        // TODO 9.0 migration: !! NodeData::isAccessible is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->isAccessible();
        // TODO 9.0 migration: !! NodeData::isInternal is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->isInternal();
        // TODO 9.0 migration: !! NodeData::isRemoved is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->isRemoved();
        // TODO 9.0 migration: !! NodeData::isVisible is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->isVisible();
        // TODO 9.0 migration: !! NodeData::matchesWorkspaceAndDimensions is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->matchesWorkspaceAndDimensions();
        $nodeData->move();
        // TODO 9.0 migration: !! NodeData::remove is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->remove();
        // TODO 9.0 migration: !! NodeData::setDimensions is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->setDimensions();
        // TODO 9.0 migration: !! NodeData::setIdentifier is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->setIdentifier();
        // TODO 9.0 migration: !! NodeData::setIndex is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->setIndex();
        // TODO 9.0 migration: !! NodeData::setMovedTo is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->setMovedTo();
        // TODO 9.0 migration: !! NodeData::setPath is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->setPath();
        // TODO 9.0 migration: !! NodeData::setRemoved is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->setRemoved();
        // TODO 9.0 migration: !! NodeData::setWorkspace is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->setWorkspace();
        // TODO 9.0 migration: !! NodeData::similarize is removed in Neos 9.0 - the new CR is not based around the concept of NodeData anymore. You need to rewrite your code here.

        $nodeData->similarize();
    }
}
