prototype(Neos.Neos:PrimaryContent) {
  myArticle {
    condition = ${q(node).is('[instanceof My.Site:Article]')}
    renderer = My.Site:ArticleRenderer
  }
}

content = Neos.Neos:PrimaryContent {
  nodePath = 'main'
}
-----
// TODO 9.0 migration:TODO 9.0 migration: bla bla
prototype(Neos.Neos:PrimaryContent) {
  myArticle {
    condition = ${q(node).is('[instanceof My.Site:Article]')}
    renderer = My.Site:ArticleRenderer
  }
}

content = Neos.Neos:ContentCollection {
  nodePath = 'main'
}
