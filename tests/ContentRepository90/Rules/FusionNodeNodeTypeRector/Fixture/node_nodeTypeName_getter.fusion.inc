prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${node.nodeType.name || documentNode.nodeType.name || site.nodeType.name}
  otherVariable = ${someOtherVariable.nodeType.name}
  inAfx = afx`<Neos.Fusion:Value value={node.nodeType.name}/>`
}
-----
// TODO 9.0 migration: Line 4: You very likely need to rewrite "VARIABLE.nodeType" to "Neos.Node.getNodeType(VARIABLE)". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${q(node).nodeTypeName() || q(documentNode).nodeTypeName() || q(site).nodeTypeName()}
  otherVariable = ${someOtherVariable.nodeType.name}
  inAfx = afx`<Neos.Fusion:Value value={q(node).nodeTypeName()}/>`
}
