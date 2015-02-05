	var perfil = window.parent.UCID.portal.perfil;
	perfil.etiquetas = Object();
	UCID.portal.cargarEtiquetas('gestdatfunciones',cargarInterfaz)
	
	////------------ Inicializo el singlenton QuickTips ------------////
	Ext.QuickTips.init();
	
	////------------ Declarar variables ------------////
	var winIns, winMod,winCamb,tipos,referencia,servicio;
	var auxIns = false;
	var auxMod = false;
	var auxDel = false;
	var auxIns2 = false;
	var auxMod2 = false;
	var auxDel2 = false;
	var auxIns3 = true;
	var auxMod3 = true;
	var auxDel3 = true;
	
	////------------ Area de Validaciones ------------////
    
	referencia = /(^([a-zA-Z])+([a-zA-Z\d\_]*\.{0,1})|([a-zA-Z])+([a-zA-Z\d\_]*\.{0,1})(\/){0,1}([a-zA-Z])+([a-zA-Z\d\_]*))+$/ 
	tipos = /(^([a-zA-ZáéíóúñüÑ])+([a-zA-ZáéíóúñüÑ\d\.\-\@\#\_]*))$/;
	
	function cargarInterfaz(){
	
	////------------ Botones ------------////
	btnAdicionar = new Ext.Button({disabled:true, id:'btnAgrFunciones', hidden:true, icon:perfil.dirImg+'adicionar.png',iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');}  });
	btnModificar = new Ext.Button({disabled:true, id:'btnModFunciones', hidden:true, icon:perfil.dirImg+'modificar.png',iconCls:'btn', text:perfil.etiquetas.lbBtnModificar, handler:function(){winForm('Mod');} });
	btnEliminar = new Ext.Button({disabled:true, id:'btnEliFunciones', hidden:true, icon:perfil.dirImg+'eliminar.png',iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar,handler:function(){eliminarFuncionalidad();} });
	/*btnAyuda = new Ext.Button({id:'btnAyuFunciones', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:'<b>Ayuda</b>' });*/
	UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
	
    ////------------ Arbol Sistemas ------------////
	arbolFunc = new Ext.tree.TreePanel({
		title:perfil.etiquetas.lbTitServReg,
		collapsible:true,
		autoScroll:true,
		region:'west',
		split:true,
		width:'37%',
		loader: new Ext.tree.TreeLoader({
			dataUrl:'cargarservicios'
		})
	});
	
	////------------ Crear nodo padre del arbol ------------////
	padreArbolFunc = new Ext.tree.AsyncTreeNode({
	    text: perfil.etiquetas.lbRootNodeServ,
	 	expandable:false,
		expanded:true,
		id:'0'
	});
	arbolFunc.setRootNode(padreArbolFunc);
	
	////------------ Evento para habilitar botones ------------////
	arbolFunc.on('click', function (node, e){
		btnModificar.disable();
		btnEliminar.disable();
		btnAdicionar.disable();
		stGpFunciones.removeAll();
			if (node.isLeaf()){
				
				servicio = node.id;
				stGpFunciones.removeAll();
				stGpFunciones.load({params:{start:0,limit:15,idservicio:node.id}});
				btnAdicionar.enable();
				auxIns = true;
				auxMod = true;
				auxDel = true;
			}
			else
			{
				auxIns = false;
				auxMod = false;
				auxDel = false;
			}
	}, this);
	
	////------------ Store del Grid de Funciones ------------////
	stGpFunciones =  new Ext.data.Store({
		url: 'cargarfunciones',
		listeners:{'beforeload':function(thisstore, objeto){objeto.params.idservicio = servicio}},
		reader:new Ext.data.JsonReader({
				totalProperty: "cantidad_filas",
				root: "datos",
				id: "idfunciones"
				},
				[
					{name:'idfunciones'},
					{name:'idservicio'},
					{name:'denominacion'},
					{name:'descripcion'}
			
				
				])
		});
	
	////------------ Establesco modo de seleccion de grid (single) ------------////
	sm = new Ext.grid.RowSelectionModel({singleSelect:true});
	sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
							btnModificar.enable();
							btnEliminar.enable();
						}, this);
	////------------ Defino el grid de Funcionalidades ------------////
	var gpFunciones = new Ext.grid.GridPanel({
		frame:true,
		title:perfil.etiquetas.lbTitFuncReg,
		region:'center',
		autoExpandColumn:'expandir',
		store:stGpFunciones,
		sm:sm,
		columns: [
					{hidden: true, hideable: false, dataIndex: 'idfunciones'},
					{hidden: true, hideable: false, dataIndex: 'idservicio'},
					{header: perfil.etiquetas.lbCampoDenominacion, width:150, dataIndex: 'denominacion'},
					{header: perfil.etiquetas.lbCampoDescripcion, width:250, dataIndex: 'descripcion', id:'expandir'}
			 	 ],
		loadMask:{store:stGpFunciones},
			bbar:new Ext.PagingToolbar({
			pageSize: 15,
			id:'ptbaux',
			store: stGpFunciones,
			displayInfo: true,
			displayMsg: perfil.etiquetas.lbMsgPaginadoFun,
			emptyMsg: perfil.etiquetas.lbMsgEmptyFun
 		})
		});
	////------------ Trabajo con el PagingToolbar ------------////
	Ext.getCmp('ptbaux').on('change',function(){
		sm.selectFirstRow();
	},this)
	
	////------------ Panel con los componentes ------------////
	var panel = new Ext.Panel({
		layout:'border',
		title:perfil.etiquetas.lbTitGestiFunc,
		items:[gpFunciones,arbolFunc],
		tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
		keys: new Ext.KeyMap(document,[{
			key:Ext.EventObject.DELETE,
			fn: function(){
			if(auxDel && auxDel2 && auxDel3)
				eliminarFuncionalidad();
			}
		  	},
		  	{
		  		key:"i",
				alt:true,
				fn: function(){
				if(auxIns && auxIns2 && auxIns3)
					winForm('Ins'); 
		  		}
		  	},
		  	{
		  		key:"m",
				alt:true,
				fn: function(){
				if(auxMod && auxMod2 && auxMod3)
					winForm('Mod');
				}		    			
		  	}])
		});
		////---------- Eventos para hotkeys ----------////
		btnAdicionar.on('show',function(){
			auxIns2 = true;
		},this)
		btnEliminar.on('show',function(){
			auxDel2 = true;
		},this)
		btnModificar.on('show',function(){
			auxMod2 = true;
		},this)
		stGpFunciones.on('load',function(){
			if(stGpFunciones.getCount() != 0)
			{
				auxMod = true;
				auxDel = true;
			}
			else
			{
				auxMod = false;
				auxDel = false;
			}
		},this)	              
	////------------ Formulario ------------////
	var regFunciones = new Ext.FormPanel({
		labelAlign: 'top',
		frame:true,
		bodyStyle:'padding:5px 5px 0',
		items: [{
					layout:'column',
					items:[{
							columnWidth:1,
							layout:'form',
							items:[{
									xtype:'textfield',
									fieldLabel:perfil.etiquetas.lbDenominacion,
									id:'denominacion',
                                    maxLength:40,
									allowBlank: false,
									blankText:perfil.etiquetas.lbMsgBlankFun,
									regex: tipos,
									regexText:perfil.etiquetas.lbMsgRegExpDenom,
									anchor:'90%'
								}]
						   },
					
						
						   {
								columnWidth:1,
								layout: 'form',
								items: [{
										xtype:'textarea',
										fieldLabel: perfil.etiquetas.lbDescripcion,
										id: 'descripcion',
										anchor:'100%'
								}]
						   }]
				}]
		});
	////------------ Cargar la ventana ------------////
	function winForm(opcion){
		switch(opcion){
			case 'Ins':{
				if(!winIns)
				{
						winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
							title:perfil.etiquetas.lbTitAdicionarFun,width:400,height:280,
							buttons:[
							{
								icon:perfil.dirImg+'cancelar.png',
								iconCls:'btn',
								text:perfil.etiquetas.lbBtnCancelar,
								handler:function(){winIns.hide();}
							},
							{	
								icon:perfil.dirImg+'aplicar.png',
								iconCls:'btn',
								text:perfil.etiquetas.lbBtnAplicar,
								handler:function(){adicionarFuncionalidad('apl');}
							},
							{	
								icon:perfil.dirImg+'aceptar.png',
								iconCls:'btn',
								text:perfil.etiquetas.lbBtnAceptar,
								handler:function(){adicionarFuncionalidad();}
							}]
						});
						winIns.on('show',function(){
							auxIns3 = false;
							auxMod3 = false;
							auxDel3 = false;
						},this)
						winIns.on('hide',function(){
							auxIns3 = true;
							auxMod3 = true;
							auxDel3 = true;
						},this)
				}					
				regFunciones.getForm().reset(); 
				winIns.add(regFunciones);
				winIns.doLayout();
				winIns.show();
				}break;
				case 'Mod':{
					if(!winMod)
					{
						winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
							title:perfil.etiquetas.lbTitModificarFun,width:400,height:280,
							buttons:[
							{
								icon:perfil.dirImg+'cancelar.png',
								iconCls:'btn',
								text:perfil.etiquetas.lbBtnCancelar,
								handler:function(){winMod.hide();}
							},
							{	
								icon:perfil.dirImg+'aceptar.png',
								iconCls:'btn',
								text:perfil.etiquetas.lbBtnAceptar,
								handler:function(){modificarFuncionalidad();}
							}]
						});
						winMod.on('show',function(){
							auxIns3 = false;
							auxMod3 = false;
							auxDel3 = false;
						},this)
						winMod.on('hide',function(){
							auxIns3 = true;
							auxMod3 = true;
							auxDel3 = true;
						},this)
					}
					regFunciones.getForm().reset(); 
					winMod.add(regFunciones);
					winMod.doLayout();									
					winMod.show();
                    regFunciones.getForm().loadRecord(sm.getSelected());
				}break;
			}
		}
	
	////------------ Viewport ------------////
	var vpGestFuncionalidad = new Ext.Viewport({
		layout:'fit',
		items:panel
		})
	
	
	
	////------------ Adicionar Funcionalidades ------------////
	function adicionarFuncionalidad(apl){
		if (regFunciones.getForm().isValid())
		{
			regFunciones.getForm().submit({
				url:'insertarfuncion',
				waitMsg:perfil.etiquetas.lbMsgEsperaReg,
				params:{idservicio:arbolFunc.getSelectionModel().getSelectedNode().attributes.id},
				failure: function(form, action){
					if(action.result.codMsg != 3)
					{
						mostrarMensaje(action.result.codMsg,action.result.mensaje); 
						regFunciones.getForm().reset(); 
						if(!apl) 
						winIns.hide();
						stGpFunciones.reload();
						sm.clearSelections();
						btnModificar.disable();
						btnEliminar.disable();
					}
					if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
				}
			});
		}
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
	}
	
	////------------ Modififcar Funcionalidad ------------////
	function modificarFuncionalidad(){
		if (regFunciones.getForm().isValid()){
			regFunciones.getForm().submit({
				url:'modificarfuncion',
				waitMsg:perfil.etiquetas.lbMsgEsperaMod,
				params:{idfunciones:sm.getSelected().data.idfunciones,idservicio:sm.getSelected().data.idservicio},
				failure: function(form, action){
					if(action.result.codMsg != 3){
						mostrarMensaje(action.result.codMsg,action.result.mensaje); 
						stGpFunciones.reload();
						winMod.hide();
					}
					if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
					
				}
			});
		}
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                        
	}
	
	////------------ Eliminar Funcionalidad ------------////
	function eliminarFuncionalidad(){
		mostrarMensaje(2,perfil.etiquetas.lbMsgDeseaEliminar,elimina);
		function elimina(btnPresionado){
			if (btnPresionado == 'ok')
			{
				Ext.Ajax.request({
					url: 'eliminarfuncion',
					method:'POST',
					params:{idfunciones:sm.getSelected().data.idfunciones},
					callback: function (options,success,response){
							responseData = Ext.decode(response.responseText);
							if(responseData.codMsg == 1)
							{
								mostrarMensaje(responseData.codMsg,responseData.mensaje);
								stGpFunciones.reload();
								sm.clearSelections();
								btnModificar.disable();
								btnEliminar.disable();
							}
							if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
					}
				});
			}
		}
	}
	
	////------------ Buscar Funcionalidad ------------////
			function buscarfuncionalidad(funcionalidad){  
				stGpFunciones.load({params:{denominacion:funcionalidad,idsistema:arbolFunc.getSelectionModel().getSelectedNode().attributes.id,start:0,limit:20}});
			}
	}