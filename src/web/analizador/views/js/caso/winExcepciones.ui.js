Analizador.UI.winExcepciones = Ext.extend(Ext.Window, {
    title: 'Excepciones',
    height: 377,
    closable: false,
    modal: true,
    initComponent: function() {
        this.frmExcepciones = new  Analizador.frmExcepciones ({
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
            this.frmExcepciones
        ];
        Analizador.UI.winExcepciones.superclass.initComponent.call(this);
    }
});
