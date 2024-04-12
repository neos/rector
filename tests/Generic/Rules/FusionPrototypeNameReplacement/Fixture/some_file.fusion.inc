prototype(Neos.Neos:SomethingOld)  < prototype(Neos.Neos:Raw) {

    raw = Neos.Neos:Raw
    rawer = Neos.Neos:Rawer

    renderer = Neos.Neos:Raw {

    old = Neos.Neos:SomethingOld

    renderer = afx`
        <Neos.Neos:SomethingOld foo=""></Neos.Neos:SomethingOld>
        <Neos.Neos:Rawer />
        <Neos.Neos:Raw />
    `
    }
}
-----
prototype(Neos.Neos:SomethingNew)  < prototype(Neos.Neos:NewRaw) {

    raw = Neos.Neos:NewRaw
    rawer = Neos.Neos:Rawer

    renderer = Neos.Neos:NewRaw {

    old = Neos.Neos:SomethingNew

    renderer = afx`
        <Neos.Neos:SomethingNew foo=""></Neos.Neos:SomethingNew>
        <Neos.Neos:Rawer />
        <Neos.Neos:NewRaw />
    `
    }
}