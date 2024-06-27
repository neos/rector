prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${node.label || documentNode.label || site.label}
  otherVariable = ${someOtherVariable.label}
  inAfx = afx`<Neos.Fusion:Value value={node.label}/>`
}
-----
// TODO 9.0 migration: Line 4: You very likely need to rewrite "VARIABLE.label" to "q(VARIABLE).label()". We did not auto-apply this migration because we cannot be sure whether the variable is a Node.
prototype(Neos.Rector:Test)  < prototype(Neos.Fusion:Value) {
  node = ${q(node).label() || q(documentNode).label() || q(site).label()}
  otherVariable = ${someOtherVariable.label}
  inAfx = afx`<Neos.Fusion:Value value={q(node).label()}/>`
}
