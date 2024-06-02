prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${q(node).property("_identifier") || q(documentNode).property("_identifier")}
    renderer = afx`
      <input
        name={q(node).property('_identifier')}
        value={q(someOtherVariable).property("_identifier")}
        {...q(node).property("_identifier")}
      />
    `
  }
}
-----
prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    #
    # pass down props
    #
    attributes = ${q(node).id() || q(documentNode).id()}
    renderer = afx`
      <input
        name={q(node).id()}
        value={q(someOtherVariable).id()}
        {...q(node).id()}
      />
    `
  }
}
