WSB.UI.vpDatatype = Ext.extend(Ext.Viewport, {
    layout: 'border',
    initComponent: function() {
        this.btnAddDatatype = new Ext.Button ({
            icon: perfil.dirImg+'adicionar.png',
            text: 'Adicionar',
            iconCls: 'btn'
        })

    	this.btnUpdDatatype = new Ext.Button ({
            icon: perfil.dirImg+'modificar.png',
            text: 'Modificar',
            iconCls: 'btn',
            disabled: true
    	})

    	this.btnRemDatatype = new Ext.Button ({
            icon: perfil.dirImg+'eliminar.png',
            text: 'Eliminar',
            iconCls: 'btn',
            disabled: true
    	})

    	this.btnAddField = new Ext.Button ({
            text: 'Adicionar',
            icon: perfil.dirImg+'adicionar.png',
            iconCls: 'btn',
            disabled: true
    	})
    	
    	this.btnUpdField = new Ext.Button ({
            text: 'Modificar',
            icon: perfil.dirImg+'modificar.png',
            iconCls: 'btn',
            disabled: true
    	})
    	
    	this.btnRemField = new Ext.Button ({
            text: 'Eliminar',
            icon: perfil.dirImg+'eliminar.png',
            iconCls: 'btn',
            disabled: true
    	})  

        this.btnClose = new Ext.Button ({
            text: 'Cerrar'
    	})

    	this.stField = new Ext.data.Store ({
            url: 'fields',
            reader: new Ext.data.JsonReader ({
                    root: 'fields'
            }, ['name', 'datatype'])
    	})

    	this.stDatatype = new Ext.data.Store ({
            url: 'datatypes',
            reader: new Ext.data.JsonReader ({
                    root: 'datatypes'
            }, ['name', 'fields'])
    	})

        this.wAddDatatype = new WSB.wAddDatatype ({
            scope: this
        })

        this.wAddField = new WSB.wAddField ({
            scope: this
        })

        this.buttons = [this.btnClose]

        this.smField  = new Ext.grid.RowSelectionModel ({scope: this})
        this.smDatatype = new Ext.grid.RowSelectionModel ({scope: this})
    	
        this.items = [
            {
                xtype: 'grid',
                title: 'Campo',
                height: 172,
                scope: this,
                region: 'center',
                sm: this.smField,
                store: this.stField,
                autoExpandColumn: 'field',
                listeners: {
                    rowclick : function (g, i) {
                        g.scope.index = i
                    }
                },
                columns: [
                    {
                        dataIndex: 'name',
                        header: 'Campo',
                        sortable: true,
                        width: 100,
                        id: 'field'
                    },
                    {   
                        dataIndex: 'datatype',
                        header: 'Tipo',
                        sortable: true,
                        width: 100,
                        align: ''
                    }
                ],
                tbar: [this.btnAddField, this.btnUpdField, this.btnRemField]
            },
            {
                xtype: 'grid',
                title: 'Tipo de dato',
                region: 'west',
                width: 230,
                autoExpandColumn: 'datatype',
                sm: this.smDatatype,
                store: this.stDatatype,
                columns: [
                    {
                        dataIndex: 'name',
                        header: 'Tipo de dato',
                        sortable: true,
                        width: 100,
                        id: 'datatype'
                    }
                ],
                tbar: [this.btnAddDatatype, this.btnUpdDatatype, this.btnRemDatatype]
            }
        ];
        
        WSB.UI.vpDatatype.superclass.initComponent.call(this);
    }
});
