WSB.wAddField = Ext.extend (WSB.UI.wAddField, {
    fn: function () {},
    remField: function (pIndex, pDatatype) {
        Ext.Msg.show ({
            title: 'Doctrine Generator v3.0',
            msg: '¿Desea eliminar el campo?',
            buttons: Ext.MessageBox.YESNO,
            icon: Ext.MessageBox.QUESTION,
            scope: this,
            fn: function (b) {
                if (b == 'yes') {
                    Ext.Ajax.request ({
                        url: 'rem_field',
                        scope: this,
                        params: {
                            index : pIndex,
                            dt  : pDatatype.data.name
                        },
                        success: function (pResp, pOpt) {
                            pOpt.scope.scope.stDatatype.reload ();
                        }
                    });
                }
            }
        })
    },
    updField : function (pObj) {
        pFrm = pObj.frm;
        pIndex  = pObj.index;
        pDt = pObj.dt;

        if (pFrm.isValid ()) {
            pFrm.submit ({
                url: 'upd_field',
                params: {
                    index: pIndex,
                    dt: pDt.data.name
                },
                failure: function (pFrm) {
                    pFrm.reset ();
                    pFrm.scope.hide ();
                    pFrm.scope.scope.stDatatype.reload ();
                }
            });
        }
    },
    addField : function (pObj) {
       pFrm = pObj.frm;
       pRecord = pObj.record;

        if (pFrm.isValid ()) {
            pFrm.submit ({
                url: 'add_field',
                params: {
                    dt: pRecord.data.name
                },
                failure: function (pFrm) {
                    pFrm.reset ();
                    pFrm.scope.hide ();
                     pFrm.scope.scope.stDatatype.reload ({
                         callback : function () {
                            pFrm.scope.scope.smDatatype.selectRecords ([pRecord]);
                         }
                     })
                }
            })
        }
    },
    setFn : function (pFn, pParam) {
        this.fn = function () {
            pFn (pParam);
        };
    },
    initComponent: function () {
        WSB.wAddField.superclass.initComponent.call(this);

        this.btnOk.setHandler (function () {
           this.fn();
        }, this);

        this.btnCancel.setHandler (function () {
            this.hide ();
        }, this);

        this.on ('show', function () {
            this.stDatatype.load ({params: {all: true}});
        });
    }
})