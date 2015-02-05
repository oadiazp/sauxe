	
	Ext.QuickTips.init();   
	////------------ Declarar variables ------------////
	var winInsU, winInsR, winModU, winInsAPU,winInsPR,winInsRR, winInsRU, winModR,radiobuton1,radiobuton2,radiobuton3,radiobuton4,radiobuton5,radiobuton6,radiobuton7, record, usuariorol,fila,col;
	var auxDel2 = true;
	var arrayauxgranted = new Array();
	////------------ Botones ------------////
	btnAdicionarU = new Ext.Button({id:'btnAgrU',iconCls:'btn', icon:perfil.dirImg+'adicionar.png' ,text:'Adicionar', handler:function(){winFormOracle('InsU');}});
	btnModificarU = new Ext.Button({id:'btnModU',iconCls:'btn', icon:perfil.dirImg+'modificar.png', text:'Modificar', handler:function(){winFormOracle('ModU');}});
	btnEliminarU = new Ext.Button({id:'btnEliU',iconCls:'btn', icon:perfil.dirImg+'eliminar.png', text:'Eliminar',handler:function(){eliminarU();}});
	btnAsignarPU = new Ext.Button({id:'btnAsignarPU',iconCls:'btn', text:'Asignar privilegios', handler:function(){winFormOracle('APU');}});
	btnAsignarRU = new Ext.Button({id:'btnAsignarRU',iconCls:'btn', text:'Asignar roles',handler:function(){winFormOracle('ARU');}});
	
	btnAdicionarR = new Ext.Button({id:'btnAgrR',iconCls:'btn', icon:perfil.dirImg+'adicionar.png', text:'Adicionar', handler:function(){winFormOracle('InsR');}});
	btnModificarR = new Ext.Button({id:'btnModR',iconCls:'btn', icon:perfil.dirImg+'modificar.png', text:'Modificar', handler:function(){winFormOracle('ModR');}});
	btnEliminarR = new Ext.Button({id:'btnEliR',iconCls:'btn', icon:perfil.dirImg+'eliminar.png', text:'Eliminar',handler:function(){eliminarR();}});
	btnAsignarPR = new Ext.Button({id:'btnAsignarPR',iconCls:'btn', text:'Asignar privilegios', handler:function(){winFormOracle('APR');}});
	btnAsignarRR = new Ext.Button({id:'btnAsignarRR',iconCls:'btn', text:'Asignar roles',handler:function(){winFormOracle('ARR');}});
	
	btnGrantedoff = new Ext.Button({id:'btnGrantedoff',iconCls:'btn', icon:perfil.dirImg+'adicionar.png', text:'Asignar', handler:function(){winFormOracle('InsR');}});
	btnGrantedon = new Ext.Button({id:'btnGrantedon',iconCls:'btn', icon:perfil.dirImg+'modificar.png', text:'No Asignar', handler:function(){winFormOracle('ModR');}});
	btnAdminon = new Ext.Button({id:'btnAdminon',iconCls:'btn', icon:perfil.dirImg+'eliminar.png', text:'Administrar',handler:function(){eliminarR();}});
	btnAdminoff = new Ext.Button({id:'btnAdminoff',iconCls:'btn', text:'No Administrar', handler:function(){winFormOracle('APR');}});
	btnDefaulton = new Ext.Button({id:'btnDefaulton',iconCls:'btn', text:'Asignar roles',handler:function(){winFormOracle('ARR');}});
	btnDefaultoff = new Ext.Button({id:'btnDefaultoff',iconCls:'btn', text:'Asignar roles',handler:function(){winFormOracle('ARR');}});
	
	////------------ Columnas de tipo checkbox ------------////
	var checkColumnGranted = new Ext.grid.CheckColumn({
         header: 'Granted',
         dataIndex: 'granted',
         align: 'center',
         width: 80
    });
 	var checkColumnAO = new Ext.grid.CheckColumn({
            
         header: 'Admin option',
         
         dataIndex: 'adminopcion',
         align: 'center',
         width: 80
 	});
 	var checkColumnGrantedR = new Ext.grid.CheckColumn({
         header: 'Granted',
         dataIndex: 'granted',
         align: 'center',
         width: 80
    });
 	var checkColumnAOR = new Ext.grid.CheckColumn({
         header: 'Admin option',
        
         dataIndex: 'adminopcion',
         align: 'center',
         width: 80
 	});
 	var checkColumnGrantedPR = new Ext.grid.CheckColumn({
         header: 'Granted',
         dataIndex: 'granted',
         align: 'center',
         width: 80
    });
 	var checkColumnAOPR = new Ext.grid.CheckColumn({
         header: 'Admin option',
       
         dataIndex: 'adminopcion',
         align: 'center',
         width: 80
 	});
 	var checkColumnDefaultRU = new Ext.grid.CheckColumn({
         header: 'Por defecto',
         dataIndex: 'pordefecto',
         align: 'center',
         width: 80
    });
 	var checkColumnAORU = new Ext.grid.CheckColumn({
         header: 'Admin option',
         dataIndex: 'adminopcion',
        
         align: 'center',
         width: 80
 	});
 	var checkColumnGrantedRU = new Ext.grid.CheckColumn({
         header: 'Granted',
         dataIndex: 'granted',
         align: 'center',
         width: 80
    });
    var checkColumnAORR = new Ext.grid.CheckColumn({
         header: 'Admin option',
         dataIndex: 'adminopcion',
         
         align: 'center',
         width: 80
 	});
 	var checkColumnGrantedRR = new Ext.grid.CheckColumn({
         header: 'Granted',
         dataIndex: 'granted',
         align: 'center',
         width: 80
    });
	////------------ Store del Grid de usuarios ------------////
	stgpU =  new Ext.data.Store({
		url: 'oracleCargarUsuariosBD',
		 listeners:{'beforeload':function(thisstore,objeto){
                objeto.params.puerto=puerto,
                objeto.params.ip=ipservidor,
                objeto.params.user=usuario,
                 objeto.params.gestor=gestorBD,
                objeto.params.passw=password,
                objeto.params.bd=baseDato
                }},
				reader:new Ext.data.JsonReader({
				totalProperty: "cantidad_filas",
				root: "datos",
				id: "id"
				},
				[
					{name:'iduser',mapping:'id'},						
					{name:'estado',mapping:'estado'},						
					{name:'denominacion',mapping:'user'},
					{name:'creado',mapping:'creado'},
					{name:'expira',mapping:'expira'},
					{name:'bloqueado',mapping:'bloqueado'},
					{name:'radiobutton1',mapping:'radiobutton1'},
					{name:'radiobutton2',mapping:'radiobutton2'},
					{name:'radiobutton3',mapping:'radiobutton3'}
					
				])
	});
	////------------ Store del Grid de roles ------------////
	stgpRoles =  new Ext.data.Store({
		url: 'oracleCargarRolesBD',
		listeners:{'beforeload':function(thisstore,objeto){
                objeto.params.puerto=puerto,
                objeto.params.ip=ipservidor,
                objeto.params.user=usuario,
                 objeto.params.gestor=gestorBD,
                objeto.params.passw=password,
                objeto.params.bd=baseDato
                }},
		reader:new Ext.data.JsonReader({
				totalProperty: "cantidad_filas",
				root: "datos",
				id: "iddesktop"
				},
				[
					{name:'iddesktop',mapping:'iddesktop'},						
					{name:'denominacionR',mapping:'rol'},						
					{name:'pidepass',mapping:'pidepass'},
					{name:'radiobutton4',mapping:'radiobutton4'},
					{name:'radiobutton5',mapping:'radiobutton5'},
					{name:'radiobutton6',mapping:'radiobutton6'},
					{name:'radiobutton7',mapping:'radiobutton7'}
				])
	});
	////------------ Store del Grid de Asignar privilegios a usuarios ------------////
	stgpAsignarPU =  new Ext.data.Store({
		url: 'oracleMuestraPrivilegiosAsignados',
		listeners:{'beforeload':function(thisstore,objeto){
                objeto.params.puerto=puerto,
                objeto.params.ip=ipservidor,
                objeto.params.user=usuario,
                 objeto.params.gestor=gestorBD,
                objeto.params.passw=password,
                objeto.params.bd=baseDato,
                objeto.params.usuariorol=usuariorol
                }},
		reader:new Ext.data.JsonReader({
                                totalProperty: "cantidad_filas",
				root: "datos"
				},
				[
					{name:'privilegio',mapping:'privilegio'},						
					{name:'adminopcion',mapping:'opcion'},						
					{name:'granted',mapping:'grantee'},
				])
	});
	////------------ Store del Grid de Asignar roles a usuarios ------------////
	stgpAsignarRU =  new Ext.data.Store({
		url: 'oracleMuestraRolesAsignadosUser',
		listeners:{'beforeload':function(thisstore,objeto){
                objeto.params.puerto=puerto,
                objeto.params.ip=ipservidor,
                objeto.params.user=usuario,
                 objeto.params.gestor=gestorBD,
                objeto.params.passw=password,
                objeto.params.bd=baseDato,
                objeto.params.usuariorol=usuariorol
                }},
		reader:new Ext.data.JsonReader({
                                totalProperty: "cantidad_filas",
				root: "datos"
				},

				[
					{name:'rol',mapping:'rol'},						
					{name:'adminopcion',mapping:'opcion'},						
					{name:'granted',mapping:'grentee'},
					{name:'pordefecto',mapping:'pordefecto'},
				])
	});
	////------------ Store del Grid de Asignar roles a roles ------------////
	stgpAsignarRR =  new Ext.data.Store({
		url: 'oracleMuestraRolesAsignadosRol',
		listeners:{'beforeload':function(thisstore,objeto){
                objeto.params.puerto=puerto,
                objeto.params.ip=ipservidor,
                objeto.params.user=usuario,
                 objeto.params.gestor=gestorBD,
                objeto.params.passw=password,
                objeto.params.bd=baseDato,
                objeto.params.usuariorol=usuariorol
                }},
		reader:new Ext.data.JsonReader({
                                totalProperty: "cantidad_filas",
				root: "datos"
				},
				[
					{name:'rol',mapping:'rol'},						
					{name:'adminopcion',mapping:'opcion'},						
					{name:'granted',mapping:'grentee'}
				])
	});
	////------------ Store del Grid de Asignar privilegios a roles ------------////
	stgpAsignarPR =  new Ext.data.Store({
		url: 'oracleMuestraPrivilegiosAsignados',
		listeners:{'beforeload':function(thisstore,objeto){
                objeto.params.puerto=puerto,
                objeto.params.ip=ipservidor,
                objeto.params.user=usuario,
                 objeto.params.gestor=gestorBD,
                objeto.params.passw=password,
                objeto.params.bd=baseDato,
                objeto.params.usuariorol=usuariorol
                }},
		reader:new Ext.data.JsonReader({
                                totalProperty: "cantidad_filas",
				root: "datos"
				},
				[
					{name:'privilegio',mapping:'privilegio'},						
					{name:'adminopcion',mapping:'opcion'},						
					{name:'granted',mapping:'grantee'},
				])
	});
	////------------ Establesco modo de seleccion de grid usuarios (single) ------------////
	smusers = new Ext.grid.RowSelectionModel({singleSelect:true});
	smusers.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
		btnModificarU.enable();
		btnEliminarU.enable();
	}, this);
	smusers.on('rowselect', function (smodel, rowIndex, keepExisting, record){
		var user = smusers.getSelected().data.user;
		usuariorol = smusers.getSelected().data.denominacion;
	}, this);					
	////------------ Establesco modo de seleccion de grid roles(single) ------------////
	smroles = new Ext.grid.RowSelectionModel({singleSelect:true});
	smroles.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
		btnModificarR.enable();
		btnEliminarR.enable();
	}, this);
	smroles.on('rowselect', function (smodel, rowIndex, keepExisting, record){
		usuariorol = smroles.getSelected().data.denominacionR;
	}, this);
	////------------ Establesco modo de seleccion de grid asignar privilegios a Usuarios(single) ------------////
	smAsignarPU = new Ext.grid.RowSelectionModel({singleSelect:true});
	////------------ Establesco modo de seleccion de grid asignar roles a Usuarios(single) ------------////
	smAsignarRU = new Ext.grid.RowSelectionModel({singleSelect:true});
	////------------ Establesco modo de seleccion de grid asignar privilegios a roles(single) ------------////
	smAsignarPR = new Ext.grid.RowSelectionModel({singleSelect:true});
	////------------ Establesco modo de seleccion de grid asignar roles a roles(single) ------------////
	smAsignarRR = new Ext.grid.RowSelectionModel({singleSelect:true});
	////------------ Defino el grid de usuarios ------------////
	var gpUsuarios = new Ext.grid.GridPanel({		
                frame:true,
		iconCls:'icon-grid',
		height:500,
		autoExpandColumn:'expandir',
		store:stgpU,
		sm:smusers,
		columns: [
					{hidden: true, hideable: false, dataIndex: 'iduser'},
					{header: 'Denominaci&oacute;n', width:100, dataIndex: 'denominacion', id:'expandir'},
					{header: 'Estado', width:100, dataIndex: 'estado'},
					{header: 'Creado', width:100, dataIndex: 'creado'},
					{header: 'Expira', width:100, dataIndex: 'expira'},
					{header: 'Bloqueado', width:100, dataIndex: 'bloqueado'}
			 	 ],
		loadMask:{store:stgpU},	
		bbar:new Ext.PagingToolbar({
		pageSize: 15,
		store: stgpU,
		displayInfo: true,
		displayMsg: '',
		emptyMsg: ''
		})
	});
        
	////------------ Defino el grid de roles ------------////
	var gpRoles = new Ext.grid.GridPanel({
		frame:true,
		iconCls:'icon-grid',

		height:500,
		autoExpandColumn:'expandir',
		store:stgpRoles,
		sm:smroles,
		columns: [
					{header: 'Denominaci&oacute;n', width:200, dataIndex: 'denominacionR', id:'expandir'},
					{header: 'Password requerido', width:200, dataIndex: 'pidepass'}
			 	 ],
		loadMask:{store:stgpRoles},	
		bbar:new Ext.PagingToolbar({
		pageSize: 15,
		store: stgpRoles,
		displayInfo: true,
		displayMsg: '',
		emptyMsg: ''
		})
	});
     
    ////------------ Grid editable para asignar privilegios a usuarios ------------////
    var egpAsignarPU = new Ext.grid.EditorGridPanel({
    	frame:true,

    	autoScroll:true,
		iconCls:'icon-grid',
		autoExpandColumn:'expandir',
		clicksToEdit:1,
		store:stgpAsignarPU,
		sm:smAsignarPU,

		plugins:[checkColumnGranted,checkColumnAO],
		columns: [
					{header: 'Privilegios', width:150, dataIndex: 'privilegio', id:'expandir'},
					checkColumnGranted,
					checkColumnAO 
					
			 	 ],
			 	 loadMask:{store:stgpAsignarPU},
                bbar:new Ext.PagingToolbar({
                    pageSize: 10,
                    store: stgpAsignarPU,
                    displayInfo: true,
                    displayMsg: '',
                    emptyMsg: ''
		})
    })
	 	 
    
    ////------------ Grid editable para asignar privilegios a Roles ------------////
    var egpAsignarPR = new Ext.grid.EditorGridPanel({
    	frame:true,
    	autoScroll:true,
		iconCls:'icon-grid',
		autoExpandColumn:'expandir',
		clicksToEdit:1,
                store:stgpAsignarPR,
		sm:smAsignarPR,
		plugins:[checkColumnGrantedPR,checkColumnAOPR],
		columns: [
					{header: 'Privilegios', width:150, dataIndex: 'privilegio', id:'expandir'},
					checkColumnGrantedPR,
					checkColumnAOPR
					
			 	 ],
			 	 loadMask:{store:stgpAsignarPR},
                bbar:new Ext.PagingToolbar({
		pageSize: 10,
		store: stgpAsignarPR,
		displayInfo: true,
		displayMsg: '',
		emptyMsg: ''
		})
	 	 
    })
    ////------------ Grid editable para asignar roles a usuarios ------------////
    var egpAsignarRU = new Ext.grid.EditorGridPanel({
    	frame:true,
    	autoScroll:true,
		iconCls:'icon-grid',
		autoExpandColumn:'expandir',
		clicksToEdit:1,
		store:stgpAsignarRU,
		sm:smAsignarRU,
		plugins:[checkColumnGrantedRU,checkColumnAORU,checkColumnDefaultRU],
		columns: [
					{header: 'Privilegios', width:120, dataIndex: 'rol', id:'expandir'},
					checkColumnGrantedRU,
					checkColumnAORU,
					checkColumnDefaultRU
			 	 ],
			 	 loadMask:{store:stgpAsignarRU},
                bbar:new Ext.PagingToolbar({
                    pageSize: 10,
                    store: stgpAsignarRU,
                    displayInfo: true,
                    displayMsg: '',
                    emptyMsg: ''
		})
	 	 
    })
    ////------------ Grid editable para asignar roles a roles ------------////
    var egpAsignarRR = new Ext.grid.EditorGridPanel({
    	frame:true,
    	autoScroll:true,
		iconCls:'icon-grid',
		autoExpandColumn:'expandir',
		clicksToEdit:1,
		store:stgpAsignarRR,
		sm:smAsignarRR,
		plugins:[checkColumnGrantedR,checkColumnAORR],
		columns: [
					{header: 'Privilegios', width:120, dataIndex: 'rol', id:'expandir'},
					checkColumnGrantedR,
					checkColumnAORR
			 	 ],
			 	 loadMask:{store:stgpAsignarRR},
                bbar:new Ext.PagingToolbar({
                    pageSize: 10,
                    store: stgpAsignarRR,
                    displayInfo: true,
                    displayMsg: '',
                    emptyMsg: ''
		})
	 	 
    })
	 ////------------ Tabpanel principal ------------////
	 var tpPrincipal = new Ext.TabPanel({
        activeTab: 0,
        height:703,
        frame:true,
        defaultType:'panel',
        items:[{
	        		xtype:'panel',
	        		title:'Usuarios',
	        		id:'0',
	        		frame:true,
	        		layout:'form',

	        		listeners: {activate: handleActivate},
	        		tbar:[btnAdicionarU,btnModificarU,btnEliminarU,btnAsignarPU,btnAsignarRU],
	        		items:[gpUsuarios]

        		},
        		{
	        		xtype:'panel',
	        		title:'Roles',
	        		id:'1',
	        		frame:true,
	        		layout:'form',
	        		listeners: {activate: handleActivate},
	        		tbar:[btnAdicionarR,btnModificarR,btnEliminarR,btnAsignarPR,btnAsignarRR],
	        		items:[gpRoles]
        		}]
    });
	////------------ Formulario Usuarios ------------////
	var regOracleU = new Ext.FormPanel({
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
									fieldLabel:'Usuario'/*perfil.etiquetas.lbFLDenominacion*/,
									id:'denominacion',
									allowBlank:false,
                                    maxLength:40, 
					            	//blankText:perfil.etiquetas.lbMsgBlank,
					            	//regex:tipos,
									//regexText:perfil.etiquetas.lbMsgregexI,
									anchor:'95%'
							  }]
				   },
				  {
				  		columnWidth:1,
						layout:'form',
						items:[{
									xtype:'fieldset',
									height:220,
									title:'Tipos de autenticaci&oacute;n' /*perfil.etiquetas.lbFLDescripcion*/,
									id:'descripcion',
									items:[{
										layout:'column',
										items:[{
											columnWidth:.33,
											layout:'form',
											items:[{
												xtype:'radio',
												name:'rbautenticacion',
												hideLabel:true,
												id:'bd',
												value:'pepe',
												boxLabel:'Base de datos'
											},
											]
										},
										{
											columnWidth:.33,
											layout:'form',
											items:[{
												xtype:'radio',
												name:'rbautenticacion',
												id:'so',
												hideLabel:true,
												boxLabel:'Sistema operativo'
											}]
										},
										{
											columnWidth:.33,
											layout:'form',
											items:[{
												xtype:'radio',
												id:'empresa',
												hideLabel:true,
												name:'rbautenticacion',
												boxLabel:'Externo'
										}]
									}]
							   },
							   {
							   	layout:'column',
							   	items:[{
											columnWidth:.99,
											layout:'form',
											items:[
												{
												xtype:'textfield',
												inputType:'password',
												submitValue:true,
												id:'newpass',
												anchor:'45%',
												fieldLabel:'Nueva contrase&ntilde;a'
											},
											{
												xtype:'textfield',
												inputType:'password',
												id:'confirmnewpass',
												anchor:'45%',
												fieldLabel:'Verificar nueva contrase&ntilde;a'
											},
											{
												xtype:'textfield',
												id:'externo',
												hideParent:true, 
												anchor:'100%',
												fieldLabel:'Nombre externo'
											}
											]
										}]
							   }]
				  }]
		}]
	}]});
	////------------ Formulario Roles ------------////
	var regOracleR = new Ext.FormPanel({
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
									fieldLabel:'Rol'/*perfil.etiquetas.lbFLDenominacion*/,
									id:'denominacionR',
									allowBlank:false,
                                    maxLength:40, 
					            	//blankText:perfil.etiquetas.lbMsgBlank,
					            	//regex:tipos,
									//regexText:perfil.etiquetas.lbMsgregexI,
									anchor:'95%'
							  }]
				   },
				  {
				  		columnWidth:1,
						layout:'form',
						items:[{
									xtype:'fieldset',
									height:200,
									title:'Tipos de autenticaci&oacute;n' /*perfil.etiquetas.lbFLDescripcion*/,
									id:'descripcion',
									items:[{
							   	layout:'column',
							   	items:[{
											columnWidth:.99,
											layout:'form',
											items:[
												{
												xtype:'radio',
												name:'rbautenticacionr',
												hideLabel:true,
												id:'nor',
												boxLabel:'No identificado'
											}
											]
										}]
							   },
											{
										layout:'column',
										items:[{
											columnWidth:.33,
											layout:'form',
											items:[{
												xtype:'radio',
												name:'rbautenticacionr',
												hideLabel:true,
												id:'bdr',
												boxLabel:'Base de datos'
											},
											]
										},
										{
											columnWidth:.33,
											layout:'form',
											items:[{
												xtype:'radio',
												name:'rbautenticacionr',
												id:'sor',
												hideLabel:true,
												boxLabel:'Sistema operativo'
											}]
										},
										{
											columnWidth:.33,
											layout:'form',
											items:[{
												xtype:'radio',
												id:'empresar',
												hideLabel:true,
												name:'rbautenticacionr',
												boxLabel:'Externo'
										}]
									}]
							   },
							   {
							   	layout:'column',
							   	items:[{
											columnWidth:.99,
											layout:'form',
											items:[
												{
												xtype:'textfield',
												inputType:'passwordr',
												submitValue:true,
												id:'newpassr',
												anchor:'45%',
												fieldLabel:'Nueva contrase&ntilde;a'
											},
											{
												xtype:'textfield',
												inputType:'password',
												id:'confirmnewpassr',
												anchor:'45%',
												fieldLabel:'Verificar nueva contrase&ntilde;a'
											}
											]
										}]
							   }]
				  }]
		}]
	}]});
	////------------ Cargar la ventana general ------------////
	function winFormOracle(opcion){
		switch(opcion){
			case 'InsU':{
			if(!winInsU)
			{
					winInsU = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						resizable: false,
						title:'Adicionar usuario'/*perfil.etiquetas.lbTitVentanaTitI*/,width:500,height:370,
						buttons:[
						{
							icon:perfil.dirImg+'cancelar.png',
							iconCls:'btn',
							text:'Cancelar'/*perfil.etiquetas.lbBtnCancelar*/,
							handler:function(){winInsU.hide();}
						},
						{	
							icon:perfil.dirImg+'aplicar.png',
							iconCls:'btn',
							text:'Aplicar'/*perfil.etiquetas.lbBtnAplicar*/,
							handler:function(){adicionarU('apl');}
						},
						{	
							icon:perfil.dirImg+'aceptar.png',
							iconCls:'btn',
							text:'Aceptar'/*perfil.etiquetas.lbBtnAceptar*/,
							handler:function(){adicionarU();}
						}]
					});
			}
			regOracleU.getForm().reset(); 
			winInsU.add(regOracleU);
			winInsU.doLayout();
			winInsU.show();
			Ext.getCmp('bd').setValue(true);
			}break;
			case 'ModU':{
				if(!winModU)
				{
					winModU= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						resizable: false,
						title:'Modificar usuario'/*perfil.etiquetas.lbTitVentanaTitII*/,width:500,height:370,
						buttons:[
						{
							icon:perfil.dirImg+'cancelar.png',
							iconCls:'btn',
							text:'Cancelar'/*perfil.etiquetas.lbBtnCancelar*/,
							handler:function(){winModU.hide();}
						},
						{	
							icon:perfil.dirImg+'aceptar.png',
							iconCls:'btn',
							text:'Aceptar'/*perfil.etiquetas.lbBtnAceptar*/,
							handler:function(){modificarU();}
						}]
					});
				}
				regOracleU.getForm().reset();
                winModU.add(regOracleU);												
				winModU.doLayout();	
				winModU.show();
				regOracleU.getForm().loadRecord(smusers.getSelected());	
				if(smusers.getSelected().data.radiobutton1 == 'on')
					Ext.getCmp('bd').setValue(true);
				if(smusers.getSelected().data.radiobutton2 == 'on')
					Ext.getCmp('so').setValue(true);
				if(smusers.getSelected().data.radiobutton3 == 'on')
					Ext.getCmp('empresa').setValue(true);
				Ext.getCmp('denominacion').disable();
			}break;
			case 'InsR':{
			if(!winInsR)
			{
					winInsR = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						resizable: false,
						title:'Adicionar rol'/*perfil.etiquetas.lbTitVentanaTitI*/,width:500,height:340,
						buttons:[
						{
							icon:perfil.dirImg+'cancelar.png',
							iconCls:'btn',
							text:'Cancelar'/*perfil.etiquetas.lbBtnCancelar*/,
							handler:function(){winInsR.hide();}
						},
						{	
							icon:perfil.dirImg+'aplicar.png',
							iconCls:'btn',
							text:'Aplicar'/*perfil.etiquetas.lbBtnAplicar*/,
							handler:function(){adicionarR('apl');}
						},
						{	
							icon:perfil.dirImg+'aceptar.png',
							iconCls:'btn',
							text:'Aceptar'/*perfil.etiquetas.lbBtnAceptar*/,
							handler:function(){adicionarR();}
						}]
					});
			}
			regOracleR.getForm().reset(); 
			winInsR.add(regOracleR);
			winInsR.doLayout();
			winInsR.show();
			Ext.getCmp('nor').setValue(true);
			}break;
			case 'ModR':{
			if(!winModR)
			{
				winModR= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
					resizable: false,
					title:'Modificar rol'/*perfil.etiquetas.lbTitVentanaTitII*/,width:500,height:370,
					buttons:[
					{
						icon:perfil.dirImg+'cancelar.png',
						iconCls:'btn',
						text:'Cancelar'/*perfil.etiquetas.lbBtnCancelar*/,
						handler:function(){winModR.hide();}
					},
					{	
						icon:perfil.dirImg+'aceptar.png',
						iconCls:'btn',
						text:'Aceptar'/*perfil.etiquetas.lbBtnAceptar*/,
						handler:function(){modificarR();}
					}]
				});
			}
			regOracleR.getForm().reset();
            winModR.add(regOracleR);												
			winModR.doLayout();
			winModR.show();
			regOracleR.getForm().loadRecord(smroles.getSelected());	
			if(smroles.getSelected().data.radiobutton4 == 'on')
				Ext.getCmp('nor').setValue(true);
			if(smroles.getSelected().data.radiobutton5 == 'on')
				Ext.getCmp('bdr').setValue(true);
			if(smroles.getSelected().data.radiobutton6 == 'on')
				Ext.getCmp('sor').setValue(true);
			if(smroles.getSelected().data.radiobutton7 == 'on')
				Ext.getCmp('empresar').setValue(true);
			Ext.getCmp('denominacionR').disable();
		}break;
		case 'APU':{
			if(!winInsAPU)
			{
					winInsAPU = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						resizable: false,
						title:'Asignar privilegios'/*perfil.etiquetas.lbTitVentanaTitI*/,width:500,height:360,
						buttons:[
						{
							icon:perfil.dirImg+'cancelar.png',
							iconCls:'btn',
							text:'Cancelar'/*perfil.etiquetas.lbBtnCancelar*/,
							handler:function(){winInsAPU.hide();
							stgpAsignarPU.removeAll();}
						},
						{	
							icon:perfil.dirImg+'aceptar.png',
							iconCls:'btn',
							text:'Aceptar'/*perfil.etiquetas.lbBtnAceptar*/,
							handler:function(){GuardarPUGrid();}
						}]
					});
			}
			winInsAPU.on('hide',function(){smusers.clearSelections();})
			winInsAPU.add(egpAsignarPU);
			winInsAPU.doLayout();
			winInsAPU.show();
                        stgpAsignarPU.load({params:{start:0,
                                        limit:10}});

			}break;
			case 'APR':{
			if(!winInsPR)
			{
					winInsPR = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						resizable: false,
						title:'Asignar roles'/*perfil.etiquetas.lbTitVentanaTitI*/,width:500,height:360,
						buttons:[
						{
							icon:perfil.dirImg+'cancelar.png',
							iconCls:'btn',
							text:'Cancelar'/*perfil.etiquetas.lbBtnCancelar*/,
							handler:function(){winInsPR.hide();
							stgpAsignarRU.removeAll();}
						},
						{	
							icon:perfil.dirImg+'aceptar.png',
							iconCls:'btn',
							text:'Aceptar'/*perfil.etiquetas.lbBtnAceptar*/,
							handler:function(){asignarPrivilegiosRol();}
						}]
					});
			}
			winInsPR.add(egpAsignarPR);
			winInsPR.on('hide',function(){smroles.clearSelections();})
			stgpAsignarPR.load({params:{limit:10,start:0}});
			winInsPR.doLayout();
			winInsPR.show();
			}break;
			case 'ARR':{
			if(!winInsRR)
			{
					winInsRR = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						resizable: false,
						title:'Asignar roles'/*perfil.etiquetas.lbTitVentanaTitI*/,width:500,height:360,
						buttons:[
						{
							icon:perfil.dirImg+'cancelar.png',
							iconCls:'btn',
							text:'Cancelar'/*perfil.etiquetas.lbBtnCancelar*/,
							handler:function(){winInsRR.hide();
							stgpAsignarRU.removeAll();}
						},
						{	
							icon:perfil.dirImg+'aceptar.png',
							iconCls:'btn',
							text:'Aceptar'/*perfil.etiquetas.lbBtnAceptar*/,
							handler:function(){asignarRolesR();}
						}]
					});
			}
			winInsRR.add(egpAsignarRR);
			winInsRR.on('hide',function(){smroles.clearSelections();})
			stgpAsignarRR.load({params:{limit:10,start:0}});
			winInsRR.doLayout();
			winInsRR.show();
			}break;
			case 'ARU':{
			if(!winInsRU)
			{
					winInsRU = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						resizable: false,
						title:'Asignar roles'/*perfil.etiquetas.lbTitVentanaTitI*/,width:500,height:360,
						buttons:[
						{
							icon:perfil.dirImg+'cancelar.png',
							iconCls:'btn',
							text:'Cancelar'/*perfil.etiquetas.lbBtnCancelar*/,
							handler:function(){winInsRU.hide();
							stgpAsignarRU.removeAll();}
						},
						{	
							icon:perfil.dirImg+'aceptar.png',
							iconCls:'btn',
							text:'Aceptar'/*perfil.etiquetas.lbBtnAceptar*/,
							handler:function(){asignarRolesU();}
						}]
					});
			}
			winInsRU.add(egpAsignarRU);
			winInsRU.on('hide',function(){smusers.clearSelections()})
			stgpAsignarRU.load({params:{limit:10,start:0}});
			winInsRU.doLayout();
			winInsRU.show();
			}break;
			
	}}
	////************ Area de eventos ************////
	////------------ Eventos de usuarios ------------////
	Ext.getCmp('bd').on('check',function(){
		Ext.getCmp('empresa').setValue(false);
		Ext.getCmp('so').setValue(false);
		Ext.getCmp('externo').disable();
		Ext.getCmp('newpass').enable();
		Ext.getCmp('confirmnewpass').enable();
		radiobuton1 = 'on';
		radiobuton2 = 'off';
		radiobuton3 = 'off';
	})
	Ext.getCmp('so').on('check',function(){
		Ext.getCmp('bd').setValue(false);
		Ext.getCmp('empresa').setValue(false);
		Ext.getCmp('externo').disable();
		Ext.getCmp('newpass').disable();
		Ext.getCmp('confirmnewpass').disable();
		radiobuton1 = 'off';
		radiobuton2 = 'on';
		radiobuton3 = 'off';
	})
	Ext.getCmp('empresa').on('check',function(){
		Ext.getCmp('bd').setValue(false);
		Ext.getCmp('so').setValue(false);
		Ext.getCmp('externo').enable();
		Ext.getCmp('newpass').disable();
		Ext.getCmp('confirmnewpass').disable();
		radiobuton1 = 'off';
		radiobuton2 = 'off';
		radiobuton3 = 'on';
	})
	////------------ Eventos de roles ------------////
	Ext.getCmp('bdr').on('check',function(){
		Ext.getCmp('empresar').setValue(false);
		Ext.getCmp('sor').setValue(false);
		Ext.getCmp('nor').setValue(false);
		Ext.getCmp('newpassr').enable();
		Ext.getCmp('confirmnewpassr').enable();
		radiobuton4 = 'on';
		radiobuton5 = 'off';
		radiobuton6 = 'off';
		radiobuton7 = 'off';
	})
	Ext.getCmp('sor').on('check',function(){
		Ext.getCmp('bdr').setValue(false);
		Ext.getCmp('empresar').setValue(false);
		Ext.getCmp('nor').setValue(false);
		Ext.getCmp('confirmnewpassr').reset();
		Ext.getCmp('newpassr').reset();
		Ext.getCmp('newpassr').disable();
		Ext.getCmp('confirmnewpassr').disable();
		radiobuton4 = 'off';
		radiobuton5 = 'on';
		radiobuton6 = 'off';
		radiobuton7 = 'off';
	})
	Ext.getCmp('empresar').on('check',function(){
		Ext.getCmp('bdr').setValue(false);
		Ext.getCmp('sor').setValue(false);
		Ext.getCmp('nor').setValue(false);
		Ext.getCmp('confirmnewpassr').reset();
		Ext.getCmp('newpassr').reset();
		Ext.getCmp('newpassr').disable();
		Ext.getCmp('confirmnewpassr').disable();
		radiobuton4 = 'off';
		radiobuton5 = 'off';
		radiobuton6 = 'on';
		radiobuton7 = 'off';
	})
	Ext.getCmp('nor').on('check',function(){
		Ext.getCmp('bdr').setValue(false);
		Ext.getCmp('sor').setValue(false);
		Ext.getCmp('empresar').setValue(false);
		Ext.getCmp('confirmnewpassr').reset();
		Ext.getCmp('newpassr').reset();
		Ext.getCmp('newpassr').disable();
		Ext.getCmp('confirmnewpassr').disable();
		radiobuton4 = 'off';
		radiobuton5 = 'off';
		radiobuton6 = 'off';
		radiobuton7 = 'on';
	})

      
	////------------ Eventos del contextmenu ------------////                            
	egpAsignarPU.on('cellcontextmenu', function( _this, rowIndex, cellIndex, e){
		col = cellIndex;
		e.stopEvent();
	    menu.showAt(e.getXY());
	},this);
	////------------ Menu para dar los permisos ------------////
	var menu = new Ext.menu.Menu({
		id:'submenu',
		items:[{
				text:'Marcar toda la columna',
				scope: this,
				icon: "../../../../images/icons/añadir.png",
				handler:function(){
					if(col == 1)
						stgpAsignarPU.getAt(col).set(checkColumnAO,true);						
			   }},
			  {
				text:'Desmarcar toda la columna',
				scope: this,
				icon: "../../../../images/icons/eliminar.png",
				handler:function(){
					for(var i = 0; i < stgpAsignarPU.getCount(); i++){
					stgpAsignarPU.getAt(i).set(newcm.getColumnHeader(col),false);
				}
			  }}]
	});
	////------------ Renderiar el panel ------------////
    var panelConexiones = new Ext.Panel({
            id:'oracle',	
            title:'Gestor de roles para oracle.'/*perfil.etiquetas.lbTitRender*/,
            items:[tpPrincipal]
        });
    
    panelAdicionar.add(panelConexiones);
    panelAdicionar.doLayout();
        
	function handleActivate(tab){	
       //tab.doLayout();
    };
    stgpU.load({params:{start:0, limit:15, user:usuario, 
                         		puerto:puerto,
                         		ip:ipservidor, 
                         		gestor:gestorBD, 
                         		passw:password, 
                         		bd:baseDato}});
    stgpRoles.load({params:{start:0, limit:15, user:usuario, 
                     		puerto:puerto,
                     		ip:ipservidor, 
                     		gestor:gestorBD, 
                     		passw:password, 
                     		bd:baseDato}});  
	////------------ Adicionar usuario ------------////
	function adicionarU(apl){
		if (regOracleU.getForm().isValid())
		{
			if (Ext.getCmp('newpass').getValue() == Ext.getCmp('confirmnewpass').getValue()) {
			regOracleU.getForm().submit({
				url:'oracleInsertarUsuario',
				waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
				 params:{
                         		user:usuario, 
                         		puerto:puerto,
                         		ip:ipservidor, 
                         		gestor:gestorBD, 
                         		passw:password, 
                         		bd:baseDato,
                         		idservidor: idservidor,
                         		idgestor: idgestor,
                         		radiobuton1:radiobuton1,
                         		radiobuton2:radiobuton2,
                         		radiobuton3:radiobuton3
                 		},
				failure: function(form, action){
					if(action.result.codMsg != 3)
					{
						mostrarMensaje(action.result.codMsg,action.result.mensaje); 
						regOracleU.getForm().reset(); 
						stgpU.reload();
						if(!apl) 
						winInsU.hide();
						smusers.clearSelections();
						btnModificarU.disable();
						btnEliminarU.disable();
					}
					if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
				}
			});
			}
			else
				mostrarMensaje(3, perfil.etiquetas.lbTitMsgContrasenaIncorrecta);
		}
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
	}
	////------------ Modificar usuario ------------////
	function modificarU(){
		if (regOracleU.getForm().isValid()) {
			if (Ext.getCmp('newpass').getValue() == Ext.getCmp('confirmnewpass').getValue()) {
				regOracleU.getForm().submit({
					url: 'oracleModificarUsuario',
					waitMsg: perfil.etiquetas.lbMsgModificarMsg,
					params: {
								user:usuario, 
                         		puerto:puerto,
                         		denominacion:smusers.getSelected().data.denominacion,
                         		ip:ipservidor, 
                         		gestor:gestorBD, 
                         		passw:password, 
                         		bd:baseDato,
                         		idservidor: idservidor,
                         		idgestor: idgestor,
                         		radiobuton1:radiobuton1,
                         		radiobuton2:radiobuton2,
                         		radiobuton3:radiobuton3
					},
					failure: function(form, action){
						if (action.result.codMsg != 3) {
							mostrarMensaje(action.result.codMsg,action.result.mensaje);
							stgpU.reload();
							winModU.hide();
						}
						
					}
				});
			}
			else 
				mostrarMensaje(3, perfil.etiquetas.lbTitMsgContrasenaIncorrecta);
		}
		else 
			mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
	}
	////------------ Eliminar Usuario ------------////            
    function eliminarU(){
	    mostrarMensaje(2,'Est&aacute; seguro que desea eliminar el usuario?'/*perfil.etiquetas.lbMsgFunEliminar*/,elimina);
	    function elimina(btnPresionado)
	    {
	        if (btnPresionado == 'ok')
	        {
	            Ext.Ajax.request({
	                url: 'oracleEliminarUsuario',
	                method:'POST',
	                params:{
	                		user: usuario,
							ip: ipservidor,
							gestor: gestorBD,
							passw: password,
							puerto:puerto,
							bd: baseDato,
							idservidor:idservidor,
							idgestor:idgestor,
							userelim: smusers.getSelected().data.denominacion
	                		},
	                callback: function (options,success,response){
	                responseData = Ext.decode(response.responseText);                        
	                    if(responseData.codMsg == 1)
	                    {
	                        mostrarMensaje(responseData.codMsg,responseData.mensaje);
	                        stgpU.reload();
	                        smusers.clearSelections();
	                        btnEliminarU.disable();
	                    }
	                  }
	            });
	        }
	    }
    }
    ////------------ Adicionar rol ------------////
	function adicionarR(apl){
		if (regOracleR.getForm().isValid())
		{
			if (Ext.getCmp('newpassr').getValue() == Ext.getCmp('confirmnewpassr').getValue()) {
			regOracleR.getForm().submit({
				url:'oracleInsertarRol',
				waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
				 params:{
                         		user:usuario, 
                         		puerto:puerto,
                         		ip:ipservidor, 
                         		gestor:gestorBD, 
                         		passw:password, 
                         		bd:baseDato,
                         		idservidor: idservidor,
                         		idgestor: idgestor,
                         		radiobuton4:radiobuton4,
                         		radiobuton5:radiobuton5,
                         		radiobuton6:radiobuton6,
                         		radiobuton7:radiobuton7
                 		},
				failure: function(form, action){
					if(action.result.codMsg != 3)
					{
						mostrarMensaje(action.result.codMsg,action.result.mensaje); 
						regOracleR.getForm().reset(); 
						stgpRoles.reload();
						if(!apl) 
						winInsR.hide();
						smroles.clearSelections();
						btnModificarR.disable();
						btnEliminarR.disable();
					}
					if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
				}
			});
			}
			else
				mostrarMensaje(3, perfil.etiquetas.lbTitMsgContrasenaIncorrecta);
		}
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
	}
	////------------ Modificar rol ------------////
	function modificarR(){
		if (regOracleR.getForm().isValid()) {
			if (Ext.getCmp('newpassr').getValue() == Ext.getCmp('confirmnewpassr').getValue()) {
				regOracleR.getForm().submit({
					url: 'oracleModificarRol',
					waitMsg: perfil.etiquetas.lbMsgModificarMsg,
					params: {
								user:usuario, 
								rol:smroles.getSelected().data.denominacionR,
                         		puerto:puerto,
                         		ip:ipservidor, 
                         		gestor:gestorBD, 
                         		passw:password, 
                         		bd:baseDato,
                         		idservidor: idservidor,
                         		idgestor: idgestor,
                         		radiobuton4:radiobuton4,
                         		radiobuton5:radiobuton5,
                         		radiobuton6:radiobuton6,
                         		radiobuton7:radiobuton7
					},
					failure: function(form, action){
						if (action.result.codMsg != 3) {
							mostrarMensaje(action.result.codMsg,action.result.mensaje);
							stgpRoles.reload();
							winModR.hide();
						}
						
					}
				});
			}
			else 
				mostrarMensaje(3, perfil.etiquetas.lbTitMsgContrasenaIncorrecta);
		}
		else 
			mostrarMensaje(3, perfil.etiquetas.lbMsgErrorEnCamops);
	}
	////------------ Eliminar rol ------------////            
    function eliminarR(){
	    mostrarMensaje(2,'Est&aacute; seguro que desea eliminar el rol?'/*perfil.etiquetas.lbMsgFunEliminar*/,elimina);
	    function elimina(btnPresionado)
	    {
	        if (btnPresionado == 'ok')
	        {
	            Ext.Ajax.request({
	                url: 'oracleEliminarRol',
	                method:'POST',
	                params:{
	                		user: usuario,
							ip: ipservidor,
							gestor: gestorBD,
							passw: password,
							puerto:puerto,
							bd: baseDato,
							rol: smroles.getSelected().data.denominacionR
	                		},
	                callback: function (options,success,response){
	                responseData = Ext.decode(response.responseText);                        
	                    if(responseData.codMsg == 1)
	                    {
	                        mostrarMensaje(responseData.codMsg,responseData.mensaje);
	                        stgpRoles.reload();
	                        smroles.clearSelections();
	                        btnEliminarR.disable();
	                    }
	                  }
	            });
	        }
	    }
    }

		function GuardarPUGrid(){
		var filasModifcadas=stgpAsignarPU.pruneModifiedRecords = true;
    	filasModifcadas = stgpAsignarPU.getModifiedRecords();
		var cantFilas = filasModifcadas.length;
		var arrayAcceso = [];
		
		if(cantFilas)
		{
			for(i=0;i< cantFilas;i++){	
				
				arrayAcceso.push([filasModifcadas[i].data.privilegio,filasModifcadas[i].data.granted,filasModifcadas[i].data.adminopcion]);
				
			}
		}
		
		//console.log(arrayDenegado);
		AsignarPU(arrayAcceso);
    }
    function AsignarPU(arrayAcceso){
	    	Ext.Ajax.request({
	                url: 'oracleDarPrivilegios',
	                method:'POST',
	                params:{
	                		user: usuario,
							ip: ipservidor,
							gestor: gestorBD,
							passw: password,
							puerto:puerto,
							bd: baseDato,
							usuario: smusers.getSelected().data.denominacion,
							listadoPrivilegiosAcceso:Ext.encode(arrayAcceso)
							
	                		}
	               /*callback: function (options,success,response){
	                responseData = Ext.decode(response.responseText);                        
	                    if(responseData.codMsg == 1)
	                    {
	                       mostrarMensaje(responseData.codMsg, responseData.mensaje);
							winInsAPU.hide();
							smAsignarPU.clearSelections();
	                        btnEliminarU.disable();
	                    }
						if (responseData.codMsg == 3) 
							mostrarMensaje(responseData.codMsg, responseData.mensaje);
	                  }*/
	            });
		}
		
		
		function asignarPrivilegiosRol(){
		var filasModifcadas=stgpAsignarPR.pruneModifiedRecords = true;
    	//var filasModifcadas = stgpAsignarPR.getModifiedRecords();
		 filasModifcadas = stgpAsignarPR.getModifiedRecords();

		var cantFilas = filasModifcadas.length;
		var arrayAcceso = [];
		
    	
		if(cantFilas)
		{
			for(i=0;i< cantFilas;i++){	
				
				arrayAcceso.push([filasModifcadas[i].data.privilegio,filasModifcadas[i].data.granted,filasModifcadas[i].data.adminopcion]);
				
			}
		}
		
		//console.log(arrayDenegado);
		AsignarPR(arrayAcceso);
    }
    function AsignarPR(arrayAccesoR){
	    	Ext.Ajax.request({
	                url: 'oracleDarPrivilegios',
	                method:'POST',
	                params:{
	                		user: usuario,
							ip: ipservidor,
							gestor: gestorBD,
							passw: password,
							puerto:puerto,
							bd: baseDato,
							usuario: smroles.getSelected().data.denominacionR,
							listadoPrivilegiosAcceso:Ext.encode(arrayAccesoR)
							
	                		}
	                /*callback: function (options,success,response){
	                responseData = Ext.decode(response.responseText);                        
	                    if(responseData.codMsg == 1)
	                    {
	                       mostrarMensaje(responseData.codMsg, responseData.mensaje);
							winInsPR.hide();
							smAsignarPR.clearSelections();
	                        btnEliminarR.disable();
	                    }
						if (responseData.codMsg == 3) 
							mostrarMensaje(responseData.codMsg, responseData.mensaje);
	                  }*/
	            });
		}
		
		function asignarRolesR(){
		var filasModifcadas=stgpAsignarRR.pruneModifiedRecords = true;
    	filasModifcadas = stgpAsignarRR.getModifiedRecords();
		var cantFilas = filasModifcadas.length;
		var arrayAcceso = [];
		
		
		if(cantFilas)
		{
			for(i=0;i< cantFilas;i++){	
				
				arrayAcceso.push([filasModifcadas[i].data.rol,filasModifcadas[i].data.granted,filasModifcadas[i].data.adminopcion,filasModifcadas[i].data.pordefecto]);
				
			}
		}
		
    	
		AsignarRolesaR(arrayAcceso);
		
    }
    function AsignarRolesaR(arrayAccesoR){
	    	Ext.Ajax.request({
	                url: 'oracleDarRolaRol',
	                method:'POST',
	                params:{
	                		user: usuario,
							ip: ipservidor,
							gestor: gestorBD,
							passw: password,
							puerto:puerto,
							bd: baseDato,
							usuario: smroles.getSelected().data.denominacionR,
							listadoPrivilegiosAcceso:Ext.encode(arrayAccesoR)
							
	                		}
	                /*callback: function (options,success,response){
	                responseData = Ext.decode(response.responseText);                        
	                    if(responseData.codMsg == 1)
	                    {
	                       mostrarMensaje(responseData.codMsg, responseData.mensaje);
							winInsRR.hide();
							smAsignarRR.clearSelections();
	                        btnEliminarR.disable();
	                    }
						if (responseData.codMsg == 3) 
							mostrarMensaje(responseData.codMsg, responseData.mensaje);
	                  }*/
	            });
		}
		
		//-------------------------------------------------Asignar roles a usuarios------------------------------------------------------------------------------------
		
		function asignarRolesU(){
		var filasModifcadas=stgpAsignarRU.pruneModifiedRecords = true;
    	filasModifcadas = stgpAsignarRU.getModifiedRecords();
		var cantFilas = filasModifcadas.length;
		var arrayAcceso = [];
		var defecto = [];
		var defectoFalse = [];
		var aux=0;
		var auxx=0;
    	if(cantFilas)
		{
			for(i=0;i< cantFilas;i++){	
				if(filasModifcadas[i].data.pordefecto == true && filasModifcadas[i].data.granted == true)
				{
				 defecto[aux] = filasModifcadas[i].data.rol;
				 aux ++;
				}
				if(filasModifcadas[i].data.pordefecto == false && filasModifcadas[i].data.granted == true)
				{
				 defectoFalse[auxx] = filasModifcadas[i].data.rol;
				 auxx ++;
				}
				arrayAcceso.push([filasModifcadas[i].data.rol,filasModifcadas[i].data.granted,filasModifcadas[i].data.adminopcion,filasModifcadas[i].data.pordefecto]);
				
			}
		}
		AsignarRolesaU(arrayAcceso,defecto,defectoFalse);
    }
    function AsignarRolesaU(arrayAccesoRU,arreglo,arregloFalse){
	    	Ext.Ajax.request({
	                url: 'oracleAsignarRolesAUsuarios',
	                method:'POST',
	                params:{
	                		user: usuario,
							ip: ipservidor,
							gestor: gestorBD,
							passw: password,
							puerto:puerto,
							bd: baseDato,
							usuario: smusers.getSelected().data.denominacion,
							listadoPrivilegiosAcceso:Ext.encode(arrayAccesoRU),
							listadoDefecto:Ext.encode(arreglo),
							listadoDefectoFalse:Ext.encode(arregloFalse)
	                		}
	                /*callback: function (options,success,response){
						responseData = Ext.decode(response.responseText);    
	                    if(responseData.codMsg == 1)
	                    {
	                       mostrarMensaje(responseData.codMsg, responseData.mensaje);
							smAsignarRU.clearSelections();
	                        btnEliminarR.disable();
	                    }
						else 
							mostrarMensaje(responseData.codMsg, responseData.mensaje);
					}*/
	            });
		}