Updater.ComboBox = Ext.extend (Ext.form.ComboBox, {
    initComponent : function () {
        this.store = new Ext.data.Store ({
           url: 'version',
           reader: new Ext.data.JsonReader ({
               root: 'data',
               fields: ['version']
           })
        });

        this.displayField = 'version';
        this.valueField = 'version';
        this.width = 100;
        this.triggerAction = 'all';        

        Updater.ComboBox.superclass.initComponent.call (this);

        this.on ('select', function (c, r, i) {
            this.scope.gp.getStore ().load ({
                params : {version : r.data.version}
            })
        });
    }
});

Updater.GridPanel = Ext.extend (Ext.grid.GridPanel, {
    initComponent : function () {
        this.height = 200
        this.tbar = ['->', 'Versi&oacute;n: ', this.cb]
        this.autoExpandColumn = 'modulo'
        
        this.store = new Ext.data.Store ({
            url : 'load',
            reader : new Ext.data.JsonReader ({
                root : 'data'
            }, ['idmodulo', 'modulo'])
        })
        
        this.sm = new Ext.grid.CheckboxSelectionModel ({
            singleSelection : true
        })
        
        this.columns = [this.sm,
                        {header : 'M&oacute;dulo', dataIndex : 'modulo', id : 'modulo'}]
        
        Updater.GridPanel.superclass.initComponent.call (this)
    }
})

Updater.Window = Ext.extend (Ext.Window, {
    updateApp : function (pModules, pVersion, pTotal, pVersiones) {
      module = pModules.shift ()
      
      if (module) {
          Ext.Ajax.request ({
              url: 'update',
              scope : this,
              params: {
                  user: Ext.getCmp ('user').getValue (),
                  passwd: Ext.getCmp ('passwd').getValue (),
                  version: pVersion,
                  module: module.idmodulo
              }, 
              success : function (r, o) {
                  o.scope.updateApp (pModules, pVersion, pTotal, pVersiones)
              }
           });
      } else {
          if (pVersiones) {
             modulos = [];
             selected = this.gp.getSelectionModel ().getSelections ()

             for (i = 0; i < selected.length; i++) {
                modulos.push (selected [i].data)
             }
             
             this.updateVersion (pVersiones, modulos, pTotal)
          }
      }
    },
    updateVersion : function (pVersiones, pModules, pTotal) {
        cant = pTotal - pVersiones.length     
        percent = (cant * 100/pTotal) / 100
        
        version = pVersiones.shift ()
                
        if (version) {
           this.winProgressBar.updateProgress (percent, 'Actualizando a la versi&oacute;n' + version);
           this.updateApp (pModules, version, pTotal, pVersiones)
        } else {
            this.winProgressBar.hide ()
            this.updateXml (this.cb.getValue ())
        }
    },
    updateXml : function (pVersion) {
      Ext.Ajax.request ({
          url : 'xml',
          params: {
              version : pVersion
          },
          success: function () {
              Ext.Msg.show ({
                  msg: 'Actualizaci&oacute;n concluida correctamente',
                  title : 'Actualizador CEDRUX',
                  icon: Ext.MessageBox.INFO,
                  buttons: Ext.MessageBox.OK
              })
          }
      }) 
    },
    initComponent: function () {
        this.title = 'Actualizador CEDRUX';
        this.modal = true;
        this.closable = false;
        this.width = 300
        this.winProgressBar = new Updater.winProgress ();
        
        fp = new Ext.FormPanel({
            fileUpload: true,
            width: 300,
            frame: true,
            autoHeight: true,
            bodyStyle: 'padding: 10px 10px 0 10px;',
            labelWidth: 60,
            defaults: {
                anchor: '95%'
            },
            items: [{
                xytpe: 'fieldset',
                defaults: {layout: 'form'},
                items: [{
                    defaults: {anchor: '95%'},
                    items: [
                            {value: 'postgres', xtype: 'textfield', fieldLabel: 'Usuario', id: 'user'},
                            {value: 'postgres', xtype: 'textfield', fieldLabel: 'Contrase&ntilde;a', inputType: 'password', id: 'passwd'},
                            {xtype: 'fileuploadfield', id: 'file',  emptyText: 'Comprimido', fieldLabel: 'Archivo', name: 'src-file', buttonText: '...'}
                           ]
                }]
            }],
            buttons: [{
                text: 'Subir fichero',
                handler: function(){
                    if(fp.getForm().isValid()){
                            fp.getForm().submit({
                                url: 'upload',
                                waitMsg: 'Subiendo fichero ...'
                            });
                    }
                }
            }]
        });

        this.cb = new Updater.ComboBox ({
            scope: this
        });
        
        this.gp = new Updater.GridPanel ({cb : this.cb})

        this.items = [fp, this.gp];
        this.buttons = [new Ext.Button({
           text: 'Actualizar',
           scope : this,
           handler: function () {
               Ext.Ajax.request ({
                   url : 'versiones',
                   scope: this,
                   params : {
                       last_version: this.cb.getValue ()
                   }, 
                   success : function (r, o) {
                       versiones = Ext.decode (r.responseText)
                       o.scope.winProgressBar.show ()
                       
                       modulos = [];
                       selected = o.scope.gp.getSelectionModel ().getSelections ()
                       
                       for (i = 0; i < selected.length; i++) {
                           modulos.push (selected [i].data)
                       }
                       
                       this.updateVersion (versiones, modulos, versiones.length)
                   }
               })
           }
        })];
        Updater.Window.superclass.initComponent.call (this);
    }
});


function cargarInterfaz(){
    new Updater.Window ().show ();
}
cargarInterfaz();
