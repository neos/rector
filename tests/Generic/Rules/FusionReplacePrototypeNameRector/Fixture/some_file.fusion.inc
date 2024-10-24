prototype(Neos.Neos:SomethingOld)  < prototype(Neos.Neos:Raw) {

    raw = Neos.Neos:Raw
    rawer = Neos.Neos:Rawer

    renderer = Neos.Neos:Raw {

    old = Neos.Neos:SomethingOld
    older = Neos.Neos:SomethingOlder

    renderer = afx`
        <Neos.Neos:SomethingOld foo=""></Neos.Neos:SomethingOld>
        <Neos.Neos:Rawer />
        <Neos.Neos:Raw />
    `
    }
}
-----
// TODO 9.0 migration: Neos.Neos:Raw: This comment should be added on top of the file.
// TODO 9.0 migration: Neos.Neos:SomethingOlder: This comment should be added on top of the file.
prototype(Neos.Neos:SomethingNew)  < prototype(Neos.Neos:NewRaw) {

    raw = Neos.Neos:NewRaw
    rawer = Neos.Neos:Rawer

    renderer = Neos.Neos:NewRaw {

    old = Neos.Neos:SomethingNew
    older = Neos.Neos:SomethingNewer

    renderer = afx`
        <Neos.Neos:SomethingNew foo=""></Neos.Neos:SomethingNew>
        <Neos.Neos:Rawer />
        <Neos.Neos:NewRaw />
    `
    }
}
