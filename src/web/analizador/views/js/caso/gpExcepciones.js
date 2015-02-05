/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Analizador.gpExcepciones = Ext.extend (Analizador.UI.gpExcepciones, {
    ok : false,
    modificarExcepcion : function (pFrm, pId) {
        if (pFrm.findById ('tpEstructura').getSelectionModel ().getSelectedNode ()) {
            if (pFrm.findById ('frm_excepcion').getForm ().isValid ()) {
                Ext.Ajax.request ({
                  url : 'modificarExcepcion',
                  scope: this,
                  params: {
                      idcaso : pId,
                      denominacion : pFrm.findById ('denominacion').getValue (),
                      categoria : pFrm.findById ('categoria').getValue (),
                      codigo : pFrm.findById ('codigo').getValue (),
                      mensaje : pFrm.findById ('mensaje').getValue (),
                      respuesta : pFrm.findById ('respuesta').getValue (),
                      estructura: pFrm.findById ('tpEstructura').getSelectionModel ().getSelectedNode ().id
                  },
                success : function (pResp, pOpt)  {
                    pOpt.scope.store.reload ()
                    pOpt.scope.limpiar ()
                    
                    if (pOpt.scope.ok)
                        pOpt.scope.winExcepciones.hide ()
                }
              });
            }
        } else Ext.Msg.show ({
            msg: 'Debe seleccionar una estructura.', 
            title: 'Analizador',
            icon : Ext.MessageBox.ERROR,
            buttons : Ext.MessageBox.OK
        })
    },
    adicionarExcepcion : function (pFrm) {
        if (pFrm.findById ('tpEstructura').getSelectionModel ().getSelectedNode ()) {
            if (pFrm.findById ('frm_excepcion').getForm ().isValid ()) {
                Ext.Ajax.request ({
                  url : 'adicionarExcepcion',
                  scope: this,
                  params: {
                      denominacion : pFrm.findById ('denominacion').getValue (),
                      categoria : pFrm.findById ('categoria').getValue (),
                      codigo : pFrm.findById ('codigo').getValue (),
                      mensaje : pFrm.findById ('mensaje').getValue (),
                      respuesta : pFrm.findById ('respuesta').getValue (),
                      estructura: pFrm.findById ('tpEstructura').getSelectionModel ().getSelectedNode ().id
                  },
                success : function (pResp, pOpt)  {
                    pOpt.scope.store.reload ()
                    pOpt.scope.limpiar ()
                    
                    if (pOpt.scope.ok)
                        pOpt.scope.winExcepciones.hide ()
                }
              });
            }
        } else Ext.Msg.show ({
            msg: 'Debe seleccionar una estructura', 
            title: 'Analizador',
            icon : Ext.MessageBox.ERROR,
            buttons : Ext.MessageBox.OK
        })
    },
    limpiar : function () {
        Ext.getCmp ('frm_excepcion').getForm ().reset ();
        Ext.getCmp ('tpEstructura').getSelectionModel ().clearSelections ()
        Ext.getCmp ('respuesta').setValue("");
    },
    initComponent: function () {
        Analizador.gpExcepciones.superclass.initComponent.call (this)

        this.btnAdd.setHandler (function () {
            this.limpiar()
            
            this.winExcepciones.btnOk.setHandler (function () {
                this.ok = true;
                this.adicionarExcepcion (this.winExcepciones.frmExcepciones)
            }, this)
            
            this.winExcepciones.btnApply.setHandler (function () {
                this.ok = false;
                this.adicionarExcepcion (this.winExcepciones.frmExcepciones)
            }, this)
            
            this.winExcepciones.btnCancel.setHandler (function () {
                this.winExcepciones.hide ()
            }, this)

            this.winExcepciones.show ();
        }, this)
        
        this.btnUpd.setHandler (function () {
            this.winExcepciones.btnOk.setHandler (function () {
                this.modificarExcepcion (this.winExcepciones.frmExcepciones, 
                                         this.getSelectionModel().getSelected().data.idcaso)
            }, this)
            
            this.winExcepciones.btnApply.setHandler (function () {
                this.modificarExcepcion (this.winExcepciones.frmExcepciones)
            }, this)
            
            this.winExcepciones.btnCancel.setHandler (function () {
                this.winExcepciones.hide ()
            }, this)

            this.winExcepciones.show ();
            
            frm = this.winExcepciones.frmExcepciones.findById ('frm_excepcion').getForm ().loadRecord ({
                success: true,
                data: this.getSelectionModel().getSelected().data
            })
            
            this.winExcepciones.frmExcepciones.findById ('respuesta').setValue (this.getSelectionModel().getSelected().data.respuesta)
        }, this)
        
        this.btnRem.setHandler (function () {
            Ext.Msg.show ({
                msg: '¿Est&aacute; seguro de que desea eliminar?',
                title: 'Analizador',
                scope: this,
                buttons: Ext.MessageBox.YESNO,
                icon : Ext.MessageBox.QUESTION,
                fn : function (b) {
                    if (b == 'yes')
                        Ext.Ajax.request ({
                                        url : 'eliminarExcepcion',
                                        params: {
                                            idcaso : this.getSelectionModel().getSelected().data.idcaso
                                        },
                                        scope: this,
                                        success : function (pResp, pOpt) {
                                            pOpt.scope.store.reload ()
                                        }
                                    })
                }
            })
        }, this)
        
        sm = this.getSelectionModel()
        
        sm.on ('selectionchange', function (pSM) {
            this.btnUpd.setDisabled (! pSM.hasSelection ())
            this.btnRem.setDisabled (! pSM.hasSelection ())
        }, this)
    }
})

