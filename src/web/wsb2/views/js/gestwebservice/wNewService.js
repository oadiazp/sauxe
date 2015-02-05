WSB.wNewService = Ext.extend (WSB.UI.wNewService, {
    initComponent: function() {
        WSB.wNewService.superclass.initComponent.call (this);
        this.btnCancel.setHandler(function(){this.cancel();},this);
    },
    cancel: function(){
        this.form.getForm().reset();
        this.hide();
    }
})