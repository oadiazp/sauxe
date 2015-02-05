WSB.wAddDatatype = Ext.extend (WSB.UI.wAddDatatype, {
    fn: function () {},
    remDatatype: function (pRecord) {
        Ext.Msg.show ({
            title: 'Doctrine Generator v3.0',
            msg: '¿Desea eliminar el tipo '+ pRecord.data.name +'?',
            buttons: Ext.MessageBox.YESNO,
            icon: Ext.MessageBox.QUESTION,
            scope: this,
            fn: function (b) {
                if (b == 'yes') {
                    Ext.Ajax.request ({
                        url: 'rem_datatype',
                        scope: this,
                        params: {
                            datatype : pRecord.data.name
                        },
                        success: function (pResp, pOpt) {
                            pOpt.scope.scope.stDatatype.reload ();
                        }
                    });
                }
            }
        })
    },
    updDatatype : function (pObj) {
        pFrm = pObj.frm;
        pRecord = pObj.record;

        if (pFrm.isValid ()) {
            pFrm.submit ({
                url: 'upd_datatype',
                params: {
                    prev: pRecord.data.name
                },
                failure: function (pFrm) {
                    pFrm.reset ()
                    pFrm.scope.hide ()
                    pFrm.scope.scope.stDatatype.reload ()
                }
            })
        }
    },
    addDatatype : function (pFrm) {
        if (pFrm.isValid ()) {
            pFrm.submit ({
                url: 'add_datatype',
                failure: function (pFrm) {
                    pFrm.reset ();
                    pFrm.scope.hide ();
                    pFrm.scope.scope.stDatatype.reload ();
                }
            });
        }
    },
    setFn : function (pFn, pParam) {
        this.fn = function () {
            pFn (pParam);
        }
    },
    initComponent: function () {
        WSB.wAddDatatype.superclass.initComponent.call(this);

        this.btnOk.setHandler (function () {
           this.fn ()
        }, this);

        this.btnCancel.setHandler (function () {
            this.hide ();
        }, this);
    }
})