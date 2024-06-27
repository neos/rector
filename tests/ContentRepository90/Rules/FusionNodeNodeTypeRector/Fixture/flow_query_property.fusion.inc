prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${q(node).property('_nodeType') || q(documentNode).property("_nodeType") || q(site).property("_nodeType")}
  otherVariable = ${q(someOtherVariable).property('_nodeType')}
  nested = ${q(someOtherVariable).property('_nodeType.properties')}
  deepNested = ${q(someOtherVariable).property('_nodeType.options.myOption')}
  inAfx = afx`<Neos.Fusion:Value value={q(node).property('_nodeType')}/>`
}
-----
prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${Neos.Node.getNodeType(node) || Neos.Node.getNodeType(documentNode) || Neos.Node.getNodeType(site)}
  otherVariable = ${Neos.Node.getNodeType(someOtherVariable)}
  nested = ${Neos.Node.getNodeType(someOtherVariable).properties}
  deepNested = ${Neos.Node.getNodeType(someOtherVariable).options.myOption}
  inAfx = afx`<Neos.Fusion:Value value={Neos.Node.getNodeType(node)}/>`
}
