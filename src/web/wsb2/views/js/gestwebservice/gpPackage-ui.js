WSB.UI.gpPackage = Ext.extend(Ext.grid.GridPanel, {
    title: 'Servicios web',
    region: 'center',
    autoScroll: true,
    initComponent: function() {
        this.store = new Ext.data.GroupingStore({
            url:"loadservices",
            autoLoad: true,
            reader: new Ext.data.JsonReader({
                    totalProperty: "cantidad_filas",
                    root: "datos",
                    id:'idred'
                    }, [
                   {name: 'nombrepaquete', mapping:'nombrepaquete'},
                   {name: 'nombreservicio', mapping:'nombreservicio'},
                   {name: 'descripcion', mapping:'descripcion'},
                   {name: 'uri', mapping:'uri'},
                   {name: 'estado', mapping:'estado'}
            ]),
            sortInfo:{field: 'nombreservicio', direction: "ASC"},
            groupField:'nombrepaquete'
        });
        this.sm = new Ext.grid.RowSelectionModel({
            singleSelect: true
        });
        this.expander = new Ext.grid.RowExpander({
		tpl : new Ext.Template(
			'<p><b>Descripci&oacute;n:</b> <br/> &nbsp; &nbsp; &nbsp;{descripcion}</p><br/><p><b>Ubicaci&oacute;n:</b> <br/> &nbsp; &nbsp; &nbsp;{uri}</p>'
		)
        });
        this.plugins = this.expander,
        this.columns = [this.expander,
            {
                xtype: 'gridcolumn',
                dataIndex: 'nombreservicio',
                id:'servicename',
                header: 'Nombre',
                sortable: true,
                width: 100
            },
//            {
//                xtype: 'gridcolumn',
//                dataIndex: 'estado',
//                header: 'Estado',
//                sortable: true,
//                width: 100,
//                align: 'right'
//            },
            {
                xtype: 'gridcolumn',
                hidden: true,
                dataIndex: 'nombrepaquete',
                header: 'Paquete',
                sortable: true,
                width: 100
            }
        ];
        this.autoExpandColumn = 'servicename';
        this.view = new Ext.grid.GroupingView({
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Servicios" : "Servicio"]})'
        });
        this.btnRemoveService = new Ext.Button({
            text: 'Eliminar servicio',
            iconCls: 'btn',
            icon: perfil.dirImg+'eliminar.png',
            disabled: true
        });
        this.btnRemovePackage = new Ext.Button({
            text: 'Eliminar paquete',
            iconCls: 'btn',
            icon: perfil.dirImg+'eliminar.png',
            disabled: true
        });
        this.btnTest = new Ext.Button ({
            scope : this,
            text: 'Ver wsdl',
            iconCls: 'btn',
            icon: perfil.dirImg+'ver.png',
            disabled: true
        });
//        this.btnTestService = new Ext.Button({
//            text: 'Probar servicio',
//            iconCls: 'btn',
//            icon: perfil.dirImg+'falta.png',
//            disabled: true
//        });
        this.tbar = [this.btnRemoveService,this.btnRemovePackage, this.btnTest];
        WSB.UI.gpPackage.superclass.initComponent.call(this);
    }
});
