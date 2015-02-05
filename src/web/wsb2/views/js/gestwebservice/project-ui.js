WSB.UI.vpProject = Ext.extend (Ext.Viewport, {
    layout: 'border',
    initComponent: function () {
        this.tpIoC = new WSB.tpIoC();
        this.gpPackage = new WSB.gpPackage();
        this.gpError = new Ext.grid.GridPanel ({
            title: 'Errores',
            collapsible: true,
            collapsed:true,
            autoExpandColumn: 'colmessage',
            store: new Ext.data.Store ({
                reader:new Ext.data.JsonReader(
                        {
                        totalProperty: "cantidad_filas",
                        root: "errors",
                        id: "idre"
                        },
                        [{name: 'type',mapping:'type'},
                         {name: 'message',mapping:'message'},
                         {name: 'clas',mapping:'clas'},
                         {name: 'method',mapping:'method'},
                         {name: 'src',mapping:'src'}]
                        )
            }),
            region: 'south',
            height: 150,
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: true
            }),
            columns: [{dataIndex:'type',header: 'Error',width: 200},
                      {dataIndex:'message',id:'colmessage',header:'Descripción'}]
        });
        this.items = [this.tpIoC, this.gpPackage, this.gpError];
        WSB.UI.vpProject.superclass.initComponent.call(this);
    }
})