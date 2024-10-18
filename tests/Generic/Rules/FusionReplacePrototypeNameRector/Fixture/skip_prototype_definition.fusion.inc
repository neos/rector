prototype(Neos.Neos:Foo) < prototype(Neos.Neos:Bar) {

    raw = Neos.Neos:Foo
    renderer = afx`
        <Neos.Neos:Bar />
    `
}
-----
// TODO 9.0 migration: Neos.Neos:FooReplaced: This comment should be added on top of the file.
// TODO 9.0 migration: Neos.Neos:BarReplaced: This comment should be added on top of the file.
prototype(Neos.Neos:Foo) < prototype(Neos.Neos:Bar) {

    raw = Neos.Neos:FooReplaced
    renderer = afx`
        <Neos.Neos:BarReplaced />
    `
}
