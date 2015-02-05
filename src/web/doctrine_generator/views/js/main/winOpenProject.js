DoctrineGenerator.winOpenProject = new Ext.extend (DoctrineGenerator.UI.winOpenProject, {
    initComponent: function () {
        DoctrineGenerator.winOpenProject.superclass.initComponent.call (this)

        this.btnOpen.setHandler (function () {
            this.open ()
        }, this)

        this.btnCancel.setHandler (function () {
            this.hide ()
        }, this)
    },
    open: function () {
        fp = Ext.getCmp ('fpUpload').getForm ()
        fp.scope = this

        if (fp.isValid ())
            fp.submit ({
                url: 'open',
                success: function (f) {
                    Ext.getCmp ('winMapper').show ()
                    f.scope.hide ()
                }
            })
    }
})