prototype(Neos.Context:Prototype)  < prototype(Neos.Fusion:Component) {

    workspace = ${node.context.workspace}
    workspaceName = ${node.context.workspaceName}
    workspaceNameByWorkspace = ${node.context.workspace.name}

    attributes = ${node.context.workspace || site.context.workspace || documentNode.context.workspace}


    renderer = afx`
        <Neos.Context:Component
            type="checkbox"
            name={node.context.workspace}
            value={someOtherVariable.context.workspace}
        />
    `
}
-----
// TODO 9.0 migration: Line 8: You very likely need to rewrite "VARIABLE.context.workspace" as the "context" of nodes has been removed without a direct replacement in Neos 9. If you really need the workspace in fusion you need to create a dedicated helper yourself which should ideally do ALL the complex logic in php directly and return the computed result.
// TODO 9.0 migration: Line 10: You very likely need to rewrite "VARIABLE.context.workspace" as the "context" of nodes has been removed without a direct replacement in Neos 9. If you really need the workspace in fusion you need to create a dedicated helper yourself which should ideally do ALL the complex logic in php directly and return the computed result.
// TODO 9.0 migration: Line 12: You very likely need to rewrite "VARIABLE.context.workspace" as the "context" of nodes has been removed without a direct replacement in Neos 9. If you really need the workspace in fusion you need to create a dedicated helper yourself which should ideally do ALL the complex logic in php directly and return the computed result.
// TODO 9.0 migration: Line 18: You very likely need to rewrite "VARIABLE.context.workspace" as the "context" of nodes has been removed without a direct replacement in Neos 9. If you really need the workspace in fusion you need to create a dedicated helper yourself which should ideally do ALL the complex logic in php directly and return the computed result.
// TODO 9.0 migration: Line 19: You very likely need to rewrite "VARIABLE.context.workspace" as the "context" of nodes has been removed without a direct replacement in Neos 9. If you really need the workspace in fusion you need to create a dedicated helper yourself which should ideally do ALL the complex logic in php directly and return the computed result.
prototype(Neos.Context:Prototype)  < prototype(Neos.Fusion:Component) {

    workspace = ${node.context.workspace}
    workspaceName = ${node.context.workspaceName}
    workspaceNameByWorkspace = ${node.context.workspace.name}

    attributes = ${node.context.workspace || site.context.workspace || documentNode.context.workspace}


    renderer = afx`
        <Neos.Context:Component
            type="checkbox"
            name={node.context.workspace}
            value={someOtherVariable.context.workspace}
        />
    `
}
