/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


Analizador.gpActivar = Ext.extend(Analizador.UI.gpActivar, {
    toggle : function (pRow) {
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Espere ..."});
        myMask.show();
        
        Ext.Ajax.request ({
            url : 'cambiar',
            params : pRow,
            scope: this,
            success : function (pResp, pOpt) {
                pOpt.scope.store.reload ()
                myMask.hide ()
            }
        })
    },
    initComponent : function () {
        Analizador.gpActivar.superclass.initComponent.call (this)
        
        sm = this.getSelectionModel ()
     
        this.btnActivar.setHandler (function () {
            this.toggle (this.getSelectionModel ().getSelected ().data)
        }, this)
        
        this.on ('rowdblclick', function (pGP) {
            this.toggle (pGP.getSelectionModel ().getSelected ().data)
        }, this)
     
        sm.on ('selectionchange', function (pSM) {
            this.btnActivar.setDisabled (! pSM.hasSelection ())
        }, this)
    }
})

