prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${q(node).property('_label') || q(documentNode).property("_label") || q(site).property("_label")}
  otherVariable = ${q(someOtherVariable).property('_label')}
  inAfx = afx`<Neos.Fusion:Value value={q(node).property('_label')}/>`
}
-----
prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${q(node).label() || q(documentNode).label() || q(site).label()}
  otherVariable = ${q(someOtherVariable).label()}
  inAfx = afx`<Neos.Fusion:Value value={q(node).label()}/>`
}
