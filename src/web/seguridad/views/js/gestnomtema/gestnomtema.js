		var perfil = window.parent.UCID.portal.perfil;
		perfil.etiquetas = Object();
		UCID.portal.cargarEtiquetas('gestnomtema', cargarInterfaz);
		
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
				
		////------------ Declarar Variables ------------////
		var winIns, winMod,regTema;
		var auxIns = false;
		var auxMod = false;
		var auxDel = false;
		var auxMod1 = false;
		var auxDel1 = false;
		var auxIns2 = true;
		var auxMod2 = true;
		var auxDel2 = true;
		
		////------------ Area de Validaciones ------------////
		var tipos, abreviatura;
		tipos = /(^([a-zA-ZáéíóúñüÑ])+([a-zA-ZáéíóúñüÑ\d\.\-\@\#\_ ]*))$/;
		abreviatura = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\@\#\_]*)+$/;
		
		function cargarInterfaz()
		{
			////------------ Botones ------------////
			btnAdicionar = new Ext.Button({id:'btnAgrTema', hidden:true, icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');}  });
			btnModificar = new Ext.Button({disabled:true,id:'btnModTema', hidden:true, icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnModificar, handler:function(){winForm('Mod');} });
			btnEliminar = new Ext.Button({disabled:true,id:'btnEliTema', hidden:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar,handler:function(){eliminarTema();}  });
			/*btnAyuda = new Ext.Button({id:'btnAyuTema', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
			UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
			////------------ Store del Grid de Temas ------------//// 
			var stGpTema =  new Ext.data.Store({
					url: 'cargarnomtema',
					reader:new Ext.data.JsonReader({
						totalProperty: "cantidad_filas",
						root: "datos",
						id: "idtema"
						},
						[
						 	{name:'idtema'},
						 	{name:'denominacion'},
						 	{name:'descripcion'},
						 	{name:'abreviatura'},
						])
			});
			
			////------------ Establesco modo de seleccion de grid (single) ------------////
			sm = new Ext.grid.RowSelectionModel({singleSelect:true});
			sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
									btnModificar.enable();
									btnEliminar.enable();
								}, this);
			////------------ Defino el grid de Temas ------------////
			var gpTema= new Ext.grid.GridPanel({
				frame:true,
				region:'center',
				iconCls:'icon-grid',
				autoExpandColumn:'expandir',
				store:stGpTema,
				sm:sm,
				columns: [
							{hidden: true, hideable: false,  dataIndex: 'idtema'},
							{ id:'expandir',header: perfil.etiquetas.lbTitDenominacion,width:200,  dataIndex: 'denominacion'},
							{ header: perfil.etiquetas.lbTitAbreviatura,width:200, dataIndex: 'abreviatura'},	
							{ header: perfil.etiquetas.lbTitDescripcion, width:200, dataIndex: 'descripcion'}
		
				 		 ],
				loadMask:{store:stGpTema},			
				bbar:new Ext.PagingToolbar({
				pageSize: 15,
				id:'ptbaux',
				store: stGpTema,
				displayInfo: true,
				displayMsg: perfil.etiquetas.lbMsgbbarI,
				emptyMsg: perfil.etiquetas.lbMsgbbarII
			})
			});
			////------------ Trabajo con el PagingToolbar ------------////
			Ext.getCmp('ptbaux').on('change',function(){
				sm.selectFirstRow();
			},this)
			
			////------------ Renderiar el arbol ------------////
			var panel = new Ext.Panel({
				layout:'border',
				title:perfil.etiquetas.lbTitPanelTit,
				renderTo:'panel',
				items:[gpTema],
				tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
				keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    				if(auxDel && auxDel1 && auxDel2)
		    					eliminarTema();
		    			}
		    		  },
		    		  {
		    		  	key:"i",
		    			alt:true,
		    			fn: function(){
							if(auxIns && auxIns2)		    			
		    					winForm('Ins');}
		    		  },
		    		  {
		    		  	key:"m",
		    			alt:true,
		    			fn: function(){
		    				if(auxMod && auxMod1 && auxMod2)
		    					winForm('Mod');}
		    		  }])
			});
			stGpTema.on('load',function(){
				if(stGpTema.getCount() != 0)
				{
					auxMod1 = true;
					auxDel1 = true;
				}
				else
				{
					auxMod1 = false;
					auxDel1 = false;
				}
			},this)
			////------------ Eventos para hotkeys ------------////
			btnAdicionar.on('show',function(){
				auxIns = true;
			},this)
			btnEliminar.on('show',function(){
				auxDel = true;
			},this)
			btnModificar.on('show',function(){
				auxMod = true;
			},this)
			
			////------------ ViewPort ------------////
			var vpTema = new Ext.Viewport({
				layout:'fit',
				items:panel
			})
			stGpTema.load({params:{start:0, limit:15}});
			
			////------------ Formulario ------------////
			regTema = new Ext.FormPanel({
					labelAlign: 'top',
					frame:true,
					bodyStyle:'padding:5px 5px 0',
					items: [{
							layout:'column',
							items:[{
										columnWidth:.5,
										layout:'form',
										items:[{
													xtype:'textfield',
													fieldLabel:perfil.etiquetas.lbFLDenominacion,
													id:'denominacion',
													allowBlank:false,
                                                    maxLength:40,    
							            			blankText:perfil.etiquetas.lbMsgBlank,
							            			regex:tipos,
													regexText:perfil.etiquetas.lbMsgregexI,
													anchor:'95%'
											  }]
								   },
								   {
										columnWidth:.5,
										layout:'form',
										items:[{
													xtype:'textfield',
													fieldLabel:perfil.etiquetas.lbFLAbreviatura,
													id:'abreviatura',
                                                    maxLength:40,    
													allowBlank:false,
						            				blankText:perfil.etiquetas.lbMsgBlank,
						            				regex:abreviatura,
													regexText:perfil.etiquetas.lbMsgregexI,
													anchor:'95%'
											   }]
								   },
								   {
										columnWidth:1,
										layout:'form',
										items:[{
													xtype:'textarea',
													fieldLabel:perfil.etiquetas.lbFLDescripcion,
													id:'descripcion',
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
								resizable: false,
								title:perfil.etiquetas.lbTitVentanaTitI,width:350,height:220,
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
									handler:function(){adicionarTema('apl');}
								},
								{
									icon:perfil.dirImg+'aceptar.png',
									iconCls:'btn',
									text:perfil.etiquetas.lbBtnAceptar,
									handler:function(){adicionarTema();}
								}]
							});
							winIns.on('show',function(){
								auxIns2 = false;
								auxMod2 = false;
								auxDel2 = false;
							},this)
							winIns.on('hide',function(){
								auxIns2 = true;
								auxMod2 = true;
								auxDel2 = true;
							},this)
						}
						regTema.getForm().reset(); 
						winIns.add(regTema);
						winIns.doLayout();
						winIns.show();
						}break;
						case 'Mod':{
							if(!winMod)
							{
								winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
									resizable: false,
									title:perfil.etiquetas.lbTitVentanaTitII,width:350,height:220,
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
										handler:function(){modificarTema();}
									}]
								});
								winMod.on('show',function(){
									auxIns2 = false;
									auxMod2 = false;
									auxDel2 = false;
								},this)
								winMod.on('hide',function(){
									auxIns2 = true;
									auxMod2 = true;
									auxDel2 = true;
								},this)
							}
							winMod.add(regTema);						
							winMod.doLayout();
							winMod.show();
							regTema.getForm().loadRecord(sm.getSelected());	
					}break;
				}
			}
			
			////------------ Adicionar Tema ------------////
			function adicionarTema(apl){
				if (regTema .getForm().isValid())
				{
					regTema .getForm().submit({
						url:'insertartema',
						waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
						failure: function(form, action){
							if(action.result.codMsg != 3)
							{
								mostrarMensaje(action.result.codMsg,action.result.mensaje); 
								regTema .getForm().reset(); 
								stGpTema.reload();					
								if(!apl) 
								winIns.hide();
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
			
			////------------ Modififcar Tema ------------////
			function modificarTema(){
				if (regTema .getForm().isValid())
				{
					regTema .getForm().submit({
						url:'modificartema',
						waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
						params:{idtema:sm.getSelected().data.idtema},
						failure: function(form, action){
							if(action.result.codMsg != 3)
							{
								mostrarMensaje(action.result.codMsg,action.result.mensaje); 
								stGpTema.reload();
								winMod.hide();
							}
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
						}
					});
				}
                else
                    mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
			}
			
			////------------ Eliminar  Tema ------------////
			function eliminarTema(){
				mostrarMensaje(2,perfil.etiquetas.lbMsgFunEliminarMsgI,elimina);
				function elimina(btnPresionado){
					if (btnPresionado == 'ok')
					{
						Ext.Ajax.request({
							url: 'eliminarnomtema',
							method:'POST',
							params:{idtema:sm.getSelected().data.idtema},
							callback: function (options,success,response){
									responseData = Ext.decode(response.responseText);
									if(responseData.codMsg == 1)
									{
										mostrarMensaje(responseData.codMsg,responseData.mensaje);
										stGpTema.reload();
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
		
		
