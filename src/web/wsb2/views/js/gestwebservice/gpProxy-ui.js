WSB.UI.gpProxy = Ext.extend(Ext.grid.GridPanel, {
    title: 'Proxies',
    region: 'east',
    width: 200,
    initComponent: function() {
        this.columns = [
            {
                xtype: 'gridcolumn',
                dataIndex: 'string',
                header: 'Proxy',
                sortable: true,
                width: 100
            },
            {
                xtype: 'numbercolumn',
                dataIndex: 'number',
                header: 'Servicios',
                sortable: true,
                width: 100,
                align: 'right'
            }
        ];
        this.tbar = {
            xtype: 'toolbar',
            items: [
                {
                    xtype: 'button',
                    text: 'Adicionar'
                },
                {
                    xtype: 'button',
                    text: 'Modificar'
                },
                {
                    xtype: 'button',
                    text: 'Eliminar'
                }
            ]
        };
        WSB.UI.gpProxy.superclass.initComponent.call(this);
    }
});
