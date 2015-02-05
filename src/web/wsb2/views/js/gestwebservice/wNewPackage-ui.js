WSB.UI.wNewPackage = Ext.extend(Ext.Window, {
    title: 'Nuevo paquete de servicios',
    width: 400,
    height: 250,
    layout: 'fit',
    autoHeight: true,
    modal: true,
    closeAction: 'hide',
    initComponent: function() {
        this.form = new Ext.FormPanel({
            frame: true,
            labelWidth: 80,
            autoHeight: true,
            items: [
                {
                    xtype: 'textfield',
                    fieldLabel: 'Nombre',
                    anchor: '100%',
                    allowBlank: false,
                    blankText : 'Este campo es obligatorio.',
                    id: 'name'
                },
                {
                    xtype: 'textfield',
                    fieldLabel: 'Autor',
                    anchor: '100%',
                    id: 'author'
                },
                {
                    xtype: 'textarea',
                    anchor: '100%',
                    fieldLabel: 'Descripción',
                    id: 'description'
                }
            ]
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
        WSB.UI.wNewPackage.superclass.initComponent.call(this);
    }
});
