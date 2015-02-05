WSB.UI.wDocblockEditor = Ext.extend(Ext.Window, {
    title: 'Editar comentario',
    width: 443,
    height: 250,
    autoHeight: true,
    bodyStyle:'padding:5px;',
    modal: true,
    closeAction: 'hide',
    initComponent: function() {
        this.cbstore = new Ext.data.Store({
            url: 'getdatatypes',
            reader: new Ext.data.JsonReader({root:'datatypes'},['name']),
            autoLoad: true
        });
        this.gpstore = new Ext.data.Store({
            reader:new Ext.data.JsonReader({
                root:'params'
                },
                [{name: 'param',mapping:'param'},
                 {name: 'type',mapping:'type'},
                 {name: 'description',mapping:'description'}]
            )
        });
        this.combo = new Ext.form.ComboBox({
//            id:'result',
            fieldLabel: 'Tipo de retorno',
            anchor: '100%',
            store: this.cbstore,
            emptyText:"seleccione un tipo de dato...",
            editable:false,
            allowBlank:false,
            valueField:'name',
            displayField:'name',
            hiddenName:'result',
            forceSelection:true,
            typeAhead: true,
            mode: 'local',
            triggerAction: 'all'
        });
        this.textarea = new Ext.form.TextArea({
            name:'description',
            anchor: '100%',
            fieldLabel: 'Descripción'
        });
        this.form = new Ext.FormPanel({
            frame: true,
            autoHeight: true,
            items: [this.combo,this.textarea]
        });
        this.grid = new Ext.grid.EditorGridPanel({
            height: 167,
            autoExpandColumn: 'cdescription',
            loadMask:true,
            frame:true,
            store:this.gpstore,
            clicksToEdit:1,
            columns: [
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'param',
                    header: 'Parámetro',
                    sortable: true,
                    width: 100
                },
                {
                    xtype: 'gridcolumn',
                    header: 'Tipo',
                    dataIndex: 'type',
                    sortable: true,
                    width: 100,
                    editor: new Ext.form.ComboBox({
                        xtype: 'combo',
                        anchor: '100%',
                        store: this.cbstore,
                        emptyText:"seleccione...",
                        allowBlank:false,
                        editable:false,
                        valueField:'name',
                        displayField:'name',
                        hiddenName:'name',
                        forceSelection:true,
                        mode: 'local',
                        triggerAction: 'all'
                    })
                },
                {
                    xtype: 'gridcolumn',
                    header: 'Descripción',
                    dataIndex: 'description',
                    sortable: true,
                    width: 100,
                    id: 'cdescription',
                    editor: new Ext.form.TextField({
                                selectOnFocus:true,
                                value: 0
                            })
                }
            ]
        });
        this.items = [this.form,this.grid];
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
        WSB.UI.wDocblockEditor.superclass.initComponent.call(this);
    }
});
