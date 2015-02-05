		var perfil = window.parent.UCID.portal.perfil;
		perfil.etiquetas = Object();
		UCID.portal.cargarEtiquetas('gestfuncionalidad',cargarInterfaz);
		
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
		
		////------------ Declarar variables ------------////
		var winIns, winMod,winCamb, idsistma,tipos,tipos,referencia;
                var auxIns = false;
		var auxMod = false;
		var auxDel = false;
		var auxIns2 = false;
		var auxMod2 = false;
		var auxDel2 = false;
		var auxBus = false;
		var auxDelete = true;
		var auxIns3 = true;
		var auxMod3 = true;
		var auxDel3 = true;
		var auxBus3 = true;
		////------------ Area de Validaciones ------------////	 
		tipos =   /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\(\)\s]*))$/;
		soloNumeros = /^[0-9]+$/;
		function cargarInterfaz(){
        					
	    ////------------ Botones ------------////
	    btnAdicionar = new Ext.Button({disabled:true, id:'btnAgrFunc', hidden:true, icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');}  });
	    btnModificar = new Ext.Button({disabled:true, id:'btnModFunc', hidden:true, icon:perfil.dirImg+'modificar.png',iconCls:'btn', text:perfil.etiquetas.lbBtnModificar, handler:function(){winForm('Mod');} });
	    btnEliminar = new Ext.Button({disabled:true, id:'btnEliFunc', hidden:true, icon:perfil.dirImg+'eliminar.png',iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar,handler:function(){eliminarFuncionalidad();} });
	    btnBuscar = new Ext.Button({disabled:true,icon:perfil.dirImg+'buscar.png',iconCls:'btn',text:perfil.etiquetas.lbBtnBuscar, handler:function(){buscarfuncionalidad(funcionalidad.getValue());}})
	    /*btnAyuda = new Ext.Button({id:'btnAyuFunc', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
	    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
					////------------ Arbol Sistemas ------------////
					arbolFunc = new Ext.tree.TreePanel({
						title:perfil.etiquetas.lbTitArbolSistemas,
						collapsible:true,
						autoScroll:true,
						region:'west',
						split:true,
						width:'37%',
						loader: new Ext.tree.TreeLoader({
							dataUrl:'cargarsistema'
						})
					});
					
					////------------ Crear nodo padre del arbol ------------////
					padreArbolFunc = new Ext.tree.AsyncTreeNode({
					    text: perfil.etiquetas.lbRootNodeArbolSubsist,
					 	expandable:false,
						expanded:true,
						id:'0'
					});
					arbolFunc.setRootNode(padreArbolFunc);
					
					////------------ Evento para habilitar botones ------------////
					
					arbolFunc.on('click', function (node, e){
						if(node.id == 0)
						{
							stGpFuncionalidades.removeAll();
							btnModificar.disable();
							btnEliminar.disable();
							btnAdicionar.disable();
							auxIns = false;
							auxMod = false;
							auxDel = false;
							auxBus = false;
							btnBuscar.disable();
                                                        gpFuncionalidades.disable();
						}
						else if(node.isLeaf())
						{
							gpFuncionalidades.enable();
							stGpFuncionalidades.removeAll();
							idsistema = node.id;
							btnAdicionar.enable();
							stGpFuncionalidades.load({params:{start:0,limit:15}});
							btnBuscar.enable();
						}
						else
						{
							idsistema = node.id;
							gpFuncionalidades.enable();
							stGpFuncionalidades.load({params:{start:0,limit:15}});
							auxIns = true;
							auxMod = false;
							auxDel = false;
							auxBus = false;
							btnBuscar.disable();
							btnAdicionar.enable();
						}
						
					}, this);
					
					////------------ Store del Grid de Funcionalidades ------------////
					stGpFuncionalidades =  new Ext.data.Store({
						
						proxy: new Ext.data.HttpProxy({
								url: 'cargarfuncionalidades'
						}),
						listeners:{'beforeload':function(thisstore,objeto){
							objeto.params.idsistema=idsistema			
						}},
						reader:new Ext.data.JsonReader({
								totalProperty: "cantidad_filas",
								root: "datos",
								id: "idfuncionalidad"
								},
								[
									{name:'idfuncionalidad'},
									{name:'referencia'},
									{name:'text'},
									{name:'descripcion'},
									{name:'icono'},
									{name:'idsistema'},
                                    {name:'index'}
								])
						});
					
					////------------ Establesco modo de seleccion de grid (single) ------------////
					sm = new Ext.grid.RowSelectionModel({singleSelect:true});
					sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
											btnModificar.enable();
											btnEliminar.enable();
											auxDel = true;
											auxMod = true;
											auxBus = true;
										}, this);
					
					////------------ Defino el grid de Funcionalidades ------------////
					var gpFuncionalidades = new Ext.grid.GridPanel({
						frame:true,
						region:'center',
						iconCls:'icon-grid',
                                                disabled:true,
						autoExpandColumn:'expandir',
						store:stGpFuncionalidades,
						sm:sm,
						columns: [
									{hidden: true, hideable: false, dataIndex: 'idfuncionalidad'},
									{hidden: true, hideable: false, dataIndex: 'idsistema'},
									{hidden: true, hideable: false, dataIndex: 'descripcion'},
                                                                        {hidden: true, hideable: false, dataIndex: 'index'},
									{header: perfil.etiquetas.lbCampoDenom, width:150, dataIndex: 'text'},
									{header: perfil.etiquetas.lbCampoReferencia, width:300, dataIndex: 'referencia', id:'expandir'}
							 	 ],
						loadMask:{store:stGpFuncionalidades},
							
						tbar:[
								new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbBuscarFunc}),
								funcionalidad = new Ext.form.TextField({width:150, id: 'nombrefuncionalidad'}),
								new Ext.menu.Separator(),			
								btnBuscar
							 ],
						bbar:new Ext.PagingToolbar({
						pageSize: 15,
						id:'ptbaux',
						store: stGpFuncionalidades,
						displayInfo: true,
						displayMsg: perfil.etiquetas.lbMsgPaginado,
						emptyMsg: perfil.etiquetas.lbMsgEmpty
				 	})
						});
					////------------ Trabajo con el PagingToolbar ------------////
					Ext.getCmp('ptbaux').on('change',function(){
						sm.selectFirstRow();
					},this)	
					////------------ Panel con los componentes ------------////
					var panel = new Ext.Panel({
						layout:'border',
						title:perfil.etiquetas.lbTitGestFuncionalidades,
						items:[gpFuncionalidades,arbolFunc],
						tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
						keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    			if(auxDel && auxDelete && auxDel2 && auxDel3)
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
		    		  		key:"b",
		    				alt:true,
		    				fn: function(){
		    				if(auxBus && auxBus3)
		    					buscarfuncionalidad(Ext.getCmp('nombrefuncionalidad').getValue());
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
						Ext.getCmp('nombrefuncionalidad').on('focus',function(){
							auxDelete = false;
						},this)
						Ext.getCmp('nombrefuncionalidad').on('blur',function(){
							auxDelete = true;
						},this)
						stGpFuncionalidades.on('load',function(){
						if(stGpFuncionalidades.getCount() != 0)
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
					var regFuncionalidad = new Ext.FormPanel({
						labelAlign: 'top',
						frame:true,
						bodyStyle:'padding:5px 5px 0',
						items: [{
									layout:'column',
									items:[{
											columnWidth:.7,
											layout:'form',
											items:[{
													xtype:'textfield',
													fieldLabel:perfil.etiquetas.lbDenominacion,
													id:'text',
													allowBlank: false,
													blankText:perfil.etiquetas.lbMsgBlankTextDenom,
													regex: tipos,
													regexText:perfil.etiquetas.lbMsgExpRegDenom,
													anchor:'95%'
												}]
										   },
										   {
												columnWidth:.3,
												layout: 'form',
												items: [{
														xtype:'textfield',
														fieldLabel: perfil.etiquetas.lbIcono,
														id:'icono',
														regex: tipos,
                                                        maxLength:20, 
														regexText:perfil.etiquetas.lbMsgExpRegIcon,
														anchor:'100%'
												}]
										   },
										   {
												columnWidth:.8,
												layout: 'form',
												items: [{
														xtype:'textfield',
														fieldLabel: perfil.etiquetas.lbReferencia,
														id: 'referencia',
                                                        maxLength:255, 
														allowBlank: false,
														blankText:perfil.etiquetas.lbMsgBlankTextRef,
														regexText:perfil.etiquetas.lbMsgExpRegRef,
														anchor:'95%'
												}]
										   },
                                           {
                                                columnWidth:.2,
                                                layout: 'form',
                                                items: [{
                                                        xtype:'textfield',
                                                        fieldLabel: perfil.etiquetas.lbIndex,
                                                        id:'index',
                                                        regex:soloNumeros,
                                                        anchor:'100%'
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
											auxBus3 = false;
										},this)
										winIns.on('hide',function(){
											auxIns3 = true;
											auxMod3 = true;
											auxDel3 = true;
											auxBus3 = true;
										},this)
								}
								regFuncionalidad.getForm().reset();
								winIns.add(regFuncionalidad);
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
											auxBus3 = false;
										},this)
										winMod.on('hide',function(){
											auxIns3 = true;
											auxMod3 = true;
											auxDel3 = true;
											auxBus3 = true;
										},this)
									}
									
									winMod.add(regFuncionalidad);
									winMod.doLayout();									
									winMod.show();
									regFuncionalidad.getForm().loadRecord(sm.getSelected());
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
			
				if (regFuncionalidad.getForm().isValid())
				{
					regFuncionalidad.getForm().submit({
						url:'insertarfuncionalidad',
						waitMsg:perfil.etiquetas.lbMsgEsperaRegFun,
						params:{idsistema:arbolFunc.getSelectionModel().getSelectedNode().attributes.id},
						failure: function(form, action){
							if(action.result.codMsg != 3)
							{
								mostrarMensaje(action.result.codMsg,action.result.mensaje); 
								regFuncionalidad.getForm().reset(); 
								if(!apl) 
								winIns.hide();
								stGpFuncionalidades.reload();
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
				if (regFuncionalidad.getForm().isValid()){
					regFuncionalidad.getForm().submit({
						url:'modificarfuncionalidad',
						waitMsg:perfil.etiquetas.lbMsgEsperaModFun,
						params:{idfuncionalidad:sm.getSelected().data.idfuncionalidad,idsistema:sm.getSelected().data.idsistema},
						failure: function(form, action){
							if(action.result.codMsg != 3){
								mostrarMensaje(action.result.codMsg,action.result.mensaje); 
								stGpFuncionalidades.reload();
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
							url: 'eliminarfuncionalidad',
							method:'POST',
							params:{idfuncionalidad:sm.getSelected().data.idfuncionalidad},
							callback: function (options,success,response){
									responseData = Ext.decode(response.responseText);
									if(responseData.codMsg == 1)
									{
										mostrarMensaje(responseData.codMsg,responseData.mensaje);
										stGpFuncionalidades.reload();
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
				stGpFuncionalidades.load({params:{denominacion:funcionalidad,idsistema:arbolFunc.getSelectionModel().getSelectedNode().attributes.id,start:0,limit:20}});
			}
		}