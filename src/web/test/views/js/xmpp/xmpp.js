window.mb = new Componentes.MessageBus ({
	url : 'connection'
})

gp = new XMPP.gpTest ({})

window.mb.connect ();

window.mb.on ('ready', function () {
	window.mb.suscribe ({
		object : gp,
		code  :  'test',
		event : 'message'
	})
})