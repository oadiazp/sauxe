		var perfil = window.parent.UCID.portal.perfil;
		UCID.portal.cargarEtiquetas('gestrol', function(){cargarInterfaz();});	
        
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();

		////------------ Declarar Variables ------------////
		var winIns, winMod, winRegular,denrol, idsistema ,arraysistemas = [], padre=0,arbolSistema,arraytiene = [];
		var modificar=false;var auxDelete = true;var auxIns = false;var auxMod = false;
		var auxDel = false;var auxReg = false;var auxMod1 = false;var auxDel1 = false;
		var auxReg1 = false;var auxIns2 = true;var auxMod2 = true;var auxDel2 = true;
		var auxReg2 = true;var auxBus2 = true;var auxAcept = false;var auxApl = false;
		var auxCanc = false;
		letrasnumeros = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\_\s]*))$/; 
		function cargarInterfaz(){
		////------------ Botones ------------////
		btnAdicionar = new Ext.Button({id:'btnAgrRol', hidden:true, icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');} });
		btnModificar = new Ext.Button({id:'btnModRol', disabled:true, hidden:true, icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnModificar, handler:function(){winForm('Mod');} });
		btnEliminar = new Ext.Button({id:'btnEliRol', disabled:true, hidden:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar, handler:function(){eliminarRol();}});
        btnRegular = new Ext.Button({disabled:true,id:'btnRestRol', hidden:true, icon:perfil.dirImg+'restringir.png', iconCls:'btn', text:perfil.etiquetas.lbBtnRegularAcciones,handler:function(){winForm('Reg');} });
        btnSiguiente = new Ext.Button({disabled:true,id:'btnsiguiente',style:'margin-left:18px;margin-top:110px',icon:perfil.dirImg+'siguiente.png',iconCls:'btn',handler:function(){eliminaraccion();} });
        btnAnterior = new Ext.Button({disabled:true,id:'btnAnterior',style:'margin-left:18px;margin-top:90px',icon:perfil.dirImg+'anterior.png',iconCls:'btn',handler:function(){adicionaraccion();} });
		btnAyuda = new Ext.Button({id:'btnAyuRol', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        ////------------ Arbol de sistemas ------------////
 		arbolSistema = new Ext.tree.TreePanel({
			title:'Sistemas registrados',
			autoScroll:true,
			region:'center',                           
			split:true,
			width:'37%',
			loader: new Ext.tree.TreeLoader({
				dataUrl:'cargarsistemafuncionalidades',
				listeners:{'beforeload':function(atreeloader, anode){ 
								atreeloader.baseParams = {};                                            
	                                  if(modificar)
	                                  {
	                                     atreeloader.baseParams.idsistema = anode.attributes.idsistema
	                                     atreeloader.baseParams.idrol =sm.getSelected().data.idrol
	                                     if(anode.attributes.tiene)
	                                        arraytiene.push(anode.attributes.idsistema)
	                                      
	                                  }                                  
	                                  else
	                                    {    
	                                       atreeloader.baseParams.idsistema = anode.attributes.idsistema
	                                    }                     									
							}
						}
			})
		});
	////------------ Crear nodo padre del arbol ------------////
	padreArbolSistema = new Ext.tree.AsyncTreeNode({
	      text: 'Subsistemas',
		  expandable:false,
		  id:'0'
	      });
	      
	arbolSistema.setRootNode(padreArbolSistema);
    
		////------------- Store del Grid de Roles -------------- ////
		stGpRol =  new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({
					url: 'cargarrol'
			}),
			listeners:{'beforeload':function(thisstore,objeto){
            
			}},
			reader:new Ext.data.JsonReader({
							totalProperty: "cantidad_filas",
							root:"datos",
							id: "idrol"
					},
					
					[
							{name:'idrol',mapping:'idrol'},
							{name:'descripcion',mapping:'descripcion'},
							{name:'abreviatura',mapping:'abreviatura'},
							{name:'denominacion',mapping:'denominacion'},
                            {name:'identidad',mapping:'identidad'}
					 ])
	});	
	
		////------------ Establesco modo de seleccion de grid (single) ---------////
	sm = new Ext.grid.RowSelectionModel({singleSelect:false});

	sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
				            if(record.data.idrol == 10000000001 )
						btnEliminar.disable();
					     else
						btnEliminar.enable();	
						btnModificar.enable();
						btnRegular.enable();
					}, this);
                    
    sm.on('rowselect', function (smodel, rowIndex, keepExisting, record){
            if(sm.getCount()!=1){
                btnModificar.disable();
                btnRegular.disable();
            }else{
                btnModificar.enable();
                btnRegular.enable();
            }
        }, this);

    sm.on('rowdeselect', function (smodel, rowIndex, keepExisting, record){
            if(sm.getCount()!=1){
                btnModificar.disable();
                btnRegular.disable();
            }else{
                btnModificar.enable();
                btnRegular.enable();
            }
        }, this);
		
		////---------- Defino el grid de roles ----------////
		var gpRol = new Ext.grid.GridPanel({
			frame:true,
			region:'center',
			iconCls:'icon-grid',
			autoExpandColumn:'expandir',
			store:stGpRol,
			sm:sm, 
			columns: [
						{hidden: true, hideable: false, dataIndex: 'idrol'},
						{id:'expandir',header:perfil.etiquetas.lbTitMsgDenominacion,width:200,dataIndex: 'denominacion'},
						{header:perfil.etiquetas.lbTitMsgAbreviatura, width:200, dataIndex: 'abreviatura'},
						{header:perfil.etiquetas.lbTitMsgDescripcion, width:200, dataIndex: 'descripcion'},
                        {hidden: true, hideable: false, dataIndex: 'identidad'}
		 			 ],
					 
			loadMask:{store:stGpRol},            
                    
            tbar:
                [
                    new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbTitDenBuscar}),
                    denrol = new Ext.form.TextField({width:80, id: 'denrol'}),
                    new Ext.menu.Separator(),
                    new Ext.Button({icon:perfil.dirImg+'buscar.png',iconCls:'btn',text:perfil.etiquetas.lbBtnBuscar, handler:function(){buscarnombrerol(denrol.getValue())}})
			    ],
			bbar:new Ext.PagingToolbar({
			pageSize: 15,
			id:'ptbaux',
			store: stGpRol,
			displayInfo: true,
			displayMsg:perfil.etiquetas.lbTitMsgResultados,
			emptyMsg:perfil.etiquetas.lbTitMsgNingunresultadoparamostrar
		})
		});
		////------------ Trabajo con el PagingToolbar ------------////
		Ext.getCmp('ptbaux').on('change',function(){
			sm.selectFirstRow();
		},this)
		////---------- Renderiar el arbol ----------////
		var panel = new Ext.Panel({
			layout:'border',
			title:perfil.etiquetas.lbTitMsgGestionarRoles,
			items:[gpRol],
			tbar:[btnAdicionar,btnModificar,btnRegular,btnEliminar,/*btnAyuda*/],
			keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    			if(auxDelete && auxDel && auxDel1 && auxDel2)
		    				eliminarRol();
		    			}
		    			
		    		  },
		    		  {
		    				key:Ext.EventObject.ENTER,
		    				fn: function(){
		    						if(auxAcept)
		    							adicionarRol();
		    				}
		    		  },/*
		    		  {
		    		  	key:"p",
		    			ctrl:true,
		    			fn: function(){
		    				if(auxApl)
		    					adicionarRol('apl')}
		    		  },*/
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
		    				if(auxBus2)
		    					buscarnombrerol(denrol.getValue());}
		    		  },
		    		  {
		    		  	key:"u",
		    			alt:true,
		    			fn: function(){
		    				if(auxReg && auxReg1 && auxReg2)
		    					winForm('Reg');}
		    		  },
		    		  {
		    		  	key:"m",
		    			alt:true,
		    			fn: function(){
		    				if(auxMod && auxMod1 && auxMod2)
		    					winForm('Mod');}
		    		  }])
		});
		stGpRol.load({params:{start:0,limit:15}});
		////---------- Eventos para hotkeys ----------////
		btnAdicionar.on('show',function(){
			auxIns = true;
		},this)
		btnEliminar.on('show',function(){
			auxDel = true;
		},this)
		btnModificar.on('show',function(){
			auxMod = true;
		},this)
		btnRegular.on('show',function(){
			auxReg = true;
		},this)
		Ext.getCmp('denrol').on('focus',function(){
			auxDelete = false;
		},this)
		Ext.getCmp('denrol').on('blur',function(){
			auxDelete = true;
		},this)
		stGpRol.on('load',function(){
			if(stGpRol.getCount() != 0)
			{
				auxMod1 = true;
				auxDel1 = true;
				auxReg1 = true;
			}
			else
			{
				auxMod1 = false;
				auxDel1 = false;
				auxReg1 = false;
			}
		},this)

		////---------- Viewport ----------////
		var vpGestRol = new Ext.Viewport({
			layout:'fit',
			items:panel
		})
		
		////---------- Formulario ----------////
		var regrol = new Ext.FormPanel({
			labelAlign: 'top',
            collapsible:true,
			width:200,
            region:'west',
			frame:true,
			bodyStyle:'padding:5px 5px 0',
			items: [{
						layout:'column',
						items:[{
								columnWidth:1,
								layout: 'form',
								items: [{
										 	xtype:'textfield',
											fieldLabel:perfil.etiquetas.lbTitMsgDenominacion,
										 	name: 'denominacion',
                                            maxLength:40,
										 	anchor:'100%',
											allowBlank:false,
											blankText:perfil.etiquetas.lbMsgEstecampoesrequerido,
											regex:/(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/						
										},
										{
											xtype:'textfield',
											fieldLabel:perfil.etiquetas.lbTitMsgAbreviatura,
											name: 'abreviatura',
                                            maxLength:40,
											anchor:'100%',
											allowBlank:false,
											blankText:perfil.etiquetas.lbMsgEstecampoesrequerido,
											regex:/(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/,
											regexText:perfil.etiquetas.lbMsgIntrodujovaloresnopermitidos
										},					
										{
											xtype:'textarea',
											fieldLabel:perfil.etiquetas.lbTitMsgDescripcion,
											name: 'descripcion',
											height:180,
											anchor:'100%'
										}]
							   }]
				   }]
		});

        /////////////////////////////////////mi mierdita
          ////------------ Store del Grid de acciones que tiene un usuario ------------////
        var storeTieneAccion =  new Ext.data.Store({
                url: 'cargaraccionesquetiene',
                listeners:{'beforeload':function(thisstore,objeto){
                objeto.params.idsistema=idi,
                objeto.params.idrol=idr,
                objeto.params.idfuncionalidad=idu
                }},
                reader:new Ext.data.JsonReader({
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "ide"
            },
                        [
                            {name:'idaccion',mapping:'idaccion'},
                            {name:'denominacion',mapping:'denominacion'},
                            {name:'idfuncionalidad',mapping:'idfuncionalidad'}
                        ])
        });
        ////------------- Modo de seleccion del Grid de acciones que tiene un usuario -------------////
        var smtieneaccion = new Ext.grid.RowSelectionModel({singleSelect:false});
        smtieneaccion.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
                             btnAnterior.disable();
                             btnSiguiente.enable();
                            }, this);
            
        ////------------- Creando el grid de acciones que tiene un usuario -------------////
        var gridtieneaccion = new Ext.grid.GridPanel({ 
              title:perfil.etiquetas.lbTitAccionesautorizadas,   
            frame:true,
            iconCls:'icon-grid',    
            autoExpandColumn:'expandir',
            margins:'2 2 2 -4',
            store:storeTieneAccion,
            height:315,
            sm:smtieneaccion,
            columns: [
                        {header:perfil.etiquetas.lbDenominacion, width:200, dataIndex: 'denominacion', id:'expandir'},
                        {hidden: true, hideable: false, dataIndex: 'idaccion'},
                        {hidden: true, hideable: false, dataIndex: 'idfuncionalidad'}
                     ],
            
            loadMask:{store:storeTieneAccion},
            bbar:new Ext.PagingToolbar({
                pageSize: 15,
                id:'ptbaux2',
                store: storeTieneAccion                                                 
            })
            
        });
        ////------------ Trabajo con el PagingToolbar del grid acciones autorizadas ------------////
		Ext.getCmp('ptbaux2').on('change',function(){
			smtieneaccion.selectFirstRow();
		},this)
        ////------------ Store del grid de acciones que no tiene un usuario ------------////
        var storeNoTieneAccion =  new Ext.data.Store({
                url: 'cargaraccionesquenotiene',
                listeners:{'beforeload':function(thisstore,objeto){objeto.params.idsistema=idi,
                objeto.params.idrol=idr,
                objeto.params.idfuncionalidad=idu
                }},
                reader:new Ext.data.JsonReader({
                totalProperty: "cantidad_filas",
                root: "datos",
                id: "ide"
            },
                        [{name:'idaccion',mapping:'idaccion'},
                        {name:'idfuncionalidad',mapping:'idfuncionalidad'},
                        {name:'denominacion',mapping:'denominacion'}]
            )
        });
        ////------------ Modo de seleccion  del grid de acciones que no tiene un usuario ------------////
        var smnotieneaccion = new Ext.grid.RowSelectionModel({singleSelect:false});
        smnotieneaccion.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
                             btnAnterior.enable();
                             btnSiguiente.disable();
                            }, this);
            
        ////------------ Creando el grid de acciones que no tiene un usuario ------------////
        var gridnotieneaccion = new Ext.grid.GridPanel({ 
            title:perfil.etiquetas.lbAccionesnoautorizadas,   
            height:315,
            frame:true,
            iconCls:'icon-grid',    
            autoExpandColumn:'expandir',
            margins:'2 2 2 -4',
            store:storeNoTieneAccion,
            sm:smnotieneaccion,
            columns: [
                        {header:perfil.etiquetas.lbDenominacion, width:200, dataIndex: 'denominacion', id:'expandir'},
                        {hidden: true, hideable: false, dataIndex: 'idaccion'},
                        {hidden: true, hideable: false, dataIndex: 'idfuncionalidad'}             
                      ],
            
            loadMask:{store:storeNoTieneAccion},
            bbar:new Ext.PagingToolbar({
                pageSize: 15,
                store: storeNoTieneAccion
            })
            
        });

        ////------------ Arbol de sistema y roles para seleccionar acciones ------------////
         arbolRestAccion = new Ext.tree.TreePanel({
            title:perfil.etiquetas.lbTitMsgSistemasyroles,
            border:false,
               autoScroll:true,
            region:'west',
            width:200,
            margins:'2 2 2 2',
            loader: new Ext.tree.TreeLoader({
                        listeners:{'beforeload':function(atreeloader, anode)                        
                            { 
                                atreeloader.baseParams = {};
                                if(sm.getSelected())
                                { 
                                  arbolRestAccion.getLoader().baseParams = {idrol:sm.getSelected().data.idrol,idsistema:anode.attributes.idsistema}
                                }
                            }
                        },
                dataUrl:'cargarsistemafunc'
            })
        });
    
    
          ////------------ Crear nodo padre del arbol ------------////
        padreRestAccion= new Ext.tree.AsyncTreeNode({
            text:perfil.etiquetas.lbTitMsgSubsistemas,
              expandable:false,
              expanded:true,
              id:'0'
        });
        
        ////------------ Habilitar botones y cargar id de Nodos ------------////
        arbolRestAccion.setRootNode(padreRestAccion);    

        ////------------ Evento para habilitar botones. ------------////
        arbolRestAccion.on('click', function (node, e)
        {
            if (node.isLeaf())
            {
                idi=node.attributes.idsistema;
                idr=sm.getSelected().data.idrol;
                idu=node.attributes.idfuncionalidad;
                storeNoTieneAccion.load({params:{start:0,limit:15}});
                storeTieneAccion.load({params:{start:0,limit:15}});
            }
        }, this);
    
        ////------------ Panel para las acciones ------------////
        var regularAcciones = new Ext.FormPanel({
                labelAlign: 'top',
                frame:true,
                region:'center',
                height:370,
                items: [{
                            layout:'column',
                            items:[{
                                        columnWidth:.45,
                                        layout: 'form',
                                        items: [gridtieneaccion]
                                   },
                                   {
                                        columnWidth:.10,
                                        layout: 'form',
                                        items: [btnSiguiente,btnAnterior],
                                        anchor:'100%'
                                   },
                                   {
                                        columnWidth:.45,
                                        layout: 'form',
                                        items: [gridnotieneaccion]
                                   }]
                        }]
        });
        
        panelRestAccion = new Ext.Panel({
            layout:'border',
            items:[regularAcciones,arbolRestAccion]    
        });

 		////---------- panel ----------////
		var panelAdicionar = new Ext.Panel({
			layout:'border',	
			items:[regrol,arbolSistema]
		});

		////---------- Cargar ventanas ----------////
		function winForm(opcion){
		switch(opcion){
			case 'Ins':{
				if(!winIns)
				{
					winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
												title:perfil.etiquetas.lbTitAdicionarRol,width:550,height:400,
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
																	handler:function(){adicionarRol('apl');}
																},
																
																{	
																	icon:perfil.dirImg+'aceptar.png',
																	iconCls:'btn',
																	text:perfil.etiquetas.lbBtnAceptar,
																	handler:function(){adicionarRol();}
																}
														]
											});
					winIns.on('show',function(){
						auxIns2 = false;
						auxMod2 = false;
						auxDel2 = false;
						auxReg2 = false;
						auxBus2 = false;
						auxAcept = true;
						auxApl = true;
					},this)
					winIns.on('hide',function(){
						auxIns2 = true;
						auxMod2 = true;
						auxDel2 = true;
						auxReg2 = true;
						auxBus2 = true;
						auxAcept = false;
						auxApl = false;
					},this)
				}
				arraytiene = [];
                arraysistemas = [];
                modificar=false;
                arbolSistema.getRootNode().reload(); 
				regrol.getForm().reset();
				winIns.add(panelAdicionar);
				winIns.doLayout();
				winIns.show();
				
			}break;
			case 'Mod':{
						if(!winMod)
						{
								winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
									title:perfil.etiquetas.lbTitModificarRol,width:550,height:400,
									buttons:[
												{
													icon:perfil.dirImg+'cancelar.png',
													iconCls:'btn',
													text:perfil.etiquetas.lbBtnCancelar,
													handler:function(){	winMod.hide();}
												},
												
												{	
													icon:perfil.dirImg+'aceptar.png',
													iconCls:'btn',
													text:perfil.etiquetas.lbBtnAceptar,
													handler:function(){modificarRol();}
												}
											]
										});
								winMod.on('show',function(){
									auxIns2 = false;
									auxMod2 = false;
									auxDel2 = false;
									auxReg2 = false;
									auxBus2 = false;
									auxAcept = true;
									auxApl = true;
								},this)
								winMod.on('hide',function(){
									auxIns2 = true;
									auxMod2 = true;
									auxDel2 = true;
									auxReg2 = true;
									auxBus2 = true;
									auxAcept = false;
									auxApl = false;
								},this)		
						}
						arraytiene = [];
                        arraysistemas = [];
                        modificar=true; 
                         arbolSistema.getRootNode().reload();                       								
						regrol.getForm().reset();
                        winMod.add(panelAdicionar);
                        winMod.doLayout();																	
						winMod.show();    
						regrol.getForm().loadRecord(sm.getSelected());	
				}break;
                case 'Reg':
                        {
                            if(!winRegular)
                            {
                                winRegular= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
                                    title:perfil.etiquetas.lbBtnRegularacciones,width:750,height:400,
                                    buttons:[
                                    {
                                        icon:perfil.dirImg+'cancelar.png',
                                        iconCls:'btn',
                                        text:perfil.etiquetas.lbBtnCancelar,
                                        handler:function(){winRegular.hide();}
                                    }]
                                });
                                winRegular.on('show',function(){
									auxIns2 = false;
									auxMod2 = false;
									auxDel2 = false;
									auxReg2 = false;
									auxBus2 = false;
								},this)
								winRegular.on('hide',function(){
									auxIns2 = true;
									auxMod2 = true;
									auxDel2 = true;
									auxReg2 = true;
									auxBus2 = true;
								},this)		
                            }                
                            winRegular.add(panelRestAccion);
                            winRegular.doLayout();
                            winRegular.show();
                            storeTieneAccion.removeAll();
                            storeNoTieneAccion.removeAll();
                            arbolRestAccion.getRootNode().reload();
                            arbolRestAccion.expandPath(arbolRestAccion.getRootNode().getPath());
                        }break;
			}
		 }
		
        ////---------- Adicionar Rol ----------////    
        function adicionarRol(apl)
		{    
			if (regrol.getForm().isValid())
			{       
                    var resultado = new Array();
                    var arrPadres = new Array();
					var arrayNodos = arbolSistema.getChecked();
					if(arrayNodos.length > 0)
					{
						for (var i=0; i<arrayNodos.length; i++)
						{						
                            if(!existesistema(arrPadres, arrayNodos[i].parentNode.attributes.idsistema))
                            {
                                var result = new Array();
                                arrPadres.push(arrayNodos[i].parentNode.attributes.idsistema);
                                result[0] = arrayNodos[i].parentNode.attributes.idsistema;
                                result[1] = dameFuncionalidades(arrayNodos[i].parentNode.attributes.idsistema, arrayNodos);                  
                                resultado.push(result);
                                damesistemas(arrayNodos[i]);          
                            }
                        }
						
							regrol.getForm().submit({
									url:'insertarrol',					
									params:{arraysistfun:Ext.encode(resultado),arraysist:Ext.encode(arraysistemas)},
									failure: function(form, action)
									{
									if(action.result.codMsg != 3)
										{
											mostrarMensaje(action.result.codMsg,action.result.mensaje); 
											if(!apl)
                                                winIns.hide();
											stGpRol.reload();
                                            arraytiene = [];
                                            arraysistemas = [];	
											regrol.getForm().reset();
											btnModificar.disable();
											btnEliminar.disable();
										}
									if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
									}
								});
					}
					else mostrarMensaje(3,'Debe seleccionar al menos una funcionalidad');
			}
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
	}
		////---------- Eliminar Rol ----------////
		function eliminarRol()
		{
        var arrRolesElim = sm.getSelections();
        var arrayRolesElim = [];
        for (var i=0;i<arrRolesElim.length;i++)
            arrayRolesElim.push(arrRolesElim[i].data.idrol);
        
		 mostrarMensaje(2,perfil.etiquetas.lbMsgEliminar,elimina);
			function elimina(btnPresionado){
						if (btnPresionado == 'ok')
						{
								Ext.Ajax.request({
									url: 'eliminarrol',
									method:'POST',
									params:{arrayRolesElim:Ext.encode(arrayRolesElim)},
									callback: function (options,success,response)
												{
														responseData = Ext.decode(response.responseText);
														if(responseData.codMsg == 1)
														{													
															mostrarMensaje(responseData.codMsg,responseData.mensaje);
															stGpRol.reload();
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
		
		////---------- Modificar Rol ----------////
		function modificarRol(apl)
		{
			if (regrol.getForm().isValid())
			{
                var resultado = new Array();
                var arrPadres = new Array();
                var arrayNodos = arbolSistema.getChecked();
                
                if(arrayNodos.length > 0)
                {
                    for (var i=0; i<arrayNodos.length; i++)
                    {                        
                        if(!existesistema(arrPadres, arrayNodos[i].parentNode.attributes.idsistema))
                        {
                            var result = new Array();
                            arrPadres.push(arrayNodos[i].parentNode.attributes.idsistema);
                            result[0] = arrayNodos[i].parentNode.attributes.idsistema;
                            result[1] = dameFuncionalidades(arrayNodos[i].parentNode.attributes.idsistema, arrayNodos);                  
                            resultado.push(result);
                            damesistemas(arrayNodos[i]);
                        }
                    }
                    arrayeliminar = sistemaEliminar();
                }
                else
                    arrayeliminar = arraytiene; 
                regrol.getForm().submit(
                {
				    url:'modificarrol',					
				    waitMsg:'Modificando rol...',
                    params:{arraysistfun:Ext.encode(resultado),arraysist:Ext.encode(arraysistemas),arrayeliminar:Ext.encode(arrayeliminar),idrol:sm.getSelected().data.idrol}, 																										
				    failure: function(form, action)
                    {
					    if(action.result.codMsg != 3)
						{
							mostrarMensaje(action.result.codMsg,action.result.mensaje); 
							if(!apl){
                                 winMod.hide();}
                                 
							resultado = {};	
							stGpRol.reload();
                            arraytiene = [];
                            arraysistemas = [];                                                           									
							btnModificar.disable();
							btnEliminar.disable();
						}
						if(action.result.codMsg == 3) 
                            mostrarMensaje(action.result.codMsg,action.result.mensaje);
				    }
		        });
			}
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);            
		}
		
        function damesistemas(nodo)
        {
            if(nodo.parentNode && nodo.parentNode.attributes.idsistema && !existesistema(arraysistemas,nodo.parentNode.attributes.idsistema))
                arraysistemas.push(nodo.parentNode.attributes.idsistema);
            if(nodo.parentNode) 
                damesistemas(nodo.parentNode);
            return 0;
        }

        function dameFuncionalidades(idPadre, array)
        {
            var resultado = new Array();
            for (var i=0; i<array.length; i++)
            {
                if(array[i].parentNode.attributes.idsistema == idPadre)
                    resultado.push(array[i].attributes.idfuncionalidad);        
            }
            return resultado; 
        }
        
		function existesistema(arraysistemas,sistema)
		{
			for (var f=0; f<arraysistemas.length; f++)
			{	
				if(arraysistemas[f] == sistema)														
				    return true;
			}
			return false;
		}
        
        ////------------ Adicionar Acciones ------------////
        function adicionaraccion()
        {
            var arrAccs = smnotieneaccion.getSelections();
            var arrAcc = [];
            for (var i=0;i<arrAccs.length;i++)
                arrAcc.push(arrAccs[i].data.idaccion);
        
            if (regularAcciones.getForm().isValid())
              {  
                  regularAcciones.getForm().submit({
                url:'adicionaraccion',     
                waitMsg:'Adicionando acci&oacute;n...',                          
                params:{idsistema:idi,idfuncionalidad:idu,idrol:idr,idaccion:Ext.encode(arrAcc)},
                  failure: function(form, action)
                  {
                       if(action.result.tiene==1)
                    {
                         storeTieneAccion.reload();
                         storeNoTieneAccion.reload();
                      }
                      if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                 }
                });
            }
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
        }

        ////------------ Eliminar Acciones ------------////
        function eliminaraccion()
        {  
            var arrAccs = smtieneaccion.getSelections();
            var arrAcc = [];
            for (var i=0;i<arrAccs.length;i++)
                arrAcc.push(arrAccs[i].data.idaccion);
                
            if (regularAcciones.getForm().isValid())
            {
              regularAcciones.getForm().submit({
              url:'eliminaraccion',     
              waitMsg:'Eliminando acci&oacute;n...',                          
              params:{idsistema:idi,idfuncionalidad:idu,idrol:idr,idaccion:Ext.encode(arrAcc)},      
              failure: function(form, action)
                  {
                    if(action.result.tiene==1)
                    {
                    storeTieneAccion.reload();
                    storeNoTieneAccion.reload();   
                    }
                    if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
                  }
                  });
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
        
        ////------------ obtener sistemas a eliminar ------------//// 
        function sistemaEliminar()
        {
            arrayeliminar = [];
            for(i=0;i<arraytiene.length;i++)
            {
                bandera = false;
                for(j=0; j<arraysistemas.length;j++)
                {
                    if(arraytiene[i] == arraysistemas[j])
                    {
                       bandera=true;
                       break;
                    }
                }
                if(!bandera)
                    arrayeliminar.push(arraytiene[i]);
            }
            return arrayeliminar;    
        }
		
        ////------------ buscar roles por denominacion ------------////
       function buscarnombrerol(denrol)
        {
        stGpRol.baseParams.denrol = denrol;
        stGpRol.load({params:{start:0,limit:15}});
        } 
	}	
	
