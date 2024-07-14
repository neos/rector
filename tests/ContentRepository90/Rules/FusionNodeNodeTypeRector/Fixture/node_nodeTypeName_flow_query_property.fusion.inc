prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${q(node).property('_nodeType.name') || q(documentNode).property("_nodeType.name") || q(site).property("_nodeType.name")}
  otherVariable = ${q(someOtherVariable).property('_nodeType.name')}
  inAfx = afx`<Neos.Fusion:Value value={q(node).property('_nodeType.name')}/>`
  dontReplaceThis = ${q(node).property('_nodeTypesname')}
}
-----
prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${q(node).nodeTypeName() || q(documentNode).nodeTypeName() || q(site).nodeTypeName()}
  otherVariable = ${q(someOtherVariable).nodeTypeName()}
  inAfx = afx`<Neos.Fusion:Value value={q(node).nodeTypeName()}/>`
  dontReplaceThis = ${q(node).property('_nodeTypesname')}
}
