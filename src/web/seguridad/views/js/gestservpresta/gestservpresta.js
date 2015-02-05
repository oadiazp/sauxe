	// 1.
	var perfil = window.parent.UCID.portal.perfil;
	perfil.etiquetas = Object();
	// 2.
	UCID.portal.cargarEtiquetas('gestservpresta',cargarInterfaz);
	// 3. Inicializo el singlenton QuickTips
	Ext.QuickTips.init();
	
	////----------4 declarar variables ----------////
	var winIns, winMod, idsistema;
	var auxIns = false;
	var auxMod = false;
	var auxDel = false;
	var auxIns3 = true;
	var auxMod3 = true;
	var auxDel3 = true;
	var auxIns2 = false;
	var auxMod2 = false;
	var auxDel2 = false;
	function cargarInterfaz(){

	////----------Botones ----------////
	btnAdicionar = new Ext.Button({id:'btnAgrServPres', hidden:true, disabled:true,icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');} });
	btnModificar = new Ext.Button({id:'btnModServPres', hidden:true, disabled:true, icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnModificar, handler:function(){winForm('Mod');} });
	btnEliminar = new Ext.Button({id:'btnEliServPres', hidden:true, disabled:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar, handler:function(){eliminarServicio();} });
	/*btnAyuda = new Ext.Button({id:'btnAyuServPres', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
	UCID.portal.cargarAcciones(window.parent.idFuncionalidad);

	////----------Arbol funcionalides ----------////
	arbolServ = new Ext.tree.TreePanel({
		title:perfil.etiquetas.lbTitSistemasReg,
		collapsible:true,
		autoScroll:true,
		region:'west',
		split:true,
		width:'28%',
		loader: new Ext.tree.TreeLoader({
			dataUrl:'../gestsistema/cargarsistema'
		})
    });
	////---------- Crear nodo padre del arbol ----------////
	padreArbolServ = new Ext.tree.AsyncTreeNode({
          text: perfil.etiquetas.lbRootNodeSitem,
		  expandable:false,
		  expanded:true,
		  id:'0'
        });

	////----------Para insertarle el root al arbol  ----------////	
	arbolServ.setRootNode(padreArbolServ);

	////---------- Evento para habilitar botones ----------////
	arbolServ.on('click', function (node, e){
		btnModificar.disable();
		btnEliminar.disable();
		btnAdicionar.disable();
		stGpServicio.removeAll();
		if (node.isLeaf()){
					gpServicio.enable();
                                        stGpServicio.removeAll();
					idsistema=node.id;
					stGpServicio.load({params:{start:0, limit:15}});
					btnAdicionar.enable();
					auxIns = true;					
		}
		else
		{
			auxDel = false;
			auxIns = false;
			auxMod = false;
		}
                if(node.id == 0)
                    gpServicio.disable();
	}, this);
	////----------Store del Grid de Funcionalidades  ----------////
	stGpServicio =  new Ext.data.Store({
			
			proxy: new Ext.data.HttpProxy({
				url: 'cargarservicio'
			}),
			listeners:{'beforeload':function(thisstore, objeto){objeto.params.idsistema=idsistema}},
				reader:new Ext.data.JsonReader(
					{
						totalProperty: "cantidad_filas",
						root: "datos",
						id: "idfuncionalidad"
					},
					[
						 {name:'idservicio', mapping:'idservicio'},
						 {name:'denominacion', mapping:'denominacion'},
						 {name:'descripcion', mapping:'descripcion'},
						 {name:'wsdl', mapping:'wsdl'}
					]
				)
	});	
	
	////---------- Establesco modo de seleccion de grid (single) ----------////
	sm = new Ext.grid.RowSelectionModel({singleSelect:true});
	
	sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record)
					{
							btnModificar.enable();
							btnEliminar.enable();
							
					}, this);
	////---------- Defino el grid de Funcionalidades ----------////
	var gpServicio = new Ext.grid.GridPanel({
		title:perfil.etiquetas.lbTitServiciosPresta,
		frame:true,
                disabled:true,
		region:'center',
		autoExpandColumn:'expandir',
		store:stGpServicio,
		sm:sm,
		columns:[
					{hidden: true, hideable: false, dataIndex: 'idservicio'},
					{header: perfil.etiquetas.lbCampDenom, width:150, dataIndex: 'denominacion'},
					{header: perfil.etiquetas.lbCampWSDL, width:150, dataIndex: 'wsdl'},
					{id:'expandir', header: perfil.etiquetas.lbCampDescrip, width:200, dataIndex: 'descripcion'}
				],
		loadMask:{store:stGpServicio},		
		bbar:new Ext.PagingToolbar({
			pageSize: 15,
			id:'ptbaux',
			store: stGpServicio,
			displayInfo: true,
			displayMsg: perfil.etiquetas.lbMsgPaginado,
			emptyMsg: perfil.etiquetas.lbMsgEmpty
		})
	});			
	////------------ Trabajo con el PagingToolbar ------------////
	Ext.getCmp('ptbaux').on('change',function(){
		sm.selectFirstRow();
	},this)

	////---------- Renderiar el arbol  ----------////
	var panel = new Ext.Panel({
		title:perfil.etiquetas.lbTitGestServPresta,
			layout:'border',
			items:[gpServicio, arbolServ],
			tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
			keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    			if(auxDel && auxDel2 && auxDel3)
		    				eliminarServicio();
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
	stGpServicio.on('load',function(){
		if(stGpServicio.getCount() != 0)
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
	////---------- Viewport ----------////
	var vpGestSistema = new Ext.Viewport({
		layout:'fit',
		items:panel
	})
	////---------- Formulario de registrar servicios que presta ----------////
	var regServPresta = new Ext.FormPanel({
				labelAlign:'top',
				frame:true,
				bodyStyle:'padding:5px 5px 0',
				items: [{
				
						layout:'column',
						items:[{
									columnWidth:.5,
									layout:'form',
									items:	[{
												xtype:'textfield',
												fieldLabel:perfil.etiquetas.lbDenominacion,
												id:'denominacion',
                                                maxLength:50,    
												anchor:'95%',
												allowBlank: false,
												blankText: perfil.etiquetas.lbMsgBlankTextDenom,
												regex:/(^([a-zA-Z�������])+([a-zA-Z��������\d\.\-\@\#\_\s]*))$/,
												regexText:perfil.etiquetas.lbMsgExpRegDenom
											}]
								},{
									columnWidth:1,
									layout: 'form',
									items: [{
												xtype:'textfield',
												fieldLabel: perfil.etiquetas.lbWSDL,
												id:'wsdl',
												anchor:'100%',
												allowBlank: false,
												blankText: perfil.etiquetas.lbMsgBlankTextDenom,
												regex:/^^([hH][tT]{2}[pP]:\/\/){1}([a-zA-Z��������\d\-\@\#\_]*\.{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)+([a-zA-Z��������\d\-\@\#\_]*\.{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)*([a-zA-Z��������\d\-\@\#\_]*\/{0,1}[a-zA-Z��������\d\-\@\#\_\s]+)*(\.[wW][sS][dD][lL])$/,
												regexText:perfil.etiquetas.lbMsgExpRegDenom
											}]
								},{
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

	////---------- Cargar ventanas ----------////
	function winForm(opcion){
		switch(opcion){
				case 'Ins':{
						if(!winIns){
								winIns = new Ext.Window({modal: true, closeAction:'hide', layout:'fit',
									title:perfil.etiquetas.lbTitAdicServPrest,width:400,height:270,
									buttons:[
											{
												icon:perfil.dirImg+'cancelar.png',
												iconCls:'btn',
												text:perfil.etiquetas.lbBtnCancelar,
												handler:function(){
															regServPresta.getForm().reset();
															winIns.hide();
														}
											},{		
												icon:perfil.dirImg+'aplicar.png',
												iconCls:'btn',
												text:perfil.etiquetas.lbBtnAplicar,
												handler:function(){
															adicionarServicio('apl');
														}
											},{		
												icon:perfil.dirImg+'aceptar.png',
												iconCls:'btn',
												text:perfil.etiquetas.lbBtnAceptar,
												handler:function(){
															adicionarServicio();
														}
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
                                                                        regServPresta.getForm().reset(); 
								},this)
						}
						winIns.add(regServPresta);
						winIns.doLayout();
						winIns.show();
				}break;				
				case 'Mod':{
					if(!winMod){
							winMod= new Ext.Window({
									modal: true,closeAction:'hide',layout:'fit',
									title:perfil.etiquetas.lbTitModServPresta, width:400,height:270,
									buttons:[
											{ 
												icon:perfil.dirImg+'cancelar.png',
												iconCls:'btn',
												text:perfil.etiquetas.lbBtnCancelar,
												handler:function(){
															regServPresta.getForm().reset();
															winMod.hide();
														}	
											},{	
												icon:perfil.dirImg+'aceptar.png',
												iconCls:'btn',
												text:perfil.etiquetas.lbBtnAceptar,
												handler:function(){
															modificarServicio();
														}
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
                                                                        regServPresta.getForm().reset(); 
								},this)

					}
					winMod.add(regServPresta);
					winMod.doLayout();	
					winMod.show();
					regServPresta.getForm().loadRecord(sm.getSelected());			
				}break;
		}
	}

	////---------- Adicionar un servicio qu presta ----------////
	function adicionarServicio(apl){
			if (regServPresta.getForm().isValid()){
				regServPresta.getForm().submit({
				    url:'insertarservicio',									
					waitMsg:perfil.etiquetas.lbMsgEsperaRegServ,
					params:{idsistema:arbolServ.getSelectionModel().getSelectedNode().attributes.id},				
					failure: function(form, action){
							if(action.result.codMsg != 3){
								mostrarMensaje(action.result.codMsg, action.result.mensaje); 
								regServPresta.getForm().reset(); 															
								if(!apl)					
									winIns.hide();																
								stGpServicio.reload();
								sm.clearSelections();
								btnModificar.disable();
								btnEliminar.disable();
							}
						if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg, action.result.mensaje);										
							}
				});
			}
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                     
	}

	////---------- Modificar un servicio qu presta ----------////
	function modificarServicio(){
			if (regServPresta.getForm().isValid()){
						regServPresta.getForm().submit({
							url:'modificarservicio',
							waitMsg:perfil.etiquetas.lbMsgEsperaModServ,				
							params:{idservicio:sm.getSelected().data.idservicio, idsistema:arbolServ.getSelectionModel().getSelectedNode().attributes.id},
							failure: function(form, action){
								if(action.result.codMsg != 3){													
											winMod.hide();
											regServPresta.getForm().reset(); 																
											stGpServicio.reload();
											sm.clearSelections();
											btnModificar.disable();
											btnEliminar.disable();
											mostrarMensaje(action.result.codMsg, action.result.mensaje); 
								}
								if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
								
							}
						});
			}
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                     
	}
	
	////---------- Eliminar un servicio qu presta----------////
	function eliminarServicio(){
			mostrarMensaje(2,perfil.etiquetas.lbMsgDeseaEliminar,elimina);
			function elimina(btnPresionado){
						if (btnPresionado == 'ok'){
							Ext.Ajax.request({
									url: 'eliminarservicio',
									method:'POST',
									params:{idservicio:sm.getSelected().data.idservicio},
									callback: function (options,success,response){
										responseData = Ext.decode(response.responseText);
										if(responseData.codMsg == 1){
													mostrarMensaje(responseData.codMsg,responseData.mensaje);
													stGpServicio.reload();
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
}
