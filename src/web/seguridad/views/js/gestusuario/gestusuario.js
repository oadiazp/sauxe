
    		var perfil = window.parent.UCID.portal.perfil;		
		UCID.portal.cargarEtiquetas('gestusuario', function(){cargarInterfaz();});
		
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
		
		////------------ Declarar variables ------------////
		var winIns, winMod,winRegular,winRol,winFicha,winPass,fpPass,btnRol,panelRolEntidad,arbolEstructura, arrayPadresEliminar = [], arregloDeschequeados = [], arrayTiene = [];
		var idsist,iduser,idi,idu,idr,storeGridRol,aBandera = false;
		var idsistema,idusuario,nodo,gridRol,sm, smrol, TFContrasenna, TFConfContrasenna;
		var esDirIp, usuario,regUsuario,modificar = 0, idrol = 0;
		var auxBuscar = true;
		var auxDelete = true;
		var auxDelete2 = true;
		var auxDelete3 = true;
		var auxBus2 = true;
		var auxIns = false;
		var auxMod = false;
		var auxDel = false;
		var auxAsg = false;
		var auxCmc = false;
		var auxMod1 = false;
		var auxDel1 = false;
		var auxAsg1 = false;
		var auxCmc1 = false;
		var auxIns2 = true;
		var auxMod2 = true;
		var auxDel2 = true;
		var auxAsg2 = true;
		var auxCmc2 = true;
		
		////////------------ Area de validaciones ------------////
		var usuario, esDirIp, modificar = false,panelEstructuras, area, entidad,cargo,idarea, identidad,idcargo,winEstructuras,letrasnumeros,arbolEntidadRol, cantrecords;
		letrasnumeros = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/; 		
		usuario = /(^([a-zA-ZáéíóúñüÑ])+([a-zA-ZáéíóúñüÑ]*))$/;
		esDirIp = /(^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9]))\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$/ ;
	
     	function cargarInterfaz(){	
		////------------ Botones ------------////
		btnAdicionar = new Ext.Button({id:'btnAgrUser', hidden:true,  icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');} });
		btnModificar = new Ext.Button({disabled:true,id:'btnModUser', hidden:true,  icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnModificar, handler:function(){winForm('Mod');} });
		btnEliminar = new Ext.Button({disabled:true,id:'btnEliUser', hidden:true,  icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar,handler:function(){eliminarusuario();} });
        	btnRol = new Ext.Button({id:'btnAgrUserRol', hidden:true, disabled:true,icon:perfil.dirImg+'buscarclitepersona.png', iconCls:'btn', text:perfil.etiquetas.lbBtnRol, handler:function(){winForm('Rol');} }); 
		btnAyuda = new Ext.Button({id:'btnAyuUser', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });
		btnContrasena  = new Ext.Button({id:'btnContUser', hidden:true, disabled:true,icon:perfil.dirImg+'buscarclitepersona.png', iconCls:'btn', text:perfil.etiquetas.lbBtnContrasena, handler:function(){winForm('Pass');} });
		btnActivar  = new Ext.Button({id:'btnActivarUser', hidden:true, disabled:true,icon:perfil.dirImg+'buscarclitepersona.png', iconCls:'btn', text:perfil.etiquetas.lbBtnactivarusuario, handler:function(){activarUsuarios();} });
		btnDesactivar  = new Ext.Button({id:'btnDesctivarUser', hidden:true, disabled:true,icon:perfil.dirImg+'buscarclitepersona.png', iconCls:'btn', text:perfil.etiquetas.lbBtndesactivarusuario, handler:function(){desactivarUsuarios();} });
		btnFichaUsuario  = new Ext.Button({id:'btnFichaUsuario', hidden:true, disabled:true,icon:perfil.dirImg+'buscarclitepersona.png', iconCls:'btn', text:perfil.etiquetas.lbBtnfichausuario, handler:function(){winForm('Ficha');} });
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
		// //------------ TextField - Contraseña------------////
		TFContrasenna = new Ext.form.TextField({
			fieldLabel:perfil.etiquetas.lbTitMsgContrasena,
			inputType:'password',
			id : 'contrasenna',
                        maxLength:50,
			allowBlank : false,
			blankText : perfil.etiquetas.lbMsgEstecampoesrequerido,
			name : 'contrasenna',
			tabIndex:11, 
			anchor : '100%'
		});
		// //------------ TextField - Confirmar Contraseña------------////
		TFConfContrasenna = new Ext.form.TextField({
			fieldLabel:perfil.etiquetas.lbTitMsgConfirmarcontrasena,
			id : 'contrasena',
			inputType:'password',
                        maxLength:50,
			allowBlank : false,
			blankText : perfil.etiquetas.lbMsgEstecampoesrequerido,
			name : 'contrasena',
			tabIndex:12, 
			anchor : '100%'
		});
		////------------ Store del combobox de Dominio ------------////	
		var storeDominio =  new Ext.data.Store({
			url: 'cargarcombodominio',
			autoLoad:true,
			reader:new Ext.data.JsonReader({
			id:'iddominio',
            root:'datos'
			},
			[
				{name: 'iddominio',mapping:'iddominio'},
				{name:'dominio',mapping:'denominacion'}
			])
		});
		
		var storeDominioBuscar =  new Ext.data.Store({
			url: 'cargarComboDominioBuscar',
			autoLoad:true,
			reader:new Ext.data.JsonReader({
			id:'iddominio',
            root:'datos'
			},
			[
				{name: 'iddominio',mapping:'iddominio'},
				{name:'dominio',mapping:'denominacion'}
			])
		});
		
		var storeDataTmp = new Ext.data.SimpleStore({
		        data : [
		        		['seleccione', 'Seleccione'],
        				['activar', 'Activar'],
        				['desactivar', 'Desactivar'] 
		    		  ],
		        fields:['value','text'] 
		    });
		////------------ Store del Grid de usuarios ------------////
		var storeGrid =  new Ext.data.Store({
				url: 'cargarusuario',
				listeners:{
					load:function(st, object){
						if(st.getCount()!=0) cantrecords = st.getCount();
					}
				},
				reader:new Ext.data.JsonReader({
				totalProperty: "cantidad_filas",
				root: "datos",
				id: "idusuario"
			},
			            [
			            	{name:'nombreusuario',mapping:'nombreusuario'},
			            	{name:'activo',mapping:'activo'},
			             	{name:'iddominio',mapping:'iddominio'},
			             	{name:'idtema',mapping:'idtema'},
			             	{name:'ididioma',mapping:'ididioma'},
			             	{name:'iddesktop',mapping:'iddesktop'},
							{name:'dominio',mapping:'dominio'},
			             	{name:'tema',mapping:'tema'},
			             	{name:'idioma',mapping:'idioma'},
			             	{name:'desktop',mapping:'desktop'},
						 	{name:'servidor',mapping:'deominacion'},
                            {name:'idservidor',mapping:'idservidor'},  
						 	{name:'idusuario',mapping:'idusuario'},
						 	{name:'ip',mapping:'ip'},
                            {name:'identidad',mapping:'identidad'},
                            {name:'idcargo',mapping:'idcargo'},
                            {name:'idarea',mapping:'idarea'},
                            {name:'entidad',mapping:'entidad'},
                            {name:'cargo',mapping:'cargo'},
                            {name:'area',mapping:'area'}
						])	
		});
		
		////------------ Modo de seleccion del grid de usuarios ------------////
		sm = new Ext.grid.RowSelectionModel({singleSelect:false});
		sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
				
								btnEliminar.enable();
								btnAdicionar.enable();
								btnActivar.enable();
								btnDesactivar.enable();
								btnFichaUsuario.enable();
                                btnRol.enable();
				
		}, this);
        
		sm.on('rowselect', function (smodel, rowIndex, keepExisting, record){
			if(sm.getCount()!=1){
				btnModificar.disable();
				btnContrasena.disable();
				btnFichaUsuario.disable();
			}else{
				btnModificar.enable();
				btnContrasena.enable();
				btnFichaUsuario.enable();
			}
		}, this); 
        
		sm.on('rowdeselect', function (smodel, rowIndex, keepExisting, record){
			if(sm.getCount()!=1){
				btnModificar.disable();
				btnContrasena.disable();
				btnFichaUsuario.disable();
			}else{
				btnModificar.enable();
				btnContrasena.enable();
				btnFichaUsuario.enable();
			}
		}, this);				
		////------------ Creando el Grid de usuarios ------------////
		var grid = new Ext.grid.GridPanel({  
			region:'center',
			frame:true,
			iconCls:'icon-grid',    
			autoExpandColumn:'expandir',
			margins:'2 2 2 -4',
			store:storeGrid,
			sm:sm,
			columns: [
			{header: perfil.etiquetas.lbUsuario, width:150, dataIndex: 'nombreusuario', id:'expandir'},
			{header: perfil.etiquetas.lbDominio, width:100, dataIndex: 'dominio'},
			{header: perfil.etiquetas.lbTema, width:100, dataIndex: 'tema'},
			{header: perfil.etiquetas.lbIdioma, width:100, dataIndex: 'idioma'},
			{header: perfil.etiquetas.lbEscritorio, width:100, dataIndex: 'desktop'},
			{header: perfil.etiquetas.lbEntidad, width:100, dataIndex: 'entidad'},
			{header: perfil.etiquetas.lbCargo, width:100, dataIndex: 'cargo'},
                        {header: perfil.etiquetas.lbArea, width:100, dataIndex: 'area'},
                        {hidden: true, hideable: false, dataIndex: 'ip'}, 
                        {hidden: true, hideable: false, dataIndex: 'activo'},
                        {hidden: true, hideable: false, dataIndex: 'identidad'},
                        {hidden: true, hideable: false, dataIndex: 'idarea'}, 
                        {hidden: true, hideable: false, dataIndex: 'idcargo'},
			{hidden: true, hideable: false, dataIndex: 'idusuario'},
			{hidden: true, hideable: false, dataIndex: 'iddominio'},
			{hidden: true, hideable: false, dataIndex: 'idtema'},
			{hidden: true, hideable: false, dataIndex: 'ididioma'},
                        {hidden: true, hideable: false, dataIndex: 'servidor'},
                        {hidden: true, hideable: false, dataIndex: 'idservidor'},
			{hidden: true, hideable: false, dataIndex: 'iddesktop'}
			],
			
			loadMask:{store:storeGrid},            
            tbar:[
                    new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbTitDenBuscar}),
                    nombreusuario = new Ext.form.TextField({width:80, id: 'usuario'}),
		    new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbTitDenDominioBuscar}),
		    dominiobuscar = new Ext.form.ComboBox({
                                            emptyText:perfil.etiquetas.lbSeleccionardominio,
                                            editable:false,
                                            fieldLabel:perfil.etiquetas.lbDominio,
                                            store:storeDominioBuscar,
                                            valueField:'iddominio',
					    id:'dominiobuscar',
                                            displayField:'dominio',
                                            hiddenName:'iddominio',
                                            forceSelection:true,
                                            typeAhead: true,
                                            mode: 'local',
                                            triggerAction: 'all',                        
                                            //selectOnFocus:true,
                                            anchor:'100%'
                                            }),
                    new Ext.menu.Separator(), 
                    new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbTitDenActiv}),
		    		activar = new Ext.form.ComboBox({
                                            emptyText:perfil.etiquetas.lbSeleccionarestado,
                                            editable:false,
                                            fieldLabel:perfil.etiquetas.lbDominio,
                                             store:storeDataTmp,
                                            valueField:'text',
                                            displayField:'text',
                                            hiddenName:'activo',
                                            forceSelection:true,
                                            typeAhead: true,
                                            mode: 'local',
                                            triggerAction: 'all',                        
                                            selectOnFocus:true,
                                            anchor:'100%'
                                             }),
                    new Ext.menu.Separator(),
                    new Ext.Button({icon:perfil.dirImg+'buscar.png',iconCls:'btn',text:perfil.etiquetas.lbBtnBuscar, handler:function(){buscarnombreusuario(nombreusuario.getValue(),dominiobuscar.getValue(), activar.getValue());}})
             ],             
			bbar:new Ext.PagingToolbar({
            pageSize: 15,
            id:'ptbaux',
            store: storeGrid,
            displayInfo: true,
            displayMsg: perfil.etiquetas.lbTitMsgNingunresultadoparamostrar,
            emptyMsg: perfil.etiquetas.lbTitMsgResultados
		})
			
		});
		////------------ Trabajo con el PagingToolbar grid usuarios ------------////
		Ext.getCmp('ptbaux').on('change',function(){
			sm.selectFirstRow();
		},this)	
		
		////------------ Panel con los componentes ------------////
		var panel = new Ext.Panel({
		title:perfil.etiquetas.lbTitGestionarusuarios,
			layout:'border',
			id:'pepe',
			renderTo:'panel',
			items:[grid],
			tbar:[btnAdicionar,btnModificar,btnEliminar,btnRol,btnContrasena, btnActivar, btnDesactivar, btnFichaUsuario],
			keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    			if(auxDelete && auxDelete2 && auxDelete3 && auxDel && auxDel1 && auxDel2)
		    				eliminarusuario();
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
		    		  	key:"b",
		    			alt:true,
		    			fn: function(){
		    				if(auxBuscar && auxBus2)
		    					buscarnombreusuario(Ext.getCmp('usuario').getValue(),Ext.getCmp('dominiobuscar').getValue());
		    				else
		    					buscardenrol(Ext.getCmp('denrolbuscado').getValue());	
		    			}
		    		  },
		    		  {
		    		  	key:"o",
		    			alt:true,
		    			fn: function(){
		    				if(auxAsg && auxAsg1 && auxAsg2)	
		    					winForm('Rol');}
		    		  },
		    		  {
		    		  	key:"t",
		    			alt:true,
		    			fn: function(){
		    				if(auxCmc && auxCmc1 && auxCmc2)	
		    					winForm('Pass');}
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
		Ext.getCmp('usuario').on('focus',function(){
			auxDelete3 = false;
		},this)
		Ext.getCmp('usuario').on('blur',function(){
			auxDelete3 = true;
		},this)
		Ext.getCmp('dominiobuscar').on('focus',function(){
			auxDelete3 = false;
		},this)
		Ext.getCmp('dominiobuscar').on('blur',function(){
			auxDelete3 = true;
		},this)
		btnAdicionar.on('show',function(){
			auxIns = true;
		},this)
		btnEliminar.on('show',function(){
			auxDel = true;
		},this)
		btnModificar.on('show',function(){
			auxMod = true;
		},this)
		btnContrasena.on('show',function(){
			auxCmc = true;
		},this)
		btnRol.on('show',function(){
			auxAsg = true;
		},this)
		storeGrid.on('load',function(){
			if(storeGrid.getCount() != 0)
			{
				auxMod1 = true;
				auxDel1 = true;
				auxAsg1 = true;
				auxCmc1 = true;
			}
			else
			{
				auxMod1 = false;
				auxDel1 = false;
				auxAsg1 = false;
				auxCmc1 = false;
			}
		},this)
		
		////------------ Viewport ------------////
		var vpGestSistema = new Ext.Viewport({
			layout:'fit',
			items:panel
		})
		storeGrid.load({params:{start:0,limit:15}});
		////------------------ Store del combobox de Desktop -----------------////	
		var storeDesktop =  new Ext.data.Store({
			url: 'cargarcombodesktop',
			autoLoad:true, 
			reader:new Ext.data.JsonReader({
			id:'id'
			},
			[
				{name: 'iddesktop',mapping:'iddesktop'},
				{name:'desktop',mapping:'denominacion'}
			])
		});
        
		
        
		
		////------------ Store del combobox de Idioma ------------////	
		var storeIdioma =  new Ext.data.Store({
			url: 'cargarcomboidioma',
			autoLoad:true, 
			reader:new Ext.data.JsonReader({
			id:'ididioma'
			},
			[
				{name: 'ididioma',mapping:'ididioma'},
				{name:'idioma',mapping:'denominacion'}
			])
		});
		
		////------------ Store del combobox de Tema ------------////	
		var storeTema =  new Ext.data.Store({
			url: 'cargarcombotema',
			autoLoad:true,
			reader:new Ext.data.JsonReader({
			id:'idtema'
			},
			[
				{name: 'idtema',mapping:'idtema'},
				{name:'tema',mapping:'denominacion'}
			])
		});
     ////------------------ Store del combobox de ServidoresAut(Sistema) -----------------////    
        var storeservidor =  new Ext.data.Store({
            url: 'cargarcomboservidoresaut',
            autoLoad:true, 
            reader:new Ext.data.JsonReader({
            id:'id'
            },
            [{name:'idservidor',mapping:'idservidor'},
            {name:'servidor',mapping:'denominacion'}
            ]
                )
            });

		////------------ Formulario de adicionar usuario ------------////
		 regUsuario = new Ext.FormPanel({
				labelAlign: 'top',
                width:460,
				frame:true,
				items: [{
						layout:'column',
						items:[{
                                columnWidth:.66,
                                layout:'form',
                                items:[{
                                            xtype:'textfield',
                                            fieldLabel:perfil.etiquetas.lbIpfin,
                                            blankText:perfil.etiquetas.lbMsgEstecampoesrequerido,
                                            maxLength:255,
                                            regexText:perfil.etiquetas.lbMsgEstosdatossonincorrectoselrangoipes255, 
                                            id:'ip',
                                            tabIndex:1, 
                                            anchor:'97%'
                                        }]
                          },{
                                columnWidth:.33,
                                layout:'form',
                                items:[new Ext.form.ComboBox({
                                            emptyText:perfil.etiquetas.lbSeleccionarescritorio,
                                            editable:false,
                                            fieldLabel:perfil.etiquetas.lbTitMsgTipodeescritorio,
                                             store:storeDesktop,
                                             valueField:'iddesktop',
                                            displayField:'desktop',
                                            hiddenName:'iddesktop',
                                            forceSelection:true,
                                            typeAhead: true,
                                            mode: 'local',
                                            allowBlank:false,
                                            triggerAction: 'all',                        
                                            selectOnFocus:true,
                                            tabIndex:2, 
                                            anchor:'95%'
                                             })]
                          },{
								columnWidth:.33,
								layout:'form',
								items:[ new Ext.form.ComboBox({
                                            emptyText:perfil.etiquetas.lbSeleccionaridioma,
                                            editable:false,
                                            fieldLabel:perfil.etiquetas.lbIdioma,
                                             store:storeIdioma,
                                             valueField:'ididioma',
                                            displayField:'idioma',
                                            hiddenName:'ididioma',
                                            forceSelection:true,
                                            typeAhead: true,
                                            mode: 'local',
                                            allowBlank:false,
                                            triggerAction: 'all',                        
                                            selectOnFocus:true,
                                            tabIndex:3, 
                                            anchor:'95%'
                                             }),{
                                            xtype:'textfield',
                                            fieldLabel:perfil.etiquetas.lbEntidad,
                                            id:'entidad',
                                            readOnly:true,
                                            regexText:perfil.etiquetas.lbTitMsgSololetras, 
                                            anchor:'95%',
                                            tabIndex:6, 
                                            listeners:{'focus':function(text){                                                         
                                                      ventanaestructura();                                            
                                                    }
                                                }
                                       },
                                       new Ext.form.ComboBox({
                                                    emptyText:'Seleccionar servidor',
                                                    editable:false,
                                                    fieldLabel:'Servidores',
                                                    store:storeservidor,
                                                    valueField:'idservidor',
                                                    displayField:'servidor',
                                                    hiddenName:'idservidor',
                                                    forceSelection:true,
                                                    typeAhead: true,
                                                    mode: 'local',
                                                    triggerAction: 'all',                        
                                                    selectOnFocus:true,
                                                    tabIndex:9, 
                                                    anchor:'95%'
                                             })
                                       
                                       
                                       ]
						  },
						  {
								columnWidth:.33,
								layout: 'form',
								items: [
											new Ext.form.ComboBox({
                                            emptyText:perfil.etiquetas.lbSeleccionartema,
                                            editable:false,
                                            fieldLabel:perfil.etiquetas.lbTema,
                                            store:storeTema,
                                            valueField:'idtema',
                                            displayField:'tema',
                                            hiddenName:'idtema',
                                            forceSelection:true,
                                            typeAhead: true,
                                            mode: 'local',
                                            allowBlank:false,
                                            triggerAction: 'all',                        
                                            selectOnFocus:true,
                                            tabIndex:4, 
                                            anchor:'95%'
                                             }),{
                                            xtype:'textfield',
                                            fieldLabel:perfil.etiquetas.lbArea,
                                            id:'area',
                                            readOnly:true,
                                            regexText:perfil.etiquetas.lbTitMsgSololetras, 
                                            anchor:'95%',
                                            tabIndex:7, 
                                            listeners:{'focus':function(text){                                                         
                                                      ventanaestructura();                                            
                                                    }
                                                }
                                        },{
                                            xtype:'textfield',
                                            fieldLabel:perfil.etiquetas.lbUsuario,
                                            allowBlank: false,
                                            blankText:perfil.etiquetas.lbMsgEstecampoesrequerido,
                                            regex:letrasnumeros,
                                            id:'nombreusuario',
                                            maxLength:50,
                                            tabIndex:10, 
                                            anchor:'95%'
                                      }]
							}, {
                                columnWidth:.33,
                                layout: 'form',
                                items: [
                                            new Ext.form.ComboBox({
                                            emptyText:perfil.etiquetas.lbSeleccionardominio,
                                            editable:false,
                                            fieldLabel:perfil.etiquetas.lbDominio,
                                            store:storeDominio,
                                            valueField:'iddominio',
                                            displayField:'dominio',
                                            hiddenName:'iddominio',
                                            forceSelection:true,
                                            typeAhead: true,
                                            mode: 'local',
                                            allowBlank:false,
                                            triggerAction: 'all',                        
                                            selectOnFocus:true,
                                            anchor:'100%',
                                            tabIndex:5, 
                                            id:'iddom'
                                             }),
                                             {
                                            xtype:'textfield',
                                            fieldLabel:perfil.etiquetas.lbCargo,
                                            id:'cargo',
                                            readOnly:true,
                                            regexText:perfil.etiquetas.lbTitMsgSololetras, 
                                            anchor:'100%',
                                            tabIndex:8, 
                                            listeners:{'focus':function(text){                                                         
                                                      ventanaestructura();                                            
                                                    }
                                                }
                                       },                                            
                                        TFContrasenna,
                                        TFConfContrasenna]
                            }]
					}]
		});
    ////------------ Formulario de Datos de Sistema ------------////
        fpPass = new Ext.FormPanel({
            frame:true,
            width:100,
            bodyStyle:'padding:5px 5px 0',
            items: [{
                    layout:'column',
                    items:[{
                            columnWidth:5,
                            layout:'form',
                            items:[
                            {
                                    xtype:'textfield',
                                    fieldLabel:perfil.etiquetas.lbUsuario,                                   
                                    id:'usuariop',     
                                    labelStyle:'width:200px',
                                    width:200,
                                    readOnly:'true'
                            },
                            {
                                    xtype:'textfield',
                                    fieldLabel:perfil.etiquetas.lbTitMsgContrasenanueva,
                                    inputType:'password',
                                    blankText:perfil.etiquetas.lbMsgEstecampoesrequerido,                                
                                    id:'contrasenap',
                                    labelStyle:'width:200px',
                                    allowBlank:false,
                                    width:200
                            },{
                                    xtype:'textfield',
                                    inputType:'password',
                                    fieldLabel:perfil.etiquetas.lbTitMsgContrasenanuevaconfirmada,
                                    blankText:perfil.etiquetas.lbMsgEstecampoesrequerido,
                                    id:'contrasennap',
                                    labelStyle:'width:200px',
                                    width:200,
                                    allowBlank:false
                            }]
                    }]
            }]
        });        

    
    
        //////////------------Store para cargar el grid de roles---------------/////////
        
        storeGridRol =  new Ext.data.Store({
        url: 'cargarroles',
        reader:new Ext.data.JsonReader({
        totalProperty: "cantidad_filas",
        root: "datos",
        id: "idrol"
        },
            [
            {name:'idrol',mapping:'idrol'},
            {name:'denominacion',mapping:'denominacion'},
            {name:'descripcion',mapping:'descripcion'},
            {name:'estado',mapping:'estado'}
            ])    
        });
         ////------------ Modo de seleccion del grid de roles ------------////
		var smrol = new Ext.grid.RowSelectionModel({singleSelect:true});
		    smrol.on('rowselect', function (smodel, rowIndex, record){
			idrol = record.data.idrol;
			arbolEntidades.enable();
			arbolEntidades.getRootNode().reload();
		}, this);
		smrol.on('rowdeselect', function (SelectionModel, rowIndex, record) {
			asignarRol(null, record);
		}, this);
		
        ////------------ Creando el Grid de roles ------------////
        gridRol = new Ext.grid.GridPanel({  
            region:'west',
            frame:true,
            width:400,
            iconCls:'icon-grid',    
            autoExpandColumn:'expandir',
            margins:'2 2 2 -4',
            store:storeGridRol,
            sm:smrol,
            columns: [
                        {header: perfil.etiquetas.lbRol, width:150, dataIndex: 'denominacion', id:'expandir'},
                        {header: perfil.etiquetas.lbDescripcion, width:250, dataIndex: 'descripcion'},
                        {hidden: true, hideable: false, dataIndex: 'idrol'},
			{hidden: true, hideable: false, dataIndex: 'estado'}
                     ],
            
            loadMask:{store:storeGridRol},
            
            tbar:[
                    new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbTitDenRolBuscar}),
                    denrolbuscado = new Ext.form.TextField({width:80, id: 'denrolbuscado'}),
                    new Ext.menu.Separator(),            
                    new Ext.Button({icon:perfil.dirImg+'buscar.png',iconCls:'btn',text:perfil.etiquetas.lbBtnBuscar, handler:function(){buscardenrol(denrolbuscado.getValue());}})
                 ],
            bbar:new Ext.PagingToolbar({
                    id:'ptbUsuario',
                    pageSize: 15,
                    store: storeGridRol,
                    displayInfo: true,
                    displayMsg: perfil.etiquetas.lbTitMsgNingunresultadoparamostrar,
                    emptyMsg: perfil.etiquetas.lbTitMsgResultados
            })
        });

	    gridRol.getView().getRowClass = function(record, index, rowParams, store) {
        	if (record.data.estado == 1)
            		return 'FilaRoja';
    	};
		gridRol.on('render',function(){
			auxBuscar = false;
		},this)
		Ext.getCmp('denrolbuscado').on('focus',function(){
			auxDelete = false;
		},this)
		
        /////------------------------------arbol de entidades que estan en mi dominio-------------------------------/////////////
        
        arbolEntidades = new Ext.tree.TreePanel({
        autoScroll:true,
        region:'center',
        split:true,
        disabled:true,           
        width:'37%',
        loader: new Ext.tree.TreeLoader({
            dataUrl:'cargarentidades',
            listeners:{'beforeload':function(atreeloader, anode){ 
                            atreeloader.baseParams = {}; 
                            atreeloader.baseParams.idusuario = sm.getSelected().data.idusuario;
                            atreeloader.baseParams.iddominio = sm.getSelected().data.iddominio;
                            atreeloader.baseParams.idrol = idrol;
                            if(anode.attributes.idestructuraop)
                                atreeloader.baseParams.idarea = anode.attributes.idestructuraop;  
                            if(anode.attributes.checked && anode.attributes.id != 0)
                                atreeloader.baseParams.tcheck = 'marcado';
                            else if(!anode.attributes.checked && anode.attributes.id != 0)
                                atreeloader.baseParams.tcheck = 'desmarcado'; 
                    },'load':function(atreeloader, anode)
                        {
				hijosNodo = anode.childNodes;
				if (hijosNodo.length) {
					for(i=0; i<hijosNodo.length;i++) {
						nodoHijo = hijosNodo[i];
						if (nodoHijo.attributes.checked)
							adicionarEnDeschequeados(arrayTiene, nodoHijo.attributes.id);
					}
				}
                                var id;
                                 if(anode.attributes.tipo == 'externa')
                                    id = anode.attributes.id;
                                 else
                                    id = anode.attributes.id;         
                                 
                                 var esta1 = estaEnDeschequeados(arrayPadresEliminar, id);
                                 
                                 if(esta1 != -1)
                                 {   
                                    eliminarEnDeschequeados(arrayPadresEliminar, esta1);
                                    if(arbolEntidades.getChecked("id", anode).length == 0)
                                    {
                                        hijosNodo = anode.childNodes;
                                        for(i=0; i<hijosNodo.length;i++)
                                        {
                                            if(!hijosNodo[i].isLeaf())
                                            {
                                                if(hijosNodo[i].attributes.tipo == 'externa')
                                                    id = anode.attributes.id;
                                                else
                                                    id = anode.attributes.id;
                                                adicionarEnDeschequeados(arrayPadresEliminar, id);
                                            }
                                        }
                                    }   
                                 }
                                
                        }
            }
            
                  
        })
    });
    ////------------ Crear nodo padre del arbol ------------////
    padreArbolEntidades= new Ext.tree.AsyncTreeNode({
          text: 'Entidades',
          expandable:false,
          id:'0'
          });         
    arbolEntidades.setRootNode(padreArbolEntidades);
    
     arbolEntidades.on('checkchange', function (node, e)
    {
        var esta = estaEnDeschequeados(arregloDeschequeados, node.attributes.id);
          var id;
        if(node.attributes.tipo == 'externa')
            id = node.attributes.id;
        else
            id = node.attributes.id;
         var esta1 = estaEnDeschequeados(arrayPadresEliminar, id);
           
        if(node.attributes.checked)
        {
            if(node.attributes.id != 0)
            {
                //marcarArriba(node);
            }
            marcarHijos(node, true);
            
            if(esta != -1)
                eliminarEnDeschequeados(arregloDeschequeados, esta);
            if(!node.isLeaf() && esta1 != -1)
                eliminarEnDeschequeados(arrayPadresEliminar, esta1);
        }
        else
        {
            if(node.attributes.id == 0 && !aBandera)
            {
                //marcarHijos(node, false);
            }
            else if(node.attributes.id != 0 && !aBandera)
            {
                //desmarcarArriba(node);
                //marcarHijos(node, false);
            }
            aBandera = false;
	    var estaT = estaEnDeschequeados(arrayTiene, id);
	    if (estaT != -1)
            	adicionarEnDeschequeados(arregloDeschequeados, node.attributes.id);
            if(!node.isLeaf() && node.childNodes.length == 0 && estaT != -1)
                adicionarEnDeschequeados(arrayPadresEliminar, id);
        }
    }, this);

	//Funcion para cambiar el tipo de display de un textfield
	function cambiaDisplayTf(aTf,aTipo){
		var aDivTF = aTf.getEl();
		var aLblTf = Ext.get(aDivTF.dom.parentNode.parentNode.childNodes[0]);
		if(aTipo){
			aDivTF.show();
			aLblTf.show();
			return 0;
		}
		aDivTF.hide();
		aLblTf.hide();
	}

        ////------------ Panel con los componentes de roles y entidades ------------////
        panelRolEntidad = new Ext.Panel({
            layout:'border',
            items:[gridRol,arbolEntidades]
        });
		//Cargar ventanas
		function winForm(opcion){
			switch(opcion){
				case 'Ins':{
						if(!winIns)
							{
									winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
									title:perfil.etiquetas.lbBtnAdicionarusuario,width:600,height:350,
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
										handler:function(){adicionarusuario('apl');},
										text:perfil.etiquetas.lbBtnAplicar
									},
									{
										icon:perfil.dirImg+'aceptar.png',
										iconCls:'btn',
										handler:function(){adicionarusuario();},
										text:perfil.etiquetas.lbBtnAceptar
									}]
								});
								winIns.on('show',function(){
									auxIns2 = false;
									auxMod2 = false;
									auxDel2 = false;
									auxAsg2 = false;
									auxCmc2 = false;
									auxBus2 = false;
								},this)
								winIns.on('hide',function(){
									auxIns2 = true;
									auxMod2 = true;
									auxDel2 = true;
									auxAsg2 = true;
									auxCmc2 = true;
									auxBus2 = true;
								},this)
							}
                            winIns.add(regUsuario);
                            regUsuario.getForm().reset(); 	
							winIns.doLayout();                           
                            winIns.show();
							cambiaDisplayTf(TFContrasenna,true);
							cambiaDisplayTf(TFConfContrasenna,true);
                            Ext.getCmp('ip').setRawValue('0.0.0.0/0');
                                                                                  							
						}break;
						case 'Mod':
						{
							if(!winMod)
							{
								winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
									title:perfil.etiquetas.lbBtnModificarusuario,width:600,height:350,resizable:false,
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
										handler:function(){modificarUsuario();},
										text:perfil.etiquetas.lbBtnAceptar
									}]
								});
								winMod.on('show',function(){
									auxIns2 = false;
									auxMod2 = false;
									auxDel2 = false;
									auxAsg2 = false;
									auxCmc2 = false;
									auxBus2 = false;
								},this)
								winMod.on('hide',function(){
									auxIns2 = true;
									auxMod2 = true;
									auxDel2 = true;
									auxAsg2 = true;
									auxCmc2 = true;
									auxBus2 = true;
								},this)
							}
                            modificar = 1;
                            winMod.add(regUsuario);		
							winMod.doLayout();				            
							winMod.show();
							cambiaDisplayTf(TFContrasenna);
							cambiaDisplayTf(TFConfContrasenna);
							regUsuario.getForm().loadRecord(sm.getSelected());
                            if(!Ext.getCmp('ip').getValue())
                            {
                            Ext.getCmp('ip').setRawValue('0.0.0.0/0');
                            }
			     Ext.getCmp('contrasena').setRawValue('contrasenna');
			     Ext.getCmp('contrasenna').setRawValue('contrasenna');
							
						}break;
                        case 'Rol':
                        {
				var arrUsuarios = sm.getSelections();
				if (arrUsuarios.length > 1) {
		    			var arrUsuario = [];
                        if(!validarDominiosUsuarios(arrUsuarios))
                            mostrarMensaje(3,'Operacion incorrecta. Los usuarios pertenecen a dominios diferentes.');
                        else{
		    			    for (var i=0;i<arrUsuarios.length;i++)
                                arrUsuario.push(arrUsuarios[i].data.idusuario); 
					        Ext.getBody().mask('Por favor espere. Validando operacion...');
					        Ext.Ajax.request({
						    url: 'validarasignacionroles',
						    params:{ArrayUsuarios:Ext.encode(arrUsuario)},
						    callback: function (options,success,response){
							    Ext.getBody().unmask();
							    responseData = Ext.decode(response.responseText);
							    if(responseData.codMsg == 3)
								    mostrarMensaje(responseData.codMsg,responseData.mensaje);
							    else
								    ventanaAsignarRol();
						         }
					    });
                    } 
				}
				else
					ventanaAsignarRol();                     
                            
                        }break;
                        case 'Pass':
                        {
                            if(!winPass)
                            {
                                winPass= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
                                    title:perfil.etiquetas.lbBtnCambiarpass,width:450,height:200,resizable:false,
                                    buttons:[
                                    {
                                        icon:perfil.dirImg+'cancelar.png',
                                        iconCls:'btn',
                                        text:perfil.etiquetas.lbBtnCancelar,
                                        handler:function(){winPass.hide();}
                                    },
                                    {
                                        icon:perfil.dirImg+'aceptar.png',
                                        iconCls:'btn',
                                        handler:function(){cambiarpass();},
                                        text:perfil.etiquetas.lbBtnAceptar
                                    }]
                                });
                               winPass.on('show',function(){
									auxIns2 = false;
									auxMod2 = false;
									auxDel2 = false;
									auxAsg2 = false;
									auxCmc2 = false;
									auxBus2 = false;
								},this)
								winPass.on('hide',function(){
									auxIns2 = true;
									auxMod2 = true;
									auxDel2 = true;
									auxAsg2 = true;
									auxCmc2 = true;
									auxBus2 = true;
								},this) 
                            }
                            winPass.add(fpPass);                
                            winPass.doLayout();
                            Ext.getCmp('contrasenap').reset();
                            Ext.getCmp('contrasennap').reset();
                            winPass.show();        
							Ext.getCmp('usuariop').setValue(sm.getSelected().data.nombreusuario);
                            
                        }break;
                        case 'Ficha': {
        					winFicha = new Ext.UCID.WinIframe({
        						title:'Ficha de usuario',
        						id: 'winFicha',
        						idIframe: 'idwinficha',
        						params:{},
        						url: 'fichausuario?idusuario=' + sm.getSelected().data.idusuario,
        						width: 750,
        						height:400,
        						doAction: function(){},
        						buttons: [{handler:function(){winFicha.close();},icon:perfil.dirImg+'cerrar.png',iconCls:'btn', text:'Cerrar'}]
        					});
        					winFicha.show('');
            			}break;
				}
			}
     
    function validarDominiosUsuarios(arrUsuarios)
    {
     for(i=0;i<arrUsuarios.length-1;i++)
     {
      if(arrUsuarios[i].data.iddominio != arrUsuarios[i+1].data.iddominio)
      return false;
     }
     return true;
    }
      
	function ventanaAsignarRol(){
		if(!winRol)
		{
			winRol= new Ext.Window({modal: true, 
				closeAction:'hide',
				layout:'fit', 
				title:perfil.etiquetas.lbBtnTramitarroles, 
				width:700,
				height:450, 
				resizable:false,
				buttons:[{
					icon:perfil.dirImg+'cancelar.png',
					iconCls:'btn',
					text:perfil.etiquetas.lbBtnCancelar,
					handler:function(){winRol.hide();arbolEntidades.disable();}
				},{
					icon:perfil.dirImg+'aceptar.png',
					iconCls:'btn',
                    id:'btnroles',
					handler:function(){asignarRol('apl');},
					text:perfil.etiquetas.lbBtnAceptar
				}]
			});
			winRol.on('hide',function(){
				auxBuscar = true;
				auxDelete2 = true;
				auxDelete = true;
				auxIns2 = true;
				auxMod2 = true;
				auxDel2 = true;
				auxAsg2 = true;
				auxCmc2 = true;
				auxBus2 = true;
			},this)
			winRol.on('show',function(){
				auxDelete2 = false;
				auxIns2 = false;
				auxMod2 = false;
				auxDel2 = false;
				auxAsg2 = false;
				auxCmc2 = false;
				auxBus2 = false;
			},this)
			
		}
		
		idrol = 0;
		arbolEntidades.collapseAll();
		winRol.add(panelRolEntidad);                
		winRol.doLayout();
		winRol.show();
		storeGridRol.baseParams = {};
		storeGridRol.baseParams.idusuario = sm.getSelected().data.idusuario;
		storeGridRol.load({params:{start:0,limit:15}});
		arrayPadresEliminar = [];
		arregloDeschequeados = [];
		arrayTiene = [];
		smrol.clearSelections();
		
		
	}
	
	////------------ Arbol entidades ------------////
     arbolEstructura = new Ext.tree.TreePanel({
        autoScroll:true,
        region:'west',
        split:true, 
        width:'37%',
        loader: new Ext.tree.TreeLoader({
            dataUrl:'cargarestructura'      
        })
    });
    ////------------ Crear nodo padre del arbol ------------////
    padreArbolEstructura = new Ext.tree.AsyncTreeNode({
          text: 'Estructuras',
          expandable:true,
          id:'0'
          });         
    arbolEstructura.setRootNode(padreArbolEstructura);
    
    ////------------ Evento para cargar las areas-------------////
   arbolEstructura.on('click', function (node, e){
        if (node.id > 0)
            {
                identidad = node.id;
                entidad = node.attributes.text;
                arbolAreas.enable(); 
                arbolAreas.getRootNode().reload();   
            }
            }, this);
    
     
    
     ////------------ Arbol de areas ------------////
     arbolAreas = new Ext.tree.TreePanel({
        autoScroll:true,
        region:'center',
        disabled:true,
        split:true, 
        width:'37%',
        loader: new Ext.tree.TreeLoader({
            dataUrl:'cargarareas',
            
                        listeners:{'beforeload':function(atreeloader, anode){ 
                            atreeloader.baseParams = {};
                                      atreeloader.baseParams.identidad = identidad 
                                                                                       
                        }
                    }      
        }) 
    });
    ////------------ Crear nodo padre del arbol ------------////
    padreArbolAreas = new Ext.tree.AsyncTreeNode({
          text: 'Areas',
          expandable:false,
          id:'0'
          });         
    arbolAreas.setRootNode(padreArbolAreas);
    
    ////------------ Evento para cargar los cargos-------------////
   arbolAreas.on('click', function (node, e){
        if (node.id > 0)
            {
                idarea = node.id;
                area = node.attributes.text;
                arbolCargos.enable();
                arbolCargos.getRootNode().reload();   
            }
            }, this);
    
         ////------------ Arbol de cargos ------------////
     arbolCargos = new Ext.tree.TreePanel({
        autoScroll:true,
        region:'east',
        split:true,
        disabled:true,  
        width:'37%',
        loader: new Ext.tree.TreeLoader({
            dataUrl:'cargarcargos',
            
                        listeners:{'beforeload':function(atreeloader, anode){ 
                            atreeloader.baseParams = {};
                            if(entidad && area)
                                { 
                                  if(modificar)
                                  {
                                     atreeloader.baseParams.identidad = identidad,
                                     atreeloader.baseParams.idarea = idarea  
                                  }                                  
                                  else
                                    {    
                                       atreeloader.baseParams.idcargo = anode.id,
                                       atreeloader.baseParams.identidad = identidad,
                                       atreeloader.baseParams.idarea = idarea 
                                    }                     
                                }                                                                        
                        }
                    }                             
        })
    });
    ////------------ Crear nodo padre del arbol ------------////
    padreArbolCargos = new Ext.tree.AsyncTreeNode({
          text: 'Cargos',
          expandable:false,
          id:'0'
          });         
    arbolCargos.setRootNode(padreArbolCargos);
    
        ////------------ Evento para guardar cargos-------------////
   arbolCargos.on('click', function (node, e){
        if (node.id > 0)
            {
                idcargo = node.id;
                cargo = node.attributes.text;                 
            }
            }, this);
    
             ////------------ Panel para arboles de estructuras ------------////
        panelEstructuras = new Ext.Panel({
            layout:'border',
            items:[arbolEstructura,arbolAreas,arbolCargos]
        });
       
            ////------------Ventana para cargar el arbol de estructuras-----------///////
            function ventanaestructura()
            {
                winEstructuras= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
                            title:perfil.etiquetas.lbTitEstructura,width:700,height:400,resizable:false,
                            buttons:[
                            {
                                icon:perfil.dirImg+'cancelar.png',
                                iconCls:'btn',
                                id:'estcan',
                                text:perfil.etiquetas.lbBtnCancelar,
                                handler:function(){inicializarArboles();}
                            },
                            {
                                icon:perfil.dirImg+'aceptar.png',
                                iconCls:'btn',
                                id:'estacept',
                                handler:function(){cargarEntidadAreaCargo();},
                                text:perfil.etiquetas.lbBtnAceptar
                            }]
                        });
                                   
                    winEstructuras.add(panelEstructuras);
                    winEstructuras.doLayout();
                    winEstructuras.show();
            }
            
            
       function cargarEntidadAreaCargo()
             {
                
                Ext.getCmp('entidad').setRawValue(entidad);
                Ext.getCmp('area').setRawValue(area);
                Ext.getCmp('cargo').setRawValue(cargo);  
                arbolCargos.collapseAll();
                arbolCargos.disable();
                arbolAreas.collapseAll();
                arbolAreas.disable();
                arbolEstructura.getRootNode().reload();
                winEstructuras.hide(); 
             }
           ////------------ Inicializo todos los arboles ------------////   
            function inicializarArboles()
            {
             arbolCargos.collapseAll();
             arbolCargos.disable();
             arbolAreas.collapseAll();
             arbolAreas.disable();
             arbolEstructura.getRootNode().reload();
             winEstructuras.hide();
            }
		////------------ Modificar usuario ------------////
	var confirmarModDominio = false;
	function modificarUsuario(apl) 
        {
        if(Ext.getCmp('ip').getValue()){
            if(!validaRango()){
                mostrarMensaje(3,perfil.etiquetas.lbTitMsgRangoIncorrecto);return;}
         }
         if(Ext.getCmp('iddom').getValue() == 0){
                mostrarMensaje(3,perfil.etiquetas.lbTitMsgDominioIncorrecto);return;}
          if (regUsuario.getForm().isValid())
          {
              if(Ext.getCmp('contrasenna').getValue() ==  Ext.getCmp('contrasena').getValue())
              {
			if ((confirmarModDominio == true && apl == 'ok') || confirmarModDominio == false) {
				regUsuario.getForm().submit({
					url:'modificarusuario', 
					waitMsg:'Modificando usuario...', 
					params:{identidad:identidad, 
						idarea:idarea, 
						idcargo:idcargo,
						idusuario:sm.getSelected().data.idusuario, 
						confirmar: confirmarModDominio},                                       
					failure: function(form, action)
					{
						if(action.result.codMsg == 2) {
							confirmarModDominio = true;
							mostrarMensaje(2, action.result.mensaje, modificarUsuario);
						}
						else if(action.result.codMsg == 1)
						{
							mostrarMensaje(action.result.codMsg,action.result.mensaje); 
							regUsuario.getForm().reset();      
							winMod.hide();      
							btnModificar.disable();
							btnEliminar.disable();
							btnAdicionar.enable();
							btnRol.disable(); 
							storeGrid.reload();
							confirmarModDominio = false;
						}
						else {
							mostrarMensaje(action.result.codMsg,action.result.mensaje);
							confirmarModDominio = false;
						}
					}
				});
			}
			else
				confirmarModDominio = false;
              }
              else
               	mostrarMensaje(3,perfil.etiquetas.lbTitMsgContrasenaIncorrecta); 
          }
          else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);           
         }
         
		//Funcion activar usuarios
        function activarUsuarios(){
        	var arrUsuariosActivar = sm.getSelections();
            	var arrUsuarioAct = [];
            	for (var i=0;i<arrUsuariosActivar.length;i++){
            		if(arrUsuariosActivar[i].data.activo == true){
            			mostrarMensaje(3,perfil.etiquetas.lbMsgYaActivados);
            			arrUsuarioAct = [];
            			return;
            		}
            		else
				arrUsuarioAct.push(arrUsuariosActivar[i].data.idusuario);
            	}
				Ext.Ajax.request({
				url: 'ActivarUsuario',
				method:'POST',
				params:{arrUsuarioAct:Ext.encode(arrUsuarioAct)},						
				callback: function (options,success,response){
						responseData = Ext.decode(response.responseText);
						if(responseData.codMsg != 3){							
							mostrarMensaje(1,perfil.etiquetas.lbMsgActivados);
							storeGrid.load({params:{start:0,limit:15}});							
						}
					}
					});
        }
        
        //Funcion desactivar usuarios
        function desactivarUsuarios(){
        	var arrUsuariosDesctivar = sm.getSelections();
            	var arrUsuarioDesact = [];
            	for (var i=0;i<arrUsuariosDesctivar.length;i++){
            		if(arrUsuariosDesctivar[i].data.activo == false){
            			mostrarMensaje(3,perfil.etiquetas.lbMsgNoActivados);
            			arrUsuarioDesact = [];
            			return;
            		}
            		else
						arrUsuarioDesact.push(arrUsuariosDesctivar[i].data.idusuario);
            	}
				Ext.Ajax.request({
				url: 'DesactivarUsuario',
				method:'POST',
				params:{arrUsuarioDesact:Ext.encode(arrUsuarioDesact)},						
				callback: function (options,success,response){
						responseData = Ext.decode(response.responseText);
						if(responseData.codMsg != 3){							
							mostrarMensaje(1,perfil.etiquetas.lbMsgDesactivados);
							storeGrid.load({params:{start:0,limit:15}});
						}
					}
					});	
        }
        
		//Funcion eliminar
		function eliminarusuario(){
		var arrUsuariosElim = sm.getSelections();
            	var arrUsuarioElim = [];
            	for (var i=0;i<arrUsuariosElim.length;i++)
                {
		arrUsuarioElim.push(arrUsuariosElim[i].data.idusuario);
		}
			mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminarlo(s)?',elimina);
			function elimina(btnPresionado){
				if (btnPresionado == 'ok'){
					Ext.Ajax.request({
						url: 'eliminarusuario',
						method:'POST',
						params:{ArrayUserDel:Ext.encode(arrUsuarioElim)},
						
						callback: function (options,success,response){
								responseData = Ext.decode(response.responseText);
								if(responseData.codMsg == 1){
									mostrarMensaje(responseData.codMsg,responseData.mensaje);
									storeGrid.reload();
									sm.clearSelections();
									btnEliminar.disable();
									btnModificar.disable();
									btnAdicionar.enable();
                                    btnRol.disable(); 
		
								}
							}
							});
					}
				}
			}


		//Cargar Rol
		function adicionarusuario(apl)
		 {
         if(Ext.getCmp('ip').getValue()){
            if(!validaRango())
			{
                mostrarMensaje(3,perfil.etiquetas.lbTitMsgRangoIncorrecto);return;
			}
         }
         if(Ext.getCmp('iddom').getValue() == 0){
                mostrarMensaje(3,perfil.etiquetas.lbTitMsgDominioIncorrecto);return;}
		  if (regUsuario.getForm().isValid())
		  { 
              if(Ext.getCmp('contrasenna').getValue() != 0 && Ext.getCmp('contrasena').getValue() != 0)
              {          
		          if(Ext.getCmp('contrasenna').getValue() ==  Ext.getCmp('contrasena').getValue())
		          {
		              regUsuario.getForm().submit({
		              url:'insertarusuario', 
		              waitMsg:'Registrando usuario...', 
                      params:{identidad:identidad, idarea:idarea, idcargo:idcargo},                         		      
		               failure: function(form, action)
		                {	      
			               if(action.result.codMsg != 3)
			                {
			                 mostrarMensaje(action.result.codMsg,action.result.mensaje); 
			                 regUsuario.getForm().reset(); 
					         storeGrid.reload();
			                if(!apl)        
			                   winIns.hide();      
			                 btnModificar.disable();
			                 btnEliminar.disable();
			                 btnAdicionar.enable();
                             btnRol.disable(); 
			                 storeGrid.reload();
			               }
			              if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
		                }
                      } );
                  }
		        else
		        mostrarMensaje(3,perfil.etiquetas.lbTitMsgContrasenaIncorrecta);
              }
              else
              mostrarMensaje(3,perfil.etiquetas.lbTitMsgEnblanco); 
		  }
          else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);          
		 }
         
         
        ////---------- Asignar Roles al usuario ----------////  
        
        function asignarRol(apl, record)
            {
		if (!idrol)
			return;
		        var arrayNodos = arbolEntidades.getChecked();
                var arrayEnt = new Array();
                var arrayPadres = new Array();
                var arrayarbol = new Array();
		        var arrUsuarios = sm.getSelections();
            	var arrUsuario = [];
            	for (var i=0;i<arrUsuarios.length;i++) {
            		var user = [];
            		user.push(arrUsuarios[i].data.idusuario);
            		user.push(arrUsuarios[i].data.iddominio);
		            arrUsuario.push(user);
		        }
 
                buscarNodosTiene(arbolEntidades.getRootNode());
                if(arrayNodos.length > 0)
                {
                    for (var i=0; i<arrayNodos.length; i++)
                    {                   
                            arrayEnt.push(arrayNodos[i].attributes.id);
                            if(!arrayNodos[i].isLeaf() && arrayNodos[i].childNodes.length == 0)
                            {       
                                arrayPadres.push(arrayNodos[i].attributes.id);
                            }
                            //if(arrayNodos[i].attributes.id != 0)
                            //arrayarbol.push(armarcadena(arrayNodos[i]));
                    }
                 }
                    Ext.getBody().mask('Por favor espere....'); 
                    Ext.Ajax.request({
                    url: 'asignarroles',
                    method:'POST',
                    params:{arrayEntidades: Ext.encode(arrayEnt), arrayEntidadesEliminar:Ext.encode(arregloDeschequeados), arrayPadres:Ext.encode(arrayPadres), arrayPadresEliminar:Ext.encode(arrayPadresEliminar), arrayTiene:Ext.encode(arrayTiene),arrayUsuario:Ext.encode(arrUsuario), idrol:idrol},
                    callback: function (options,success,response){                    
                    Ext.getBody().unmask();
                    arrayPadresEliminar = [];
                    arregloDeschequeados = [];
                    arrayTiene = [];
                    responseData = Ext.decode(response.responseText);
                    if(responseData.bien == 1)
                    {          
                        if(apl)
                        {                           
                         mostrarMensaje(1,'Los roles fueron asignados satisfactoriamente');
                         winRol.hide();
                         btnModificar.disable();
                         btnEliminar.disable();
                         btnAdicionar.enable();
                         btnRol.disable();
                         arbolEntidades.disable(); 
                         sm.clearSelections() ;
                         smrol.clearSelections(); 
                        }
                        else {
					record.data.estado = 1;
					gridRol.getView().refresh();					
					arbolEntidades.getRootNode().reload();					
			}
                        return true;
                    }
                    else if (responseData.bien == 2)
                    {   
                        mostrarMensaje(3,'El rol debe estar asociado al menos a una entidad.');
                         return false;
                    }
		    else if (responseData.bien == 3)
			return true;
            
		    else if (responseData.bien == 4) {
                if(apl)
                {
                 mostrarMensaje(1,'Operacion concluida satisfactoriamente');
                 winRol.hide();
                 btnModificar.disable();
                 btnEliminar.disable();
                 btnAdicionar.enable();
                 btnRol.disable();
                 arbolEntidades.disable(); 
                 sm.clearSelections() ;
                 smrol.clearSelections(); 
                }
                else
                {
                record.data.estado = 0;
                gridRol.getView().refresh();
                return true;
                }
            
		    }
                    if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
                }
            });
        }
            
        ////----------------------------------- Cambiar Password --------------------------------////
        function cambiarpass()
        {
            if(fpPass.getForm().isValid())
            {
                if(Ext.getCmp('contrasennap').getValue() ==  Ext.getCmp('contrasenap').getValue())
                {
                    fpPass.getForm().submit({
                            url:'cambiarpassword', 
                            waitMsg:'Cambiando contrase&ntilde;a...', 
                            failure: function(form, action)
                            {          
                               if(action.result.codMsg != 3)
                                {
                                    mostrarMensaje(action.result.codMsg,action.result.mensaje);                             
                                    winPass.hide();      
                                }
                              if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                            }
                    } );
                }
                else
                mostrarMensaje(3,perfil.etiquetas.lbTitMsgContrasenaIncorrecta);
            }
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);            
        }
        
	function buscarnombreusuario(nombreusuario,dominiobuscar,activar){  
		storeGrid.baseParams = {};
		storeGrid.baseParams.nombreusuario = nombreusuario;
		storeGrid.baseParams.dominiobuscar = dominiobuscar;
		storeGrid.baseParams.activar = activar;
            	storeGrid.load({params:{start:0,limit:15}});
	}
    
    function buscardenrol(denrolbuscado)
    {
     storeGridRol.load({params:{rolbuscado:denrolbuscado,start:0,limit:15}});
    }
    
    } 
 
		////------------ Para marcar un solo nodo ------------////
		function marcauno(node,idnode)
		{
			var arraynodos=node.parentNode.childNodes;
		  	if(node.attributes.checked)
		  	{
		  		for(var i=0; i<arraynodos.length; i++)
		  		{
		   			if(arraynodos[i].id!=idnode)
						cambiarEstadoCheck(arraynodos[i],false);
		   			else
						cambiarEstadoCheck(arraynodos[i],true);     
		  		}
		 	}
		}
 
 		////------------ Auxiliar para marcar y desmarcar nodos ------------////
	 	function cambiarEstadoCheck(anodehijo,check)
	 	{
			if(anodehijo.attributes.checked != check)
			{
				anodehijo.getUI().toggleCheck(check);
				anodehijo.attributes.checked = check;
				banderaClick = false;
				anodehijo.fireEvent('checkchange',anodehijo,check);
			}
		}
	
		////------------ Funcion para validar rango de ip ------------////
        function validaRango()
        {
            var i;
            var rango = Ext.getCmp('ip').getValue();
            var ips = rango.split(',');
            var bandera = true;
            for(i = 0; i < ips.length ; i++)
            {
	         if(!compruebaRango(ips[i])){
                bandera = false;break; 
             }  	
            }
            if(bandera)
            return true;
            else
            return false;
        }
        
        function compruebaRango(valor)
        {  
        var ipmascara = valor.split('/');
        var ipreal = ipmascara[0];
        var ippos = ipreal.split('.');
            if((ipmascara[1]))
                {      
                 if(ipmascara[1] == 0)
                     {    
                         if(ippos[0] == 0 )
                            {   
                            if(ippos[0] == 0 && ippos[1] == 0 && ippos[2] == 0 && ippos[3] == 0)
                                return true;
                                else
                                return false;
                            }
                         else
                             {
                             if( (ippos[0] >= 1 && ippos[0] <= 255)  && (ippos[1] >= 0 && ippos[0] <= 255) && (ippos[2] >= 0 && ippos[2] <= 255) && (ippos[3] >= 0 && ippos[3] <= 255))
                                    return true;
                                    else
                                    return false;
                             }
                    }
                    else{ 
                        if(ipmascara[1] >= 1 && ipmascara[1] <= 32 ){ 
                             if( (ippos[0] >= 1 && ippos[0] <= 255)  && (ippos[1] >= 0 && ippos[0] <= 255) && (ippos[2] >= 0 && ippos[2] <= 255) && (ippos[3] >= 0 && ippos[3] <= 255))
                                    return true;
                              else
                                    return false;
                            }
                            else
                            return false;
                        }
                }
            else
                 { 
                   if(ippos[0] == 0 )
                   {
                    if(ippos[0] == 0 && ippos[1] == 0 && ippos[2] == 0 && ippos[3] == 0)
                        return true;
                        else
                        return false;
                   }
                 else
                     { 
                     if( (ippos[0] >= 1 && ippos[0] <= 255)  && (ippos[1] >= 0 && ippos[0] <= 255) && (ippos[2] >= 0 && ippos[2] <= 255) && (ippos[3] >= 0 && ippos[3] <= 255))
                            return true;
                            else
                            return false;
                     }
                 }
            return false;
        }
        
        ////------------ Para marcar un solo nodo ------------////
        function marcauno(node,idnode)
        {
            var arraynodos=node.parentNode.childNodes;
              if(node.attributes.checked)
              {
                  for(var i=0; i<arraynodos.length; i++)
                  {
                       if(arraynodos[i].id!=idnode)
                        cambiarEstadoCheck(arraynodos[i],false);
                       else
                        cambiarEstadoCheck(arraynodos[i],true);     
                  }
             }
        }
        
        function estaEnDeschequeados(arreglonodos, idnodo)
        {
            var cantidad = arreglonodos.length;
            for (p=0; p<cantidad; p++)
            {
                if(arreglonodos[p] == idnodo)
                   return p;
            }
            return -1;
        }
        
        function eliminarEnDeschequeados(arreglo, pos)
        {
            arreglo.splice(pos,1);
        }
        
        function adicionarEnDeschequeados(arreglo, id)
        {
            if(estaEnDeschequeados(arreglo, id) == -1)
               arreglo.push(id);
        }
 
         ////------------ Auxiliar para marcar y desmarcar nodos ------------////
         function cambiarEstadoCheck(anodehijo,check)
         {
            if(anodehijo.attributes.checked != check)
            {
                anodehijo.getUI().toggleCheck(check);
                anodehijo.attributes.checked = check;
                banderaClick = false;
                anodehijo.fireEvent('checkchange',anodehijo,check);
            }
        }
        
        function buscarNodosTiene(nodo)
        {
            nodo.eachChild(function(anodehijo)
            {
                if(!anodehijo.attributes.checked && !anodehijo.isLeaf() && anodehijo.attributes.tiene && anodehijo.childNodes.length == 0)
                    arrayTiene.push(anodehijo.attributes.id);
                if(anodehijo.childNodes.length > 0)
                    buscarNodosTiene(anodehijo);            
                        
            },this);
        }
        
         function marcarArriba(nodo)
        {
            if(nodo.attributes.id != 0)
            {
                if(estadoTodosHijos(nodo.parentNode, true))
                    cambiarEstadoCheck(nodo.parentNode,true);
                marcarArriba(nodo.parentNode);
            }
        }
        
        function desmarcarArriba(nodo)
        {
            aBandera = true;
            if(nodo.attributes.id != 0)
            {
                cambiarEstadoCheck(nodo.parentNode,false);        
                if(nodo.parentNode.attributes.id >= 0)
                {
                    desmarcarArriba(nodo.parentNode);
                }
            }
        }
        
        function marcarHijos(nodo,check)
        {
            nodo.eachChild(function(anodehijo)
            {
                if(anodehijo.attributes.checked != check)
                    cambiarEstadoCheck(anodehijo,check);
                if(anodehijo.childNodes.length > 0)
                    marcarHijos(anodehijo, check);    
            },this);
        }
        
                ////------------ Auxiliar para marcar y desmarcar nodos ------------////
        function cambiarEstadoCheck(anodehijo,check)
             {
                if(anodehijo.attributes.checked != check)
                {
                    anodehijo.getUI().toggleCheck(check);
                }
            }
            
        function estadoTodosHijos(nodo, opcion)
        {
            resultado = true;
            nodo.eachChild(function(anodehijo)
            {
                if(anodehijo.attributes.checked != opcion)
                    resultado = false;    
            },this);
            return resultado;
        }
        
        function armarcadena(nodo)
        {
            var cadena = '';
            if(nodo.parentNode)
            {
                if(nodo.attributes.tipo == 'externa')
                {
                    cad = armarcadena(nodo.parentNode);
                    if(cad != '')
                        cadena += cad + "-" + nodo.attributes.id + '_e';
                    else
                        cadena += nodo.attributes.id + '_e';     
                } 
                else
                { 
                    cad = armarcadena(nodo.parentNode);
                    if(cad != '')
                        cadena += cad + "-" + nodo.attributes.id + '_i';
                    else
                        cadena += nodo.attributes.id + '_i'; 
                }
                return cadena;  
            }
           return cadena;
        }
        
