		var perfil = window.parent.UCID.portal.perfil;
		perfil.etiquetas = Object();
		UCID.portal.cargarEtiquetas('gestservidor',cargarInterfaz);
		
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
			
		////------------ Declarar Variables ------------////
		var winIns, winMod,regServ,tipoServidor,openldap,auxauth,ldap;
		var auxIns = false;
		var auxMod = false;
		var auxDel = false;
		var auxMod1 = false;
		var auxDel1 = false;
		var auxIns2 = true;
		var auxMod2 = true;
		var auxDel2 = true;
		
		////------------ Area de Validaciones ------------////
		var esDirIp, tipos, tipoServidor, keyAdd, keyMod, keyDel;
		tipos = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\@\#\_ ]*)+$/;
		esDirIp =  /(^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9]))\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$/ ;
		tipoServidor = /^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9])\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$|^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\@\#\_ ]*)+$/;
		
		function cargarInterfaz()
		{
			////------------ Botones ------------////
			btnAdicionar = new Ext.Button({id:'btnAgrServ', hidden:true, icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdd, handler:function(){winForm('Ins');}  });
			btnModificar = new Ext.Button({disabled:true,id:'btnModServ', hidden:true, icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnModificar, handler:function(){winForm('Mod');} });
			btnCambiarP = new Ext.Button({id:'btnCambServ', hidden:true, icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnCambia, handler:function(){winForm('Camb');}  });
			btnEliminar = new Ext.Button({disabled:true,id:'btnEliServ', hidden:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar,handler:function(){eliminarservidor();} });
			/*btnAyuda = new Ext.Button({id:'btnAyuServ', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda,handler:function(){pepe();}});*/
			UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
		   
			////------------ Combo Box ------------////
		    var dataTmp = [
		        				['bd', 'Servidor de base datos'],
		        				['autenticaci&oacute;n', 'Servidor de autenticaci\xf3n'] 
		    			  ];
			
			var storeDataTmp = new Ext.data.SimpleStore({
		        fields: ['tiposervidor','denominacion'],
		        data : dataTmp
		    });
			////------------ Store del Grid de Servidores ------------////
			var stGpServ =  new Ext.data.Store({
				url: 'cargarservidores',
				reader:new Ext.data.JsonReader({
					totalProperty: "cantidad_filas",
					root: "datos",
					id: "id"
					},
					[
						{name:'idservidor', mapping:'id'},
					 	{name:'denominacion',mapping:'text'},
					 	{name:'tiposervidor',mapping:'tiposervidor'},
					 	{name:'descripcion',mapping:'descripcion'},
					 	{name:'ip',mapping:'ip'},
					 	{name:'ldap',mapping:'ldap'},
					 	{name:'openldap',mapping:'openldap'},
					 	{name:'usuario',mapping:'cadconexion'}
					])
			});
			
			////------------ Establesco modo de seleccion de grid (single)------------////
			sm = new Ext.grid.RowSelectionModel({singleSelect:false});
		
			sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
							btnModificar.enable();
							btnEliminar.enable();
						}, this);
                        
            sm.on('rowselect', function (smodel, rowIndex, keepExisting, record){
				authaux = sm.getSelected().data.tiposervidor;
				if(sm.getSelected().data.openldap == 'openldap')
				{
					openldap = sm.getSelected().data.openldap;
					ldap = '';
					flagaux = sm.getSelected().data.openldap;
				}
				if(sm.getSelected().data.ldap == 'ldap')
				{
					ldap = sm.getSelected().data.ldap;
					openldap = '';
					flagaux = sm.getSelected().data.ldap
				}
            if(sm.getCount()!=1)
                btnModificar.disable();
            else
                btnModificar.enable();
                }, this); 
        
        	sm.on('rowdeselect', function (smodel, rowIndex, keepExisting, record){
            if(sm.getCount()!=1)
                btnModificar.disable();
            else
                btnModificar.enable();
                }, this);
			
			////------------ Defino el grid de Servidores ------------////
			var gpServ = new Ext.grid.GridPanel({
				frame:true,
				region:'center',
				iconCls:'icon-grid',
				autoExpandColumn:'expandir',
				store:stGpServ,
				sm:sm,
				columns: [
							{hidden: true, hideable: false,  dataIndex: 'idservidor'},
							{header:perfil.etiquetas.lbServidor, width:150, dataIndex: 'denominacion'},
							{header: perfil.etiquetas.lbIP, dataIndex: 'ip'},
							{header: perfil.etiquetas.lbTipo, dataIndex: 'tiposervidor'},
							{id:'expandir',header: perfil.etiquetas.lbDes,width:350, dataIndex: 'descripcion'}
						 ],
						 
				loadMask:{store:stGpServ},
				
				bbar:new Ext.PagingToolbar({
				pageSize: 15,
				id:'ptbaux',
				store: stGpServ,
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
				title:perfil.etiquetas.lbTitTituloP,
				renderTo:'panel',
				items:[gpServ],
				tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
				keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    				if(auxDel && auxDel1 && auxDel2)
		    					eliminarservidor();}
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
			stGpServ.on('load',function(){
				if(stGpServ.getCount() != 0)
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
			////------------ Viewport ------------////
			var vpServidorAut = new Ext.Viewport({
			layout:'fit',
			items:panel
			})
			stGpServ.load({params:{start:0,limit:15}});
			////************ ITEMS DEL FORMULARIO ************////
			////------------ Combo servidores del formulario ------------////
			var cbservidores = new Ext.form.ComboBox({
				emptyText:perfil.etiquetas.lbMsgSelect,
				editable:false,
                                fieldLabel:perfil.etiquetas.lbTiipoBD,
                                store:storeDataTmp,
                                tabIndex:3,
                                id:'cbservidores',
                                valueField:'tiposervidor',
                                displayField:'denominacion',
                                hiddenName:'tiposervidor',
                                typeAhead: true,
                                mode: 'local',
                                allowBlank:false,
                                blankText:perfil.etiquetas.lbMsgReq,
                                triggerAction: 'all',
                                selectOnFocus:true,
                                anchor:'90%'
			})
			////------------ Textfield usuario del formulario ------------////
			var tfusuario = new Ext.form.TextField({
				hideLabel:true,
				tabIndex:6,
				allowBlank:false,
				width:335,
				id:'usuario'
			})
			////------------ Fieldset usuario del formulario ------------////
			var fsusuario = new Ext.form.FieldSet({
				title: perfil.etiquetas.lbTitUsuario,
				autoHeight:true,
				hidden:'true',
				collapsed:false,
				anchor:'100%',
				items:[tfusuario]	
			})
			////------------ Checkbox LDAP y OpenLDAP ------------////
			var chbldap = new Ext.form.Radio({
				hideLabel:'true',
				tabIndex:4,
				name:'chbauth',
				boxLabel:perfil.etiquetas.lbTitLDAP,
				id:'ldap'
			})
			var chbopenldap = new Ext.form.Radio({
				hideLabel:'true',
				tabIndex:5,
				name:'chbauth',
				boxLabel:perfil.etiquetas.lbTitOpenLDAP,
				id:'openldap'
			})
			////------------ Fieldset tipos de autenticaci�n del formulario ------------////
			var fstiposaut = new Ext.form.FieldSet({
				title: perfil.etiquetas.lbTitTipoAuth,
				autoHeight:true,
				collapsed:false,
				hidden:'true',
				anchor:'90%',
				id:'fstiposaut',
				items:[chbldap,chbopenldap]	
			})
			////************ FIN ITEMS DEL FORMULARIO ************////		
			////------------ Formulario ------------////
			regServ = new Ext.FormPanel({ 
				labelAlign: 'top',
				autoHeight:true,
				id:1,
				frame:true,
				bodyStyle:'padding:5px 5px 0',	    		  
				items: [{
						layout:'column',
						items:[{
								columnWidth:.5,
								layout:'form',
								items:[{
											xtype:'textfield',
											fieldLabel:perfil.etiquetas.lbNombreS,
											id:'denominacion',
											tabIndex:1,
											allowBlank: false,
											blankText:perfil.etiquetas.lbMsgReq,
											regex:tipoServidor, 
											regexText:perfil.etiquetas.lbMsgRegex,
											anchor:'90%'
									   }, 
									   cbservidores]
								},
								{
									columnWidth:.5,
									layout: 'form',
									items: [{
												xtype:'textfield',
												fieldLabel: perfil.etiquetas.lbIPLab,
												id:'ip',
												tabIndex:2,
												allowBlank: false,
												blankText:perfil.etiquetas.lbMsgCReq,
												regex: esDirIp,
												regexText: perfil.etiquetas.lbMsgIPInc, 
												anchor:'90%'
										   },fstiposaut]
								},
								{
									columnWidth:.99,
									layout: 'form',
									items: [fsusuario]
								},
								{
									columnWidth:.99,
									layout: 'form',
									items: [{
												xtype:'textarea',
												autoHeight:true,
												tabIndex:7,
												fieldLabel: perfil.etiquetas.lbText,
												id: 'descripcion',
												anchor:'100%'
										   }]
								}]
						}]
			});
			////************ EVENTOS ************////
			////------------ Evento para activar o desactivar fieldset usuario y radio openldap a travez de radio ldap ------------////
			chbldap.on('check',function(){
				if(chbldap.getValue() == true)
				{
					ldap = 'ldap';
					openldap = '';
					fsusuario.hide();
					fsusuario.hide();
					fsusuario.disable();
					tfusuario.disable();
				}
			})
			cbservidores.on('select',function(){
				chbldap.setValue(false);
				chbopenldap.setValue(false);
			})
			////------------ Evento para activar o desactivar fieldset tipos de autenticaci�n ------------//// 
			cbservidores.on('select',function(){
				if(cbservidores.getValue() == 'autenticaci&oacute;n' )
				{	
					fstiposaut.show();
					fsusuario.enable();
					fstiposaut.enable();
					tfusuario.enable();
				}	
				else
				{
					fsusuario.hide();
					fstiposaut.hide();
					tfusuario.hide();
					fsusuario.disable();
					fstiposaut.disable();
					tfusuario.disable();
				}
			})
			////------------ Evento para activar o desactivar fieldset usuario a travez del radio Openldap ------------////
			chbopenldap.on('check',function(){
				if(chbopenldap.getValue() == true)
				{	
					openldap = 'openldap';
					ldap = '';
					fsusuario.show();
					fsusuario.enable();
					tfusuario.enable();
					tfusuario.show();
				}	
				else
					fsusuario.hide();
			})
			////************ FIN EVENTOS ************////
			////------------ Cargar la ventana ------------////
			function winForm(opcion){
				switch(opcion){
					case 'Ins':{
						if(!winIns)
						{
							winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
								title:perfil.etiquetas.lbTitAdicionarV,
								width:400,
								height:400,
								resizable:false,
								x:312,
								y:184,
								autoHeight:true,
								shadow:false,
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
									handler:function(){adicionarservidor('apl');}
								},
								{
									icon:perfil.dirImg+'aceptar.png',
									iconCls:'btn',
									text:perfil.etiquetas.lbBtnAceptar,
									handler:function(){adicionarservidor();}
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
						regServ.getForm().reset();
						fstiposaut.hide();
						winIns.show();
						winIns.add(regServ);
						winIns.doLayout();
					}break;
					case 'Mod':
					{
						if(!winMod)
						{
							winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
								title:perfil.etiquetas.lbTitModificalo,
								width:400,
								height:400,
								x:312,
								y:184,
								resizable:false,
								autoHeight:true,
								shadow:false,
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
									handler:function(){modificarservidor();}
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
						regServ.getForm().reset();
						winMod.add(regServ);
						winMod.doLayout();	
						winMod.show();
						
						if(sm.getSelected().data.tiposervidor == 'autenticaci&oacute;n' && sm.getSelected().data.openldap)
						{
							fstiposaut.show();
							fsusuario.show();
							chbopenldap.setValue(true);
						}
						else if(sm.getSelected().data.tiposervidor == 'autenticaci&oacute;n' && sm.getSelected().data.ldap)
						{
							fstiposaut.show();
							chbldap.setValue(true);
						}	
						else
						{
							fstiposaut.hide();
							fsusuario.hide();
							tfusuario.hide();
							fsusuario.disable();
							fstiposaut.disable();
							tfusuario.disable();
						}
						regServ.getForm().loadRecord(sm.getSelected());
				}break;
				}
			}
			////------------ Adicionar Servidor ------------////
			function adicionarservidor(apl){
					if (regServ.getForm().isValid())
					{
						if(cbservidores.getValue() == 'bd')
						{
							regServ.getForm().submit({
							url:'insertarserv',
							waitMsg:perfil.etiquetas.lbMsgAdicionandoS,
							failure: function(form, action){
								if(action.result.codMsg != 3)
								{
									mostrarMensaje(action.result.codMsg,action.result.mensaje); 
									if(!apl) 
										winIns.hide();
									regServ.getForm().reset();
									stGpServ.reload();
									sm.clearSelections();
									btnModificar.disable();
									btnEliminar.disable();
								}
								if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
								}	
							});
						}
						else if(chbopenldap.checked)
						{
							regServ.getForm().submit({
								params:{openldap:openldap},
							url:'insertarserv',
							waitMsg:perfil.etiquetas.lbMsgAdicionandoS,
							failure: function(form, action){
								if(action.result.codMsg != 3)
								{
									mostrarMensaje(action.result.codMsg,action.result.mensaje); 
									if(!apl) 
										winIns.hide();
									regServ.getForm().reset();
									stGpServ.reload();
									sm.clearSelections();
									btnModificar.disable();
									btnEliminar.disable();
								}
								if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
								}	
							});
						}
						else if(chbldap.checked)
						{
							regServ.getForm().submit({
							params:{ldap:ldap},
							url:'insertarserv',
							waitMsg:perfil.etiquetas.lbMsgAdicionandoS,
							failure: function(form, action){
								if(action.result.codMsg != 3)
								{
									mostrarMensaje(action.result.codMsg,action.result.mensaje); 
									if(!apl) 
										winIns.hide();
									regServ.getForm().reset();
									stGpServ.reload();
									sm.clearSelections();
									btnModificar.disable();
									btnEliminar.disable();
								}
								if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
								}	
							});
						}
						else
							mostrarMensaje(3,'Debe seleccionar un tipo de servidor de autenticaci&oacute;n.');
					}
                else
                    mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                    
			}
			////------------ Modififcar Servidor ------------////
			function modificarservidor()
			{
				if (regServ.getForm().isValid())
				{
					if(cbservidores.getValue() == 'bd')
					{
						regServ.getForm().submit({
							url:'modificarserv',
							waitMsg:perfil.etiquetas.lbMsgModificandoS,
							params:{idservidor:sm.getSelected().data.idservidor},
							failure: function(form, action){
								if(action.result.codMsg != 3)
								{
									mostrarMensaje(action.result.codMsg,action.result.mensaje); 
									stGpServ.reload();
									winMod.hide();
									regServ.getForm().reset();
								}
						        if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
						    }
					    });
					}
					else if(chbldap.checked)
					{
						regServ.getForm().submit({
							url:'modificarserv',
							waitMsg:perfil.etiquetas.lbMsgModificandoS,
							params:{idservidor:sm.getSelected().data.idservidor,ldap:ldap},
							failure: function(form, action){
								if(action.result.codMsg != 3)
								{
									mostrarMensaje(action.result.codMsg,action.result.mensaje); 
									stGpServ.reload();
									winMod.hide();
									regServ.getForm().reset();
									
								}
						        if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
						    }
					    });	
					}
					else if(chbopenldap.checked)
					{
					regServ.getForm().submit({
							url:'modificarserv',
							waitMsg:perfil.etiquetas.lbMsgModificandoS,
							params:{idservidor:sm.getSelected().data.idservidor,openldap:openldap},
							failure: function(form, action){
								if(action.result.codMsg != 3)
								{
									mostrarMensaje(action.result.codMsg,action.result.mensaje); 
									stGpServ.reload();
									regServ.getForm().reset();
									winMod.hide();
									
								}
						        if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
						    }
					    });	
					
					}
		        }
             	else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);               
			}
			////------------------- Eliminar Servidor ------------------------////
			function elimina1(btnPresionado)
			{
		    	if (btnPresionado == 'ok')
		    	{
		        Ext.Ajax.request({
		            url: 'eliminarservidor',
		            method:'POST',
		            params:{idservidor:sm.getSelected().data.idservidor},
		            callback: function (options,success,response){
		            responseData = Ext.decode(response.responseText);
		                   if(responseData.codMsg == 1)
		                   {
		                       	stGpServ.reload();
								sm.clearSelections();
								btnModificar.disable();
								btnEliminar.disable();
		                   }
		                   if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
		                  }
		            });
		   		}
		    } 
			
			function eliminarservidor()
			{
                var arrServElim = sm.getSelections();
                var arrayServElim = [];
                for (var i=0;i<arrServElim.length;i++)
                arrayServElim.push(arrServElim[i].data.idservidor);
				mostrarMensaje(2,perfil.etiquetas.lbMsgprueba,elimina);
				function elimina(btnPresionado)
				{
					if (btnPresionado == 'ok')
					{
						Ext.Ajax.request({
							url: 'comprobarservidor',
							method:'POST',
							params:{arrayServ:Ext.encode(arrayServElim)},
							callback: function (options,success,response){
							responseData = Ext.decode(response.responseText);
								if(responseData.tiene == 1)
								mostrarMensaje(2,perfil.etiquetas.lbMsgEliminarGA,elimina1)
								if(responseData.codMsg == 1)
								{
									mostrarMensaje(responseData.codMsg,responseData.mensaje);
									stGpServ.reload();
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
			
	
