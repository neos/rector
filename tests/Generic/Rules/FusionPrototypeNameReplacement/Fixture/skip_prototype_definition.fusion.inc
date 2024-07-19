prototype(Neos.Neos:Foo) < prototype(Neos.Neos:Bar) {

    raw = Neos.Neos:Foo
    renderer = afx`
        <Neos.Neos:Bar />
    `
}
-----
// TODO 9.0 migration:todo
// TODO 9.0 migration:todo
prototype(Neos.Neos:Foo) < prototype(Neos.Neos:Bar) {

    raw = Neos.Neos:FooReplaced
    renderer = afx`
        <Neos.Neos:BarReplaced />
    `
}
