Ext.Ajax.method = 'POST'
Ext.QuickTips.init();
window.mb = new Componentes.MessageBus ()
window.mb.connect ()
window.mb.on ('ready', function () {
	new WaoX.vpWaox ()	
}, this)


