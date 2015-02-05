WSB.wDocblockEditor = Ext.extend (WSB.UI.wDocblockEditor, {
    initComponent: function(){
        WSB.wDocblockEditor.superclass.initComponent.call (this);
        this.btnAcept.setHandler(function(){this.acept();},this);
        this.btnCancel.setHandler(function(){this.cancel();},this);
    },
    acept: function(){
        if(this.form.getForm().isValid()){
            if(this.validateGrid()){
                if(this.isChanged()){
                    var lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Modificando comentario...'});
                    lMaskk.show();
                    var params = [];
                    this.gpstore.each(function(record){
                        params.push(record.data);
                    },this);
                    Ext.Ajax.request({
                        url: 'modifydocblock',
                        params: {
                                params : Ext.encode(params),
                                data : Ext.encode(this.form.getForm().getValues()),
                                method: this.method,
                                clas: this.clas,
                                src: this.src
                                },
                        callback: function(options,success,response){
                                lMaskk.hide();
                                var responsedata = Ext.decode(response.responseText);
                                if(responsedata.codigo == 1){
                                    mostrarMensaje(1,"El comentario fue modificado con éxito.");
                                }
                                else{
                                    mostrarMensaje(3,"No se pudo modificar el comentario.");
                                }
                            }
                        });
                }
                this.form.getForm().reset();
                this.hide();
            }
            else
                mostrarMensaje(3,"Debe especificar los tipos de dato de todos los parámetros.");
        }
        else
            mostrarMensaje(3,"Debe especificar el tipo de dato retornado.");
    },
    cancel: function(){
        this.form.getForm().reset();
        this.hide();
    },
    validateGrid: function(){
        var flag = true;
        this.gpstore.each(function(record){
            if(record.get('type')==''){
                flag = false;
                return false;
            }
        },this);
        return flag;
    },
    isChanged:function(){
        if(this.combo.getValue() != this.result || this.textarea.getValue() != this.description || this.gpstore.getModifiedRecords().length){
            return true;
        }
        return false;
    },
    loadDocblock: function(src, clas, method){
        var lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Cargando comentario...'});
        lMaskk.show();
        Ext.Ajax.request({
                url: 'loaddocblock',
                method:'POST',
                scope:this,
                params:{src:src,clas:clas,method:method},
                callback: function (options,success,response){
                            lMaskk.hide();
                            var data = Ext.decode(response.responseText);
                            this.gpstore.loadData(data);
                            if(data.result){
                                this.combo.setValue(data.result);
                                this.result= data.result;
                            }
                            this.textarea.setValue(data.description);
                            this.description= this.textarea.getValue();
                        }
        });
        this.src = src;
        this.clas = clas;
        this.method= method;
        this.show();
    }
})