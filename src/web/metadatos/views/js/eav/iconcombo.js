var perfil = window.parent.UCID.portal.perfil;
Ext.ux.IconCombo = Ext.extend(Ext.form.ComboBox, {
    initComponent:function() {
 
        Ext.apply(this, {
            tpl:  '<tpl for=".">'
                + '<div class="x-combo-list-item ux-icon-combo-item "  ><img src="geticon?icon={'+this.iconClsField+'}" width="16px" height="16px" align="absmiddle"/>&nbsp;&nbsp '                
                + '{' + this.displayField + '}'
                + '</div></tpl>'
				
        });
 
        // call parent initComponent
        Ext.ux.IconCombo.superclass.initComponent.call(this);
 
    }, // end of function initComponent
 
    onRender:function(ct, position) {
        // call parent onRender
        Ext.ux.IconCombo.superclass.onRender.call(this, ct, position);
 
        // adjust styles
        this.wrap.applyStyles({position:'relative'});
        this.el.addClass('ux-icon-combo-input');
 
        // add div for icon
        this.icon = Ext.DomHelper.append(this.el.up('div.x-form-field-wrap'), {
            tag: 'div', style:'position:absolute',id:'divbandera'
        });
    }, // end of function onRender
 
    setIconCls:function() {
        var rec = this.store.query(this.valueField, this.getValue()).itemAt(0); 
        if(rec) {
            this.icon.className = 'ux-icon-combo-icon  ' //+ rec.get(this.iconClsField); 
			document.getElementById('divbandera').innerHTML='<img src="geticon?icon='+rec.data.icono+'" width="16px" height="16px"/>';
        
		}
    }, // end of function setIconCls
 
    setValue: function(value) {
        Ext.ux.IconCombo.superclass.setValue.call(this, value);
        this.setIconCls();
    } // end of function setValue
});
 
// register xtype
Ext.reg('iconcombo', Ext.ux.IconCombo);
 
// end of file