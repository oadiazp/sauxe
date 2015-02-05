WSB.wMngDatatype = Ext.extend (WSB.UI.wMngDatatype, {
    initComponent: function () {
        WSB.wMngDatatype.superclass.initComponent.call (this)

        this.btnClose.setHandler (function () {
            this.closeWindow()
        }, this)

        this.btnAddDatatype.setHandler (function () {
            this.wAddDatatype.setFn (this.wAddDatatype.addDatatype, this.wAddDatatype.frmDatatype.getForm ())
            this.wAddDatatype.show ()
        }, this)

       this.btnUpdDatatype.setHandler (function () {
          this.wAddDatatype.setFn (this.wAddDatatype.updDatatype, {
              record: this.smDatatype.getSelected (),
              frm: this.wAddDatatype.frmDatatype.getForm ()
          })
          this.wAddDatatype.show ()
          this.wAddDatatype.frmDatatype.getForm ().loadRecord ({data:{type: this.smDatatype.getSelected ().data.name}})
       }, this)

       this.btnRemDatatype.setHandler (function () {
            this.wAddDatatype.remDatatype (this.smDatatype.getSelected ())
       }, this)

       this.btnAddField.setHandler (function () {
            this.wAddField.setFn (this.wAddField.addField, {
                frm: this.wAddField.frmField.getForm (),
                record: this.smDatatype.getSelected ()
            })
            this.wAddField.show ()
        }, this)

       this.btnUpdField.setHandler (function () {
          this.wAddField.setFn (this.wAddField.updField, {
              index: this.index,
              dt: this.smDatatype.getSelected (),
              frm: this.wAddField.frmField.getForm ()
          })
          this.wAddField.show ()
          this.wAddField.frmField.getForm ().loadRecord ({data:{name: this.smField.getSelected ().data.name,
                                                                                                                              datatype: this.smField.getSelected ().data.datatype}})
       }, this)

       this.btnRemField.setHandler (function () {
            this.wAddField.remField (this.index, this.smDatatype.getSelected ())
       }, this)

       this.on ('show', function () {
           Ext.Ajax.request ({
               url: 'datatypes',
               scope: this,
//               params: {all: true},
               success: function (pResp, pOpt) {
                   obj = Ext.decode (pResp.responseText)
                   pOpt.scope.stDatatype.loadData (obj)
               }
           })
       }, this)

       this.smDatatype.on ('selectionchange', function (pSM) {
        if (pSM.getSelected ())
            this.stField.loadData (pSM.getSelected ().data)
        else
            this.stField.removeAll ()

        this.btnUpdDatatype.setDisabled (! pSM.getSelected ())
        this.btnAddField.setDisabled (! pSM.getSelected ())
        this.btnRemDatatype.setDisabled (! pSM.getSelected ())
       }, this)

        this.smField.on ('selectionchange', function (pSM) {
             this.btnUpdField.setDisabled (! pSM.getSelected ())
            this.btnRemField.setDisabled (! pSM.getSelected ())
       }, this)
    },
    closeWindow : function () {
        this.hide ()
    }
})