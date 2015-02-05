DoctrineGenerator.Main = Ext.extend (DoctrineGenerator.UI.Main, {
    initComponent: function () {
        DoctrineGenerator.Main.superclass.initComponent.call (this)

        this.btnNewProject.setHandler (function () {
            this.winCreateProject.show ();
        }, this);

        this.btnSaveProject.setHandler (function () {
            window.open ('download');
        }, this)

        this.btnOpenProject.setHandler (function () {
            this.winOpenProject.show ()
        }, this)
    }
})

new DoctrineGenerator.Main ();