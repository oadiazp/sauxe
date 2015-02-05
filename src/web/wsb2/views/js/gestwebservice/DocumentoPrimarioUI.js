DocumentoPrimarioUi = Ext.extend(Ext.Window, {
    width: 400,
    layout: 'anchor',
    autoHeight: true,
    autoWidth: true,
    initComponent: function() {
        this.title = this.data.nombre;
        this.items  = [];
        this.fields = [];
        for (n in this.data.cabecera){
            this.fields.push({
                xtype: 'displayfield',
                value: this.data.cabecera[n],
                fieldLabel: n,
                boxMaxWidth: 300,
                anchor: '100%'
            });
        }
        if(this.fields.length){
            this.cabecera = new Ext.FormPanel({
                autoScroll: true,
                boxMaxHeight: 250,
                autoWidth: true,
                frame: true,
                items: this.fields,
                anchor: '100%'
            });
            this.items.push(this.cabecera);
        }
        this.columns = [];
        this.storefields = [];
        for (n in this.data.coleccion[0]){
            this.columns.push(
                {
                    dataIndex: n,
                    header: n,
                    sortable: true
                }
            );
            this.storefields.push(
                {
                    name: n
                }
            );
        }
        if(this.columns.length){
            this.coleccion = new Ext.grid.GridPanel({
                title: this.data.nombrecol,
                //autoHeight: true,
                height: 250,
                boxMaxHeight: 400,
                columns: this.columns,
                store: {
                            xtype: 'jsonstore',
                            storeId: 'MyStore',
                            fields: this.storefields
                        },
                anchor: '100%'
            });
            this.items.push(this.coleccion);
            this.coleccion.getStore().loadData(this.data.coleccion);
        }
        DocumentoPrimarioUi.superclass.initComponent.call(this);
        this.show();
    }
});


