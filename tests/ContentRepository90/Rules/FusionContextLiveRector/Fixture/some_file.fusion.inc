prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${node.context.live || site.context.live || documentNode.context.live}

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
        name={node.context.live}
        value={someOtherVariable.context.live}
        checked={props.checked}
        {...node.context.live}
      />
    `
  }
}
-----
// TODO 9.0 migration: Line 26: You very likely need to rewrite "VARIABLE.context.live" to Neos.Node.isLive(VARIABLE). We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${Neos.Node.isLive(node) || Neos.Node.isLive(site) || Neos.Node.isLive(documentNode)}

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
        name={Neos.Node.isLive(node)}
        value={someOtherVariable.context.live}
        checked={props.checked}
        {...Neos.Node.isLive(node)}
      />
    `
  }
}
