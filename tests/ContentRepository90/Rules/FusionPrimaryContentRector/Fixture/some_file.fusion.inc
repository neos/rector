prototype(My.Fancy:Component) < prototype(Neos.Fusion:Join) {
  main = Neos.Neos:PrimaryContent {
    nodePath = 'main'
  }

  content = Neos.Neos:PrimaryContent
  content.nodePath = 'content'
}

prototype(My.Evil:Component) < prototype(Neos.Neos:PrimaryContent) {

}
-----
// TODO 9.0 migration: You need to rewrite "Neos.Neos:PrimaryContent" to "Neos.Neos:ContentCollection".
prototype(My.Fancy:Component) < prototype(Neos.Fusion:Join) {
  main = Neos.Neos:PrimaryContent {
    nodePath = 'main'
  }

  content = Neos.Neos:PrimaryContent
  content.nodePath = 'content'
}

prototype(My.Evil:Component) < prototype(Neos.Neos:PrimaryContent) {

}
