prototype(Neos.Neos:SomethingOld)  < prototype(Neos.Neos:Raw) {

    foo = Neos.Neos:Foo

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
// Neos.Neos:Raw: Add this comment to top of file.
// Neos.Neos:Foo: Add this comment to top of file.
prototype(Neos.Neos:SomethingOld)  < prototype(Neos.Neos:Raw) {

    foo = Neos.Neos:Foo

    renderer = Neos.Neos:Raw {

        old = Neos.Neos:SomethingOld

        renderer = afx`
            <Neos.Neos:SomethingOld foo=""></Neos.Neos:SomethingOld>
            <Neos.Neos:Rawer />
            <Neos.Neos:Raw />
        `
    }
}
