WSB.wNewPackage = Ext.extend (WSB.UI.wNewPackage, {
    initComponent: function() {
        WSB.wNewPackage.superclass.initComponent.call (this);
        this.btnCancel.setHandler(function(){this.cancel();},this);
    },
    cancel: function(){
        this.form.getForm().reset();
        this.hide();
    }
})