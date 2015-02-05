WSB.gpPackage = Ext.extend (WSB.UI.gpPackage, {
    initComponent: function () {
        WSB.gpPackage.superclass.initComponent.call (this)
        this.btnRemoveService.setHandler(function(){this.removeservice();},this);
        this.btnRemovePackage.setHandler(function(){this.removepackage();},this);
//        this.btnTestService.setHandler(function(){this.testservice();},this);
        this.getSelectionModel().addListener('selectionchange',function(){this.checkButtonsActivation();}, this);
        this.btnTest.setHandler (function () {
          this.test (this.getSelectionModel ().getSelected ().data)
        }, this)
    },
    test: function(pRow){
        window.open (pRow.uri + '?wsdl')
    },
    removeservice: function(){
        Ext.MessageBox.confirm('Eliminar servicio', 'Esta seguro que desea eliminar el servicio seleccionado?',function(btn){
            if(btn=='yes'){
                var lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Eliminando servicio...'});
                lMaskk.show();
                var gpPackages = this;
                var row = this.getSelectionModel().getSelected();
                Ext.Ajax.request({
                    url: 'removeservice',
                    params: {
                        service : row.data.nombreservicio,
                        pack :row.data.nombrepaquete
                    },
                    callback: function(options,success,response){
                        var responsedata = Ext.decode(response.responseText);
                        lMaskk.hide();
                        if(responsedata.codigo == 1){
                            mostrarMensaje(1,"El servicio fue eliminado con &eacute;xito.");
                            gpPackages.getStore().reload();
                        }
                        else{
                            mostrarMensaje(1,"El servicio no pudo ser eliminado.");
                        }
                    }
                });
            }
        },this);
    }
    ,
    removepackage: function(){
        Ext.MessageBox.confirm('Eliminar paquete', 'Esta seguro que desea eliminar el paquete seleccionado?',function(btn){
            if(btn=='yes'){
                var lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Eliminando paquete...'});
                lMaskk.show();
                var gpPackages = this;
                var row = this.getSelectionModel().getSelected();
                Ext.Ajax.request({
                    url: 'removepackage',
                    params: {
                        pack :row.data.nombrepaquete
                    },
                    callback: function(options,success,response){
                        var responsedata = Ext.decode(response.responseText);
                        lMaskk.hide();
                        if(responsedata.codigo == 1){
                            mostrarMensaje(1,"El paquete fue eliminado con &eacute;xito.");
                            gpPackages.getStore().reload();
                        }
                        else{
                            mostrarMensaje(1,"El paquete no pudo ser eliminado.");
                        }
                    }
                });
            }
        },this);
    },
    checkButtonsActivation: function(){
        if(this.getSelectionModel().getSelections().length){
            this.btnRemoveService.enable();
            this.btnRemovePackage.enable();
            this.btnTest.enable();
        }
        else{
            this.btnRemoveService.disable();
            this.btnRemovePackage.disable();
            this.btnTest.disable();
        }
    }
})