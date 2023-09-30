prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${node.nodeAggregateIdentifier || documentNode.nodeAggregateIdentifier}
    renderer = afx`
      <input
        name={node.nodeAggregateIdentifier}
        value={someOtherVariable.nodeAggregateIdentifier}
        {...node.nodeAggregateIdentifier}
      />
    `
  }
}
-----
// TODO 9.0 migration: Line 13: You may need to rewrite "VARIABLE.nodeAggregateIdentifier" to VARIABLE.nodeAggregateId.value. We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${node.nodeAggregateId.value || documentNode.nodeAggregateId.value}
    renderer = afx`
      <input
        name={node.nodeAggregateId.value}
        value={someOtherVariable.nodeAggregateIdentifier}
        {...node.nodeAggregateId.value}
      />
    `
  }
}
