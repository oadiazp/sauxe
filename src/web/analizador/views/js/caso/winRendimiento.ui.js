Analizador.UI.winRendimiento = Ext.extend(Ext.Window, {
    title: 'Rendimiento',
    height: 400,
    closable: false,
    modal: true,
    initComponent: function() {
        this.frmRendimiento = new  Analizador.frmRendimiento ({
            scope: this
        })

        this.btnOk = new Ext.Button ({
            scope: this,
            text : 'Aceptar'
        })

        this.btnApply = new Ext.Button ({
            scope: this,
            text : 'Aplicar'
        })

        this.btnCancel = new Ext.Button ({
            scope: this,
            text : 'Cancelar'
        })

        this.buttons = [this.btnCancel, this.btnApply, this.btnOk]

        this.items = [
            this.frmRendimiento
        ];
        Analizador.UI.winRendimiento.superclass.initComponent.call(this);
    }
});
