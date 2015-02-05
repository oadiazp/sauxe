WSB.UI.wNewService = Ext.extend(Ext.Window, {
    title: 'Nuevo servicio',
    width: 300,
    height: 250,
    layout: 'fit',
    autoHeight: true,
    modal: true,
    closeAction: 'hide',
    initComponent: function() {
        this.store = new Ext.data.Store({
            url: 'loadpackages',
            reader: new Ext.data.JsonReader({root:'data'},['nombrepaquete']),
            autoLoad: true
        });
        this.combo = new Ext.form.ComboBox({
            emptyText:"seleccione un paquete...",
            editable:false,
            fieldLabel:'Paquete',
            store:this.store,
            valueField:'nombrepaquete',
            displayField:'nombrepaquete',
            hiddenName:'nombrepaquete',
            forceSelection:true,
            typeAhead: true,
            mode: 'local',
            triggerAction: 'all',
            anchor:'100%'
        });
        this.form = new Ext.FormPanel({
            frame: true,
            labelWidth: 60,
            autoHeight: true,
            items: [this.combo]
        });
        this.items = [this.form];
        this.btnAcept = new Ext.Button({
                xtype: 'button',
                text: '<b>Aceptar</b>',
                iconCls: 'btn',
                icon: perfil.dirImg+'aceptar.png'
        });
        this.btnCancel = new Ext.Button({
                xtype: 'button',
                text: '<b>Cancelar</b>',
                icon: perfil.dirImg+'cancelar.png',
                iconCls: 'btn'
        });
        this.buttons = [this.btnCancel,this.btnAcept];
        WSB.UI.wNewService.superclass.initComponent.call(this);
    }
});