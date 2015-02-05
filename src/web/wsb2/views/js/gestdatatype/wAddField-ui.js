WSB.UI.wAddField = Ext.extend(Ext.Window, {
    title: 'Campos',
    width: 310,
    height: 128,
    layout: 'border',
    modal: true,
    closeAction: 'hide',
    closable: false,
    initComponent: function() {
        this.btnOk = new Ext.Button ({
            text: 'Aceptar',
            iconCls: 'btn',
            icon: perfil.dirImg+'aceptar.png'
        });

        this.btnCancel = new Ext.Button ({
            text: 'Cancelar',
            icon: perfil.dirImg+'cancelar.png',
            iconCls: 'btn'
        });

        this.stDatatype = new Ext.data.Store ({
            url: 'datatypes',
            reader: new Ext.data.JsonReader ({
                    root: 'datatypes'
            }, ['name', 'fields'])
    	});

        this.frmField = new Ext.FormPanel ({
                region: 'center',
                scope: this,
                frame: true,
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Campo',
                        anchor: '100%',
                        regex: /^[A-z]([A-z]|[0-9])*/,
                        regexText: 'Valor inválido',
                        id: 'name'
                    },
                    {
                        xtype: 'combo',
                        fieldLabel: 'Tipo de dato',
                        anchor: '100%',
                        store: this.stDatatype,
                        displayField: 'name',
                        valueField: 'name',
                        triggerAction:'all',
                        mode: 'local',
                        id: 'datatype'
                    }
                ]
            });

        this.buttons = [this.btnCancel, this.btnOk]
        this.items = [
            this.frmField
        ];
        WSB.UI.wAddField.superclass.initComponent.call(this);
    }
});
