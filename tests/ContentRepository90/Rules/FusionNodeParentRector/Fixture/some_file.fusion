prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${node.parent || documentNode.parent}

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
        name={node.parent}
        value={someOtherVariable.parent}
        checked={props.checked}
        {...node.parent}
      />
    `
  }
}
-----
// TODO 9.0 migration: Line 26: You may need to rewrite "VARIABLE.parent" to Neos.NodeAccess.findParent(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${Neos.NodeAccess.findParent(node) || Neos.NodeAccess.findParent(documentNode)}

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
        name={Neos.NodeAccess.findParent(node)}
        value={someOtherVariable.parent}
        checked={props.checked}
        {...Neos.NodeAccess.findParent(node)}
      />
    `
  }
}
