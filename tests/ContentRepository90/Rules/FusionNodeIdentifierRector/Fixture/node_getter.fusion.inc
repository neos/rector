prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${node.identifier || documentNode.identifier}
    renderer = afx`
      <input
        name={node.identifier}
        value={someOtherVariable.identifier}
        {...node.identifier}
      />
    `
  }
}
-----
// TODO 9.0 migration: Line 13: You may need to rewrite "VARIABLE.identifier" to "q(VARIABLE).id()". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${q(node).id() || q(documentNode).id()}
    renderer = afx`
      <input
        name={q(node).id()}
        value={someOtherVariable.identifier}
        {...q(node).id()}
      />
    `
  }
}
