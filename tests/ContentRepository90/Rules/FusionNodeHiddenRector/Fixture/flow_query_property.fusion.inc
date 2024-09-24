prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${q(node).property('_hidden') || q(documentNode).property("_hidden") || q(site).property("_hidden")}
  otherVariable = ${q(someOtherVariable).property('_hidden')}
  inAfx = afx`<Neos.Fusion:Value value={q(node).property('_hidden')}/>`
}
-----
prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${Neos.Node.isDisabled(node) || Neos.Node.isDisabled(documentNode) || Neos.Node.isDisabled(site)}
  otherVariable = ${Neos.Node.isDisabled(someOtherVariable)}
  inAfx = afx`<Neos.Fusion:Value value={Neos.Node.isDisabled(node)}/>`
}
