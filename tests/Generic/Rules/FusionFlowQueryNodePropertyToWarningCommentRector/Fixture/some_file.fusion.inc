prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    attributes = ${q(node).property('_autoCreated') || q(site).property("_contextPath")}
    attributes2 = ${q(site).property('_autoCreated') || q(site).property("_contextPath")}
    attributes3 = ${q(node).parent().property('_autoCreated') || q(node).parent().property("_contextPath")}

  }
}
-----
// TODO 9.0 migration: Line 11: !! You very likely need to rewrite "q(VARIABLE).property("_autoCreated")" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
// TODO 9.0 migration: Line 12: !! You very likely need to rewrite "q(VARIABLE).property("_autoCreated")" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
// TODO 9.0 migration: Line 13: !! You very likely need to rewrite "q(VARIABLE).property("_autoCreated")" to "VARIABLE.classification.tethered". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
// TODO 9.0 migration: Line 11: !! You very likely need to rewrite "q(VARIABLE).property("_contextPath")" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
// TODO 9.0 migration: Line 12: !! You very likely need to rewrite "q(VARIABLE).property("_contextPath")" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
// TODO 9.0 migration: Line 13: !! You very likely need to rewrite "q(VARIABLE).property("_contextPath")" to "Neos.Node.serializedNodeAddress(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
prototype(Neos.Fusion.Form:Checkbox)  < prototype(Neos.Fusion.Form:Component.Field) {

  renderer = Neos.Fusion:Component {

    attributes = ${q(node).property('_autoCreated') || q(site).property("_contextPath")}
    attributes2 = ${q(site).property('_autoCreated') || q(site).property("_contextPath")}
    attributes3 = ${q(node).parent().property('_autoCreated') || q(node).parent().property("_contextPath")}

  }
}
