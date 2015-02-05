/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Analizador.gpRendimiento = Ext.extend (Analizador.UI.gpRendimiento, {
    ok : false,
    modificarRendimiento : function (pFrm, pId) {
        tmax = pFrm.findById ('rtiempomax').getValue ()
        tmin = pFrm.findById ('rtiempo').getValue ()
        mmax = pFrm.findById ('rmemoriamax').getValue ()
        mmin = pFrm.findById ('rmemoria').getValue ()
        cond1 = false; cond2 = false;
        
        if (tmin && tmax) {
            if (tmin >= tmax) {
                cond1 = true;
            }
        }
        
        if (mmin && mmax) {
            if (mmin >= mmax) {
                cond2 = true;
            }
        }
        
        if (cond1 || cond2) {
            Ext.Msg.show ({
                msg: 'Intervalos inv&aacute;lidos', 
                title: 'Analizador',
                icon : Ext.MessageBox.ERROR,
                buttons : Ext.MessageBox.OK
            })
            
            return;
        }
        
        if (pFrm.findById ('tprEstructura').getSelectionModel ().getSelectedNode ()) {
            if (pFrm.findById ('frm_Rendimiento').getForm ().isValid ()) {
                Ext.Ajax.request ({
                  url : 'modificarRendimiento',
                  scope: this,
                  params: {
                      idcaso : pId,
                      denominacion : pFrm.findById ('rdenominacion').getValue (),
                      categoria : pFrm.findById ('rcategoria').getValue (),
                      memoria : pFrm.findById ('rmemoria').getValue (),
                      tiempo : pFrm.findById ('rtiempo').getValue (),
                      memoriamax : pFrm.findById ('rmemoriamax').getValue (),
                      tiempomax : pFrm.findById ('rtiempomax').getValue (),
                      respuesta : pFrm.findById ('rrespuesta').getValue (),
                      estructura: pFrm.findById ('tprEstructura').getSelectionModel ().getSelectedNode ().id
                  },
                success : function (pResp, pOpt)  {
                    pOpt.scope.store.reload ()
                    pOpt.scope.limpiar ();
                    if (pOpt.scope.ok)
                        pOpt.scope.winRendimiento.hide ()
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
    adicionarRendimiento : function (pFrm) {
        tmax = pFrm.findById ('rtiempomax').getValue ()
        tmin = pFrm.findById ('rtiempo').getValue ()
        mmax = pFrm.findById ('rmemoriamax').getValue ()
        mmin = pFrm.findById ('rmemoria').getValue ()
        cond1 = false; cond2 = false;
        
        if (tmin && tmax) {
            if (tmin >= tmax) {
                cond1 = true;
            }
        }
        
        if (mmin && mmax) {
            if (mmin >= mmax) {
                cond2 = true;
            }
        }
        
        if (cond1 || cond2) {
            Ext.Msg.show ({
                msg: 'Intervalos inv&aacute;lidos', 
                title: 'Analizador',
                icon : Ext.MessageBox.ERROR,
                buttons : Ext.MessageBox.OK
            })
            
            return;
        }
        
        if (pFrm.findById ('tprEstructura').getSelectionModel ().getSelectedNode ()) {
            if (pFrm.findById ('frm_Rendimiento').getForm ().isValid ()) {
                Ext.Ajax.request ({
                  url : 'adicionarRendimiento',
                  scope: this,
                  params: {
                      denominacion : pFrm.findById ('rdenominacion').getValue (),
                      categoria : pFrm.findById ('rcategoria').getValue (),
                      memoria : pFrm.findById ('rmemoria').getValue (),
                      tiempo : pFrm.findById ('rtiempo').getValue (),
                      memoriamax : pFrm.findById ('rmemoriamax').getValue (),
                      tiempomax : pFrm.findById ('rtiempomax').getValue (),
                      respuesta : pFrm.findById ('rrespuesta').getValue (),
                      estructura: pFrm.findById ('tprEstructura').getSelectionModel ().getSelectedNode ().id
                  },
                success : function (pResp, pOpt)  {
                    pOpt.scope.store.reload ()
                    pOpt.scope.limpiar ();
                    if (pOpt.scope.ok)
                        pOpt.scope.winRendimiento.hide ()
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
    limpiar : function () {
        Ext.getCmp ('frm_Rendimiento').getForm ().reset ();
        Ext.getCmp ('tprEstructura').getSelectionModel ().clearSelections ()
        Ext.getCmp ('rrespuesta').setValue("");
    },
    initComponent: function () {
        Analizador.gpRendimiento.superclass.initComponent.call (this)

        this.btnAdd.setHandler (function () {
            this.limpiar()
            
            this.winRendimiento.btnOk.setHandler (function () {
                this.ok = true
                this.adicionarRendimiento (this.winRendimiento.frmRendimiento)
            }, this)
            
            this.winRendimiento.btnApply.setHandler (function () {
                this.ok = false
                this.adicionarRendimiento (this.winRendimiento.frmRendimiento)
            }, this)
            
            this.winRendimiento.btnCancel.setHandler (function () {
                this.winRendimiento.hide ()
            }, this)

            this.winRendimiento.show ();
        }, this)
        
        this.btnUpd.setHandler (function () {
            this.winRendimiento.btnOk.setHandler (function () {
                this.ok = true
                this.modificarRendimiento (this.winRendimiento.frmRendimiento, 
                                           this.getSelectionModel().getSelected().data.idcaso)
            }, this)
            
            this.winRendimiento.btnApply.setHandler (function () {
                this.ok = false
                this.adicionarRendimiento (this.winRendimiento.frmRendimiento)
            }, this)
            
            this.winRendimiento.btnCancel.setHandler (function () {
                this.winRendimiento.hide ()
            }, this)

            this.winRendimiento.show ();
            
            frm = this.winRendimiento.frmRendimiento.findById ('frm_Rendimiento').getForm ().loadRecord ({
                success: true,
                data: {
                    rdenominacion : this.getSelectionModel().getSelected().data.denominacion,
                    rcategoria : this.getSelectionModel().getSelected().data.categoria,
                    rmemoria: this.getSelectionModel().getSelected().data.memoriamin,
                    rtiempo: this.getSelectionModel().getSelected().data.tiempomin,
                    rmemoriamax: this.getSelectionModel().getSelected().data.memoriamax,
                    rtiempomax: this.getSelectionModel().getSelected().data.tiempomax
                }
            })
            
            this.winRendimiento.frmRendimiento.findById ('rrespuesta').setValue (this.getSelectionModel().getSelected().data.respuesta)
        }, this)
        
        this.btnRem.setHandler (function () {
            Ext.Msg.show ({
                msg: '¿Est&aacute; seguro de que desea eliminar?',
                title: 'Analizador',
                buttons: Ext.MessageBox.YESNO,
                scope: this,
                icon : Ext.MessageBox.QUESTION,
                fn : function (b) {
                    if (b == 'yes')
                        Ext.Ajax.request ({
                                            url : 'eliminarRendimiento',
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