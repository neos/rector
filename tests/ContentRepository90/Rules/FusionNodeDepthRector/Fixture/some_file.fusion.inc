prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${node.depth || documentNode.depth || node.property('_depth') || documentNode.property("_depth")}
    foo = ${bar.property('_depth') || bar.property("_depth")}

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
        name={node.depth}
        value={someOtherVariable.depth}
        checked={props.checked}
        {...node.depth}
      />
    `
  }
}
-----
// TODO 9.0 migration: Line 11: You may need to rewrite "VARIABLE.property('_depth')" to Neos.Node.depth(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
// TODO 9.0 migration: Line 27: You may need to rewrite "VARIABLE.depth" to Neos.Node.depth(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${Neos.Node.depth(node) || Neos.Node.depth(documentNode) || Neos.Node.depth(node) || Neos.Node.depth(documentNode)}
    foo = ${bar.property('_depth') || bar.property("_depth")}

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
        name={Neos.Node.depth(node)}
        value={someOtherVariable.depth}
        checked={props.checked}
        {...Neos.Node.depth(node)}
      />
    `
  }
}
