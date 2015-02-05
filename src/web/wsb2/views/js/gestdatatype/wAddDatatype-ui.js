WSB.UI.wAddDatatype = Ext.extend(Ext.Window, {
    title: 'Adicionar tipo de dato',
    width: 313,
    height: 100,
    layout: 'border',
    scope: this,
    closable: false,
    modal: true,
    closeAction: 'hide',
    initComponent: function() {
        this.btnOk = new Ext.Button ({
            text: 'Aceptar',
            icon: perfil.dirImg+'aceptar.png',
            iconCls: 'btn'
        });

        this.btnCancel = new Ext.Button ({
            text: 'Cancelar',
            icon: perfil.dirImg+'cancelar.png',
            iconCls: 'btn'
        });

        this.frmDatatype = new Ext.FormPanel ({
                region: 'center',
                scope: this,
                frame: true,
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Tipo de dato',
                        regex: /^[A-z]([A-z]|[0-9])*/,
                        regexText: 'Valor inválido',
                        anchor: '100%',
                        id: 'type'
                    }
                ]
            });

        this.buttons = [this.btnCancel, this.btnOk];

        this.items = [this.frmDatatype];
        
        WSB.UI.wAddDatatype.superclass.initComponent.call(this);
    }
});
