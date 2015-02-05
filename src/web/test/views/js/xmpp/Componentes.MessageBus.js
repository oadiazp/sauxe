Ext.ns ('Componentes')

Componentes.MessageBus = Ext.extend (Ext.util.Observable, {
	unsuscribe : function (pSuscriber) {
		this.suscribers.remove (pSuscriber)	
	},	 
	suscribe : function (pSuscriber) {
		this.suscribers.push (pSuscriber)	
	},
	getSuscribers : function () {
		return this.suscribers;
	},
	update : function (pStranza) {
		var elems = pStranza.getElementsByTagName('body');
		var body = elems[0];

		json = Ext.decode (Strophe.getText (body))

		if (json) {
			suscribers = window.mb.getSuscribers ()

			for (i = 0; i < suscribers.length; i++) {
				suscriber = suscribers [i]

				if (json.code == suscriber.code)
					suscriber.object.fireEvent (suscriber.event,
												suscriber.object, 
												json.msg)
			}
		}

		return true;
	},
	connect : function () {
		this.conn = new Strophe.Connection (data.url)
		this.conn.scope = this
		this.conn.connect (data.username, data.password, function (pStatus) {
			switch (pStatus) {
				console.log (pStatus)
				case Strophe.Status.CONNECTED: {
					this.send ($pres ().tree ())

					this.addHandler (this.scope.update, null, 'message')
					this.scope.fireEvent('ready', {mb : this.scope})
				}
			}
		})
	},
	constructor : function (config) {
		Componentes.MessageBus.superclass.constructor.call (this, config);

		this.url = config.url
		this.id = 'mb'
		this.suscribers = []

		this.addEvents ('ready')
	}
})