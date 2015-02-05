Ext.QuickTips.init();
var perfil = window.parent.UCID.portal.perfil
perfil.etiquetas = Object();
UCID.portal.cargarEtiquetas('gestionartraza', cargartraza);
var fechaDesde, fechaHasta, stComboTipo, stComboCategoria;
var storegridtraza, gdGestiontraza, combotipo, combocategoria, cmGestiontraza, smtraza;
var viewport, winBusq, frmBusq, btnExportar, btnAyuda, btnAvanzado, values;

Ext.apply(Ext.form.VTypes, {
	daterange : function(val, field) {
		var date = field.parseDate(val);

		if (!date) {
			return;
		}
		if (field.startDateField
				&& (!this.dateRangeMax || (date.getTime() != this.dateRangeMax
						.getTime()))) {
			var start = Ext.getCmp(field.startDateField);
			start.setMaxValue(date);
			start.validate();
			this.dateRangeMax = date;
		} else if (field.endDateField
				&& (!this.dateRangeMin || (date.getTime() != this.dateRangeMin
						.getTime()))) {
			var end = Ext.getCmp(field.endDateField);
			end.setMinValue(date);
			end.validate();
			this.dateRangeMin = date;
		}
		/*
		 * Always return true since we're only using this vtype to set the
		 * min/max allowed values (these are tested for after the vtype test)
		 */
		return true;
	}
});

function cargartraza() {
	// Store de los tipos de trazas
	stComboTipo = new Ext.data.Store({
		url : 'cargarcombotipo',
		autoLoad : true,
		reader : new Ext.data.JsonReader({
			root : "tipo_traza",
			id : "idtipotraza"
		}, [{
			name : 'idtipotraza'
		}, {
			name : 'tipotraza'
		},])
	});
	// Store de las categorias
	stComboCategoria = new Ext.data.Store({
		url : 'cargarcombocategoria',
		autoLoad : true,
		reader : new Ext.data.JsonReader({
			root : "categorias",
			id : "idcategoriatraza"
		}, [{
			name : 'idcategoriatraza'
		}, {
			name : 'denominacion'
		},])
	});
	// Store del grid
	storegridtraza = new Ext.data.Store({
		url : '',
		reader : new Ext.data.JsonReader({
			totalProperty : "totalProperty",
			root : "root"
		}, [{
			name : 'vacio'
		}])
	});
	btnExportar = new Ext.Button({
		disabled : true,
		id : 'btnExportar',
		icon : perfil.dirImg + 'exportar.png',
		iconCls : 'btn',
		text : perfil.etiquetas.lbBtnExportar,
		handler : OnbtnExportarTrazas
	});
	// Boton de Ayuda
	btnAyuda = new Ext.Button({
		disabled : true,
		id : 'btnAyuda',
		icon : perfil.dirImg + 'ayuda.png',
		iconCls : 'btn',
		text : perfil.etiquetas.lbBtnAyuda
	});
	// Boton para busqueda avanzada
	btnAvanzado = new Ext.Button({
		disabled : true,
		id : 'btnAvanzado',
		icon : perfil.dirImg + 'buscaravanzada.png',
		iconCls : 'btn',
		text : perfil.etiquetas.lbBtnAvanzada,
		handler : function(){winForm(true)}
	})
	// Campo fecha desde
	fechaDesde = new Ext.form.DateField({
		id : 'iddesde',
		fieldLabel : perfil.etiquetas.lbDfDesde,
		vtype : 'daterange',
		endDateField : 'idhasta',
		anchor : '50%',
		maxValue : new Date(),
		readOnly : true,
		format : 'd/m/Y',
		listeners : {
			'change' : function() {
				if (combotipo.getValue() != "") {
					cargar(storegridtraza, combotipo.getRawValue(), combotipo.getValue(), combocategoria.getValue(), fechaDesde.getRawValue(), fechaHasta.getRawValue());
				}
			}
		}
	});
	// Campo fecha hasta
	fechaHasta = new Ext.form.DateField({
		id : 'idhasta',
		fieldLabel : perfil.etiquetas.lbDfHasta,
		vtype : 'daterange',
		startDateField : 'iddesde',
		anchor : '50%',
		maxValue : new Date(),
		readOnly : true,
		format : 'd/m/Y',
		listeners : {
			'change' : function() {
				if (combotipo.getValue() != "") {
					cargar(storegridtraza, combotipo.getRawValue(), combotipo.getValue(), combocategoria.getValue(), fechaDesde.getRawValue(), fechaHasta.getRawValue());
				}
			}
		}
	});

	cmGestiontraza = new Ext.grid.ColumnModel([{
		id : 'expandir'
	}]);
	// Combo de tipos de trazas
	combotipo = new Ext.form.ComboBox({
		allowBlank : false,
		emptyText : 'Seleccione',
		fieldLabel : perfil.etiquetas.lbCbTipoTraza,
		anchor : '95%',
		readOnly : true,
		store : stComboTipo,
		displayField : 'tipotraza',
		valueField : 'idtipotraza',
		hiddenName : 'idtipotraza',
		triggerAction : 'all',
		mode : 'local',
		listeners : {
			'select' : function() {confform(combotipo.getRawValue()),confgrid(combotipo.getRawValue());
			}
		}
	});
	// Combo de categoriass
	combocategoria = new Ext.form.ComboBox({
		emptyText : 'Todas',
		fieldLabel : perfil.etiquetas.lbCbCategoria,
		anchor : '95%',
		readOnly : true,
		store : stComboCategoria,
		displayField : 'denominacion',
		hiddenName : 'idcategoriatraza',
		valueField : 'idcategoriatraza',
		triggerAction : 'all',
		mode : 'local',
		listeners : {
			'select' : function() {if (combotipo.getValue() != "") {
					cargar(storegridtraza, combotipo.getRawValue(), combotipo.getValue(), combocategoria.getValue(), fechaDesde.getRawValue(), fechaHasta.getRawValue());
				}
			}
		}
	});

	smtraza = new Ext.grid.RowSelectionModel({
		singleSelect : true,
		listeners : {
			'rowselect' : function(smodel, rowIndex, keepExisting, record) {
				btnExportar.enable(), btnAvanzado.enable();
			}
		}
	})

	smtraza.on('rowselect', function(smodel, rowIndex, keepExisting, record) {
	}, this);
	// create the gdGestiontraza
	var xg = Ext.grid
	gdGestiontraza = new xg.GridPanel({
		frame : true,
		sm : smtraza,
		store : storegridtraza,
		loadMask : {
			store : storegridtraza
		},
		cm : cmGestiontraza,
		tbar : [{
			xtype : 'label',
			text : 'Desde'
		}, fechaDesde, {
			xtype : 'label',
			text : 'Hasta'
		}, fechaHasta, {
			xtype : 'label',
			text : 'Categoría'
		}, combocategoria, {
			xtype : 'label',
			text : 'Tipo'
		}, combotipo, '-', btnAvanzado],
		bbar : new Ext.PagingToolbar({
			store : storegridtraza,
			displayInfo : true
		})
	});

	pGrid = new Ext.Panel({
		title : 'Gestion de trazas',
		id : 'id',
		tbar : [btnExportar, btnAyuda],
		layout : 'fit',
		items : gdGestiontraza
	});

	viewport = new Ext.Viewport({
		layout : 'fit',
		items : pGrid
	});
}// Endof cargar interfaz

// Funcion para configurar el grid en dependencia del tipo de traza
function confgrid(traza) {
	btnExportar.disabled = true,
	btnAvanzado.disabled = true,
	Ext.getBody().mask(perfil.etiquetas.confMaskMsg);
	Ext.Ajax.request({
		url : 'confgrid',
		params : {tipo_traza : traza},
		callback : function(options, success, response) {
			responseData = Ext.decode(response.responseText);
			if (responseData.grid) {
				var newcm = Ext.UCID.generaDinamico('cm', responseData.grid.columns);
				storegridtraza = new Ext.data.Store({
					url : 'cargargrid',
					listeners : {'load' : function() {gdGestiontraza.getSelectionModel().selectFirstRow()}
					},
						reader : new Ext.data.JsonReader({
						totalProperty: 'cantidad_trazas',
						root : 'trazas',
						id : 'idtraza'
					},Ext.UCID.generaDinamico('rdcampos', responseData.grid.campos))
				});
				Ext.getBody().unmask();
				if (newcm && storegridtraza)
				{				
					gdGestiontraza.reconfigure(storegridtraza, newcm);
					gdGestiontraza.getBottomToolbar().bind(storegridtraza);
					gdGestiontraza.loadMask = new Ext.LoadMask(Ext.getBody(), {msg : perfil.etiquetas.loadMaskMsg, store : storegridtraza});
					cargar(storegridtraza, combotipo.getRawValue(), combotipo.getValue(), combocategoria.getValue(), fechaDesde.getRawValue(), fechaHasta.getRawValue());
				}
			}
		}
	});
}
//Funcion para cargar el grid
function cargar(estore, traza, idtraza, categoria, fechaini, fechafin)
{
	filtro = null;
	if(winBusq) filtro = frmBusq.getForm().getValues();
	estore.baseParams = {
							idtipotraza : idtraza,
							idcategoria : categoria,
							fecha_desde : fechaini,
							fecha_hasta : fechafin,
							tipotraza : traza,
							campos : Ext.encode(filtro)
					};
	estore.reload({	params : {
						limit : 20,
						start : 0
					}
				});
	this.values = filtro;
	if (winBusq)
		winBusq.hide();
}
//Funcion para configurar el formulario de busqueda avanzada.
function confform(traza)
{
	Ext.getBody().mask(perfil.etiquetas.confMaskMsg);
	Ext.Ajax.request({
		url: 'confform',
		params:{tipo_traza:traza},
		callback: function (options,success,response){
				responseData = Ext.decode(response.responseText);				
				crearformulario(responseData)			
		}
	});
}
//Funcion para crear la ventana del formulario de busqueda.
function winForm(mostrar){
	if(!winBusq){		
		winBusq = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
			title:'B&uacute;squeda avanzada',width:500,autoHeight:true,
			buttons:[{icon:perfil.dirImg+'cancelar.png',iconCls:'btn',text:'Cancelar', handler: function(){winBusq.hide();}},
					 {icon:perfil.dirImg+'buscar.png',iconCls:'btn',text:'Buscar', handler:function(){cargar(gdGestiontraza.getStore(),combotipo.getRawValue(), combotipo.getValue(), combocategoria.getValue(), fechaDesde.getRawValue(), fechaHasta.getRawValue());}}]
		});
	}	
	winBusq.add(frmBusq);
	winBusq.doLayout();
	if (mostrar)
		winBusq.show();
}
//Funcion para crear el formulario de busqueda avanzada.
function crearformulario(objitems){ 
	
	if(objitems.length >1){
		if (winBusq)
		{
			winBusq.destroy();
			winBusq = false;
		}
		frmBusq = new Ext.FormPanel({
			id : 'idfrmBusq',
			labelAlign: 'top',
			autoHeight:true,
			frame:true,
			items:{layout:'column',
				items:Ext.UCID.generaDinamico('form',objitems)
			}
		});
	}
}
//Funcion para exportar las trazas
function OnbtnExportarTrazas(btn) {
	arreglo = []
	var limite = gdGestiontraza.getBottomToolbar().pageSize
	var pagina_activa = gdGestiontraza.getBottomToolbar().getPageData().activePage - 1
	var inicio = limite * pagina_activa
	arreglo.push(inicio)
	arreglo.push(limite)
	arreglo.push(storegridtraza.baseParams.idtipotraza)
	arreglo.push(storegridtraza.baseParams.idcategoria)
	arreglo.push(storegridtraza.baseParams.fecha_desde)
	arreglo.push(storegridtraza.baseParams.fecha_hasta)
	arreglo.push(storegridtraza.baseParams.tipotraza)
	arreglo.push(Ext.decode(storegridtraza.baseParams.campos))
	window.open('exportarxml?datos=' + Ext.encode(arreglo));
}