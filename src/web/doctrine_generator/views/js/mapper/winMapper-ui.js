DoctrineGenerator.UI.winMapper = Ext.extend(Ext.Window, {
    title: 'Mapeo',
    width: 500,
    id: 'winMapper',
    height: 325,
    closable: false,
    modal: true,
    checkRenderer: function (pValue) {
        if (pValue == 1)
            return "<center><img src = '../../views/img/checked.gif'/> </center>";
        else
            return "<center><img src = '../../views/img/unchecked.gif'/></center>";
    },
    initComponent: function() {
        this.stFields = new Ext.data.Store ({
            reader: new Ext.data.JsonReader({
                root: 'fields'
            }, ['name', 'length', 'sequence', 'pk', 'type'])
        })

        this.stRelations = new Ext.data.Store ({
            reader: new Ext.data.JsonReader({
                root: 'relations'
            }, ['type', 'ft', 'ff', 'lf', 'it'])
        })

        this.stClasses = new Ext.data.Store ({
            id: 'stClasses',
            url: '../mapper/load_classes',
            reader: new Ext.data.JsonReader({
                root: 'data'
            }, ['clas', 'table'])
        })

        this.cbClasses = new Ext.form.ComboBox ({
            store: this.stClasses,
            fieldLabel: 'Clase',
            displayField: 'clas',
            valueField: 'table',
            triggerAction: 'all',
            width: 206
        })

        this.btnMap = new Ext.Button ({
            text: 'Mapear'
        })

        this.pRelations = new DoctrineGenerator.pRelations ({
            stClasses: this.stClasses,
            stFields: this.stFields,
            stRelations: this.stRelations
        })
        
        this.btnCancel = new Ext.Button ({
            text: 'Cancelar'
        })

        this.buttons = [this.btnCancel, this.btnMap]

        this.items = [
            {
                xtype: 'panel',
                title: '',
                layout: 'form',
                scope: this,
                frame: true,
                height: 298,
                width: 482,
                items: [
                    this.cbClasses,
                    {
                        xtype: 'tabpanel',
                        activeTab: 1,
                        scope: this,
                        id: 'tp',   
                        title: 'Relaciones',
                        height: 262,
                        items: [
                                        {
                                            xtype: 'grid',
                                            title: 'Campos',
                                            store: this.stFields,
                                            height: 234,
                                            columns: [
                                                {
                                                    dataIndex: 'name',
                                                    header: 'Campo',
                                                    sortable: true,
                                                    width: 150
                                                },
                                                {
                                                    dataIndex: 'type',
                                                    header: 'Tipo',
                                                    sortable: true,
                                                    width: 100
                                                },
                                                {
                                                    dataIndex: 'pk',
                                                    header: 'Llave primaria',
                                                    sortable: true,
                                                    renderer: this.checkRenderer,
                                                    width: 100
                                                },
                                                {
                                                    dataIndex: 'sequence',
                                                    header: 'Secuencia',
                                                    sortable: true,
                                                    width: 100
                                                }
                                            ]
                                        },
                            this.pRelations
                        ]
                    }
                ]
            }
        ];
        DoctrineGenerator.UI.winMapper.superclass.initComponent.call(this);
    }
});
