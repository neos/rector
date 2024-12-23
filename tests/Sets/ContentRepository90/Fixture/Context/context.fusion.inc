prototype(Neos.Context:Prototype)  < prototype(Neos.Fusion:Component) {

    workspaceName = ${node.context.workspaceName}
    workspaceNameByWorkspace = ${node.context.workspace.name}
    rootNode = ${node.context.rootNode}

    currentDateTime = ${node.context.currentDateTime}
    dimensions = ${node.context.dimensions}
    properties = ${node.context.properties}

    targetDimensions = ${node.context.targetDimensions}
    targetDimensionValues = ${node.context.targetDimensionValues}

    workspace = ${node.context.workspace}

    isInaccessibleContentShown = ${node.context.isInaccessibleContentShown}
    isInvisibleContentShown = ${node.context.isInvisibleContentShown}
    isRemovedContentShown = ${node.context.isRemovedContentShown}

}
-----
// TODO 9.0 migration: Line 14: !! node.context.rootNode is removed in Neos 9.0.
// TODO 9.0 migration: Line 16: !! node.context.currentDateTime is removed in Neos 9.0.
// TODO 9.0 migration: Line 17: !! node.context.dimensions is removed in Neos 9.0. You can get node DimensionSpacePoints via node.dimensionSpacePoints now.
// TODO 9.0 migration: Line 18: !! node.context.properties is removed in Neos 9.0.
// TODO 9.0 migration: Line 20: !! node.context.targetDimensions is removed in Neos 9.0.
// TODO 9.0 migration: Line 21: !! node.context.targetDimensionValues is removed in Neos 9.0.
// TODO 9.0 migration: Line 25: !! node.context.isInaccessibleContentShown is removed in Neos 9.0.
// TODO 9.0 migration: Line 26: !! node.context.isInvisibleContentShown is removed in Neos 9.0.
// TODO 9.0 migration: Line 27: !! node.context.isRemovedContentShown is removed in Neos 9.0.
prototype(Neos.Context:Prototype)  < prototype(Neos.Fusion:Component) {

    workspaceName = ${node.workspaceName}
    workspaceNameByWorkspace = ${node.workspaceName}
    rootNode = ${node.context.rootNode}

    currentDateTime = ${node.context.currentDateTime}
    dimensions = ${node.context.dimensions}
    properties = ${node.context.properties}

    targetDimensions = ${node.context.targetDimensions}
    targetDimensionValues = ${node.context.targetDimensionValues}

    workspace = ${node.context.workspace}

    isInaccessibleContentShown = ${node.context.isInaccessibleContentShown}
    isInvisibleContentShown = ${node.context.isInvisibleContentShown}
    isRemovedContentShown = ${node.context.isRemovedContentShown}

}
