WSB.vpProject = Ext.extend (WSB.UI.vpProject, {
    initComponent: function () {
        WSB.vpProject.superclass.initComponent.call (this)
        this.tpIoC.wNewPackage.btnAcept.setHandler(function(){this.newPackage();},this);
        this.tpIoC.wNewService.btnAcept.setHandler(function(){this.newService();},this);
        this.gpError.on("rowclick",function(grid,rowIndex,e){
            var datos = grid.getStore().getAt(rowIndex).data;
            this.tpIoC.wDocblockEditor.loadDocblock(datos.src,datos.clas,datos.method);
        },this);
    },
    newPackage: function(){
        if(this.tpIoC.wNewPackage.form.getForm().isValid()){
            var selNodes = this.tpIoC.getChecked();
            var subsystems = [];
            var services = [];
            var classes = [];
            var methods = [];
            var srcs = [];
            var selection = [];
            Ext.each(selNodes, function(node){
                subsystems.push(node.parentNode.text);
                services.push(node.text);
                classes.push(node.attributes.clas);
                methods.push(node.attributes.method);
                srcs.push(node.attributes.src);
            });
            selection.push(services);
            selection.push(subsystems);
            selection.push(classes);
            selection.push(methods);
            selection.push(srcs);
            var lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Creando paquete de servicios...'});
            lMaskk.show();
            Ext.Ajax.request({
                url: 'generatepackage',
                scope: this,
                params: {
                        selection : Ext.encode(selection),
                        data : Ext.encode(this.tpIoC.wNewPackage.form.getForm().getValues())
                        },
                callback: function(options,success,response){
                        lMaskk.hide();
                        var responsedata = Ext.decode(response.responseText);
                        if(responsedata.codigo == 1){
                            mostrarMensaje(1,"El paquete fue creado con &eacute;xito.");
                            this.gpPackage.getStore().reload();
                            this.tpIoC.wNewService.store.reload();
                            Ext.each(selNodes, function(node){
                               node.ui.toggleCheck();
                            });
                        }
                        else{
                            mostrarMensaje(3,"Debe especificar los par&aacute;metros y tipo de retorno de los m&eacute;todos a exportar como servicios web.");
                            this.gpError.getStore().loadData(responsedata);
                            this.gpError.expand(true);
                        }
                        this.tpIoC.wNewPackage.form.getForm().reset();
                        this.tpIoC.wNewPackage.hide();
                    }
                }
            );
        }
    },

    newService : function(){
        var lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Creando servicio...'});
        lMaskk.show();
        var gpPackages = this.gpPackage;
        var tpIoC = this.tpIoC;
        Ext.Ajax.request({
            url: 'addservice',
            scope: this,
            params: {
                    pack : this.tpIoC.wNewService.combo.getValue (),
                    service :this.tpIoC.clickedNode.text,
                    subsystem : this.tpIoC.clickedNode.parentNode.text,
                    clas : this.tpIoC.clickedNode.attributes.clas,
                    method : this.tpIoC.clickedNode.attributes.method,
                    src : this.tpIoC.clickedNode.attributes.src
                    },
            callback: function(options,success,response){
                    lMaskk.hide();
                    var responsedata = Ext.decode(response.responseText);
                    if(responsedata.codigo == 1){
                        mostrarMensaje(1,"El servicio fue adicionado con &eacute;xito.");
                        gpPackages.getStore().reload();
                        tpIoC.wNewService.store.reload();
                        var selNodes = tpIoC.getChecked();
                        Ext.each(selNodes, function(node){
                           node.ui.toggleCheck();
                        });
                    }
                    else if(responsedata.codigo == 2){
                        mostrarMensaje(3,"Debe especificar los par&aacute;metros y tipo de retorno de los m&eacute;todos a exportar como servicios web.");
                        this.gpError.getStore().loadData(responsedata);
                        this.gpError.expand(true);
                    }
                    else {
                        mostrarMensaje(3,"Ha ocurrido un error interno del sistema.");
                    }
                    tpIoC.wNewService.form.getForm().reset();
                    tpIoC.wNewService.hide();
                }
        });
    }
});
new WSB.vpProject ();


