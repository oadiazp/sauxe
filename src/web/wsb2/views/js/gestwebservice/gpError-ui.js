WSB.UI.gpError = Ext.extend(Ext.grid.GridPanel, {
    title: 'Errores',
    region: 'south',
    autoScroll: true,
//    split:true,
    height: 150,
//    minSize: 100,
//    maxSize: 200,
//    borders: true,
//    margins:'0 0 0 0',
//    collapsible: true,
//    collapsed:true,
    autoExpandColumn: 'colmessage',
    initComponent: function() {
        this.store = new Ext.data.Store({
            reader:new Ext.data.JsonReader({
                root:'errors'
                },
                [{name: 'type',mapping:'type'},
                 {name: 'message',mapping:'message'},
                 {name: 'clas',mapping:'clas'},
                 {name: 'method',mapping:'method'},
                 {name: 'src',mapping:'src'}
             ]
            )
        });
        this.sm = new Ext.grid.RowSelectionModel({
            singleSelect: true
        });
        this.columns = [
            {dataIndex:'type',header: 'Error',width: 200},
            {dataIndex:'message',id:'colmessage',header:'Descripción'}
        ];
        WSB.UI.gpError.superclass.initComponent.call(this);
    }
});
