prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${node.removed || site.removed || documentNode.hiddenBeforeDateTime}
    attributes2 = ${node.hiddenBeforeDateTime || site.hiddenBeforeDateTime || documentNode.removed}
    attributes3 = ${node.hiddenAfterDateTime || site.hiddenAfterDateTime || documentNode.hiddenAfterDateTime}
    attributes4 = ${node.foo.bar}
    attributes5 = ${node.fooXbar}

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
        name={node.hiddenAfterDateTime}
        checked={props.checked}
        {...node.removed}
      />
      <input
        type="checkbox"
        name={node.hiddenBeforeDateTime}
        checked={props.checked}
        {...node.hiddenBeforeDateTime}
      />
      <input
        type="checkbox"
        name={node.hiddenAfterDateTime}
        checked={props.checked}
        {...node.removed}
      />
    `
  }
}
-----
// TODO 9.0 migration: Line 20: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.
// TODO 9.0 migration: Line 21: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.
// TODO 9.0 migration: Line 42: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.
// TODO 9.0 migration: Line 54: !! node.removed - the new CR *never* returns removed nodes; so you can simplify your code and just assume removed == FALSE in all scenarios.
// TODO 9.0 migration: Line 20: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
// TODO 9.0 migration: Line 21: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
// TODO 9.0 migration: Line 46: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
// TODO 9.0 migration: Line 48: !! node.hiddenBeforeDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
// TODO 9.0 migration: Line 22: !! node.hiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
// TODO 9.0 migration: Line 40: !! node.hiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
// TODO 9.0 migration: Line 52: !! node.hiddenAfterDateTime is not supported by the new CR. Timed publishing will be implemented not on the read model, but by dispatching commands at a given time.
// TODO 9.0 migration: Line 23: !! node.foo.bar is not supported anymore.
prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${node.removed || site.removed || documentNode.hiddenBeforeDateTime}
    attributes2 = ${node.hiddenBeforeDateTime || site.hiddenBeforeDateTime || documentNode.removed}
    attributes3 = ${node.hiddenAfterDateTime || site.hiddenAfterDateTime || documentNode.hiddenAfterDateTime}
    attributes4 = ${node.foo.bar}
    attributes5 = ${node.fooXbar}

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
        name={node.hiddenAfterDateTime}
        checked={props.checked}
        {...node.removed}
      />
      <input
        type="checkbox"
        name={node.hiddenBeforeDateTime}
        checked={props.checked}
        {...node.hiddenBeforeDateTime}
      />
      <input
        type="checkbox"
        name={node.hiddenAfterDateTime}
        checked={props.checked}
        {...node.removed}
      />
    `
  }
}
