		var perfil = window.parent.UCID.portal.perfil;
		perfil.etiquetas = Object();
		UCID.portal.cargarEtiquetas('gestnomidioma',cargarInterfaz);
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
		
		////------------ Declarar variables ------------////
		var winIns, winMod,winCamb;
		var auxIns = false;
		var auxMod = false;
		var auxDel = false;
		var auxMod1 = false;
		var auxDel1 = false;
		var auxIns2 = true;
		var auxMod2 = true;
		var auxDel2 = true;
		
		////------------ Area de Validaciones ------------////
		var tipos,abreviatura;
		tipos = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d_]*)+(((\(){1}).([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d_ ]*)+((\)){1}))?$$/;
		abreviatura = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\@\#\_]*)+$/;
		
		function cargarInterfaz()
		{
			////------------ Botones ------------////
			btnAdicionar = new Ext.Button({id:'btnAgrIdioma', hidden:true, icon:perfil.dirImg+'adicionar.png',iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');}  });
			btnModificar = new Ext.Button({disabled:true, id:'btnModIdioma', hidden:true, icon:perfil.dirImg+'modificar.png',iconCls:'btn', text:perfil.etiquetas.lbBtnModificar, handler:function(){winForm('Mod');} });
			btnEliminar = new Ext.Button({disabled:true, id:'btnEliIdioma', hidden:true, icon:perfil.dirImg+'eliminar.png',iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar,handler:function(){eliminarIdioma();} });
			/*btnAyuda = new Ext.Button({id:'btnAyuIdioma', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
			UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
			////------------ Store del Grid de Idiomas ------------////
			stgpIdioma =  new Ext.data.Store({
				url: 'cargarnomidioma',
				reader:new Ext.data.JsonReader({
						totalProperty: "cantidad_filas",
						root: "datos",
						id: "ididioma"
						},
						[
							{name:'ididioma',mapping:'ididioma'},
							{name:'denominacion',mapping:'denominacion'},
							{name:'abreviatura',mapping:'abreviatura'}						
						])
				});
			////------------ Establesco modo de seleccion de grid (single) ------------////
			sm = new Ext.grid.RowSelectionModel({singleSelect:true});
			sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
									btnModificar.enable();
									btnEliminar.enable();
								}, this);
			////------------ Defino el grid de Idiomas ------------////
			var gpIdioma = new Ext.grid.GridPanel({
				frame:true,
				region:'center',
				iconCls:'icon-grid',
				autoExpandColumn:'expandir',
				store:stgpIdioma,
				sm:sm,
				columns: [
							{hidden: true, hideable: false, dataIndex: 'ididioma'},
							{header: perfil.etiquetas.lbTitDenominacion, width:200, dataIndex: 'denominacion', id:'expandir'},
							{header: perfil.etiquetas.lbTitAbreviatura, width:300, dataIndex: 'abreviatura'}						
					 	 ],
				loadMask:{store:stgpIdioma},
				bbar:new Ext.PagingToolbar({
					pageSize: 15,
					id:'ptbaux',
					store: stgpIdioma,
					displayInfo: true,
					displayMsg: perfil.etiquetas.lbMsgbbarI,
					emptyMsg: perfil.etiquetas.lbMsgbbarII
		 		})
				});
			////------------ Trabajo con el PagingToolbar ------------////
			Ext.getCmp('ptbaux').on('change',function(){
				sm.selectFirstRow();
			},this)
			
			////------------ Panel con los componentes ------------////
			var panel = new Ext.Panel({
				layout:'border',
				title:perfil.etiquetas.lbTitPanelTit,
				items:[gpIdioma],
				tbar:[btnAdicionar,btnModificar,btnEliminar,/*btnAyuda*/],
				keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    				if(auxDel && auxDel1 && auxDel2)
		    					eliminarIdioma();
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
			stgpIdioma.on('load',function(){
				if(stgpIdioma.getCount() != 0)
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
			
			////------------ Formulario ------------////
			var regIdioma = new Ext.FormPanel({
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
										anchor:'80%'
								  }]
						   },
						   {
							columnWidth:.5,
							layout: 'form',
							items: [{
										xtype:'textfield',
										fieldLabel: perfil.etiquetas.lbFLAbreviatura,
										id:'abreviatura',	
						           		allowBlank:false,
                                        maxLength:40,    
						            	blankText:perfil.etiquetas.lbMsgBlank,
						            	regex:abreviatura,
										regexText:perfil.etiquetas.lbMsgregexI,
										anchor:'80%'
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
									title:perfil.etiquetas.lbTitVentanaTitI,width:300,height:150,
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
										handler:function(){adicionarIdioma('apl');}
									},
									{	
										icon:perfil.dirImg+'aceptar.png',
										iconCls:'btn',
										text:perfil.etiquetas.lbBtnAceptar,
										handler:function(){adicionarIdioma();}
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
						regIdioma.getForm().reset(); 
						winIns.add(regIdioma);					
						winIns.doLayout();
						winIns.show();
						}break;
						case 'Mod':{
							if(!winMod)
							{
								winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
									resizable: false,
									title:perfil.etiquetas.lbTitVentanaTitII,width:300,height:150,
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
										handler:function(){modificarIdioma();}
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
							regIdioma.getForm().reset();
	                        winMod.add(regIdioma);												
							winMod.doLayout();	
							winMod.show();
							regIdioma.getForm().loadRecord(sm.getSelected());
						}break;
					}
				}
			
			////------------ Viewport ------------////
			var vpGestIdioma = new Ext.Viewport({
				layout:'fit',
				items:panel
				})
			stgpIdioma.load({params:{start:0, limit:15}});
			
			
			////------------ Adicionar Idiomas ------------////
			function adicionarIdioma(apl){
				if (regIdioma.getForm().isValid())
				{
					regIdioma.getForm().submit({
						url:'insertarnomidioma',
						waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
						failure: function(form, action){
							if(action.result.codMsg != 3)
							{
								mostrarMensaje(action.result.codMsg,action.result.mensaje); 
								regIdioma.getForm().reset(); 
								stgpIdioma.reload();
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
			
			////------------ Modififcar Idioma ------------////
			function modificarIdioma(){
				if (regIdioma.getForm().isValid()){
					regIdioma.getForm().submit({
						url:'modificarnomidioma',
						waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
						params:{ididioma:sm.getSelected().data.ididioma},
						failure: function(form, action){
							if(action.result.codMsg != 3){
								mostrarMensaje(action.result.codMsg,action.result.mensaje); 
								winMod.hide();
								stgpIdioma.reload();
							}
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							
						}
					});
				}
                else
                    mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
			}
			
			////------------ Eliminar Idioma ------------////
			function eliminarIdioma(){
				mostrarMensaje(2,perfil.etiquetas.lbMsgFunEliminarMsgI,elimina);
				function elimina(btnPresionado){
					if (btnPresionado == 'ok')
					{
						Ext.Ajax.request({
							url: 'eliminarnomidioma',
							method:'POST',
							params:{ididioma:sm.getSelected().data.ididioma},
							callback: function (options,success,response){
									responseData = Ext.decode(response.responseText);
									if(responseData.codMsg == 1)
									{
										mostrarMensaje(responseData.codMsg,responseData.mensaje);
										stgpIdioma.reload();
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