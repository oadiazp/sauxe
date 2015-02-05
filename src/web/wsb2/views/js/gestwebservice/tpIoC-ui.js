WSB.UI.tpIoC = Ext.extend(Ext.tree.TreePanel, {
    title: 'Servicios internos',
    region: 'west',
    width: 240,
    rootVisible: false,
    autoScroll: true,
    initComponent: function() {
        this.root = {
            id:'0',
            text: 'Tree Node'
        };
        this.loader = {
            url:'loadioc'
        };

//        this.wMngDatatype= new WSB.wMngDatatype ()

        this.btnGenerate = new Ext.Button( {
            text: 'Crear paquete de servicios',
            iconCls: 'btn',
            icon: perfil.dirImg+'exportar.png',
            disabled: true
        });

//        this.btnCreateDatatype = new Ext.Button ({
//            text: 'Crear tipo de dato'
//        })

        this.tbar =[this.btnGenerate];
        this.wNewPackage = new WSB.wNewPackage();
        this.wNewService = new WSB.wNewService();
        this.wDocblockEditor = new WSB.wDocblockEditor();
        this.menuComent = new Ext.menu.Item( {
            text: 'Editar comentario'
        });
        this.menuNewService = new Ext.menu.Item( {
            text: 'Exportar como servicio web'
        });
        this.menu = new Ext.menu.Menu({
            items: [this.menuNewService,this.menuComent]
        });
        WSB.UI.tpIoC.superclass.initComponent.call(this);
    }
});
