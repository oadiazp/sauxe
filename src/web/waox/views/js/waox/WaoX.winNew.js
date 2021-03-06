/*
 * File: WaoX.winNew.js
 * Date: Sat Jan 01 2011 01:02:54 GMT-0500 (CST)
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be generated the first time you export.
 *
 * You should implement event handling and custom methods in this
 * class.
 */

WaoX.winNew = Ext.extend(WaoX.winNewUi, {
    initComponent: function() {
        WaoX.winNew.superclass.initComponent.call(this);


        this.btnOk.setHandler (function () {
            s = this.egChart.getStore ()
            
            result = []
            s.each (function (pRecord) {
                result.push (pRecord.data)
            })            

            pCard = this.scope.scope.pCard
            pCard.getLayout().setActiveItem (result.length)
            this.hide ()

            var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading ..."});
            myMask.show();

            Ext.Ajax.request ({
                url : 'configure',
                scope : this,
                params: {charts: Ext.encode (result)},
                success: function (pResp, pOpt) {
                    items = Ext.decode (pResp.responseText)                                        

                    pCard = pOpt.scope.scope.scope.pCard
                    objs = []

                    for (i = 0; i < items.length; i++) {                        
                        phId = String.format ('ph{0}{1}', result.length, i+1)
                        ph = Ext.getCmp (phId)
                        cmp = Ext.ComponentMgr.create (items [i])

                        cmp.on ('updateChart', function (pChart, pMsg) {
                            // console.log (pMsg)
                            pChart.store.loadData (pMsg)
                        })
                        
                        window.mb.suscribe ({
                            object : cmp,
                            code   : cmp.code,
                            event  : 'updateChart'     
                        }) 

                        ph.add (cmp)
                    }

                    pCard.doLayout ()

                    myMask.hide ()
                }
            })   
        }, this)
    }
});
Ext.reg('waox.winNew', WaoX.winNew);