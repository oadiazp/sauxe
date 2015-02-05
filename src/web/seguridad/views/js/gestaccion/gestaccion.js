	var perfil = window.parent.UCID.portal.perfil;
	perfil.etiquetas = Object();
	UCID.portal.cargarEtiquetas('gestaccion',cargarInterfaz);
	
	////------------ Inicializo el singlenton QuickTips ------------////
	Ext.QuickTips.init();
		
	////------------ Declarar Variables ------------////
	var winIns, winMod, idfuncionalidad;
	var auxIns = false;
	var auxMod = false;
	var auxDel = false;
	var auxIns2 = false;
	var auxMod2 = false;
	var auxDel2 = false;
	var auxIns3 = true;
	var auxMod3 = true;
	var auxDel3 = true;
	var auxBus3 = true;
	var auxBus = false;
	var auxDelete = true;
	
	////------------ Area de Validaciones ------------////
	var tipos;
	tipos =  /(^([a-zA-ZáéíóúñüÑ])+([a-zA-ZáéíóúñüÑ\d\.\-\@\#\_\s]*))$/;
	function cargarInterfaz(){
	////------------ Botones ------------////
	btnAdicionar = new Ext.Button({disabled:true,id:'btnAgrAccion', hidden:true, icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');}});
	btnModificar = new Ext.Button({disabled:true,id:'btnModAccion', hidden:true, icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnModificar,handler:function(){winForm('Mod');} });
	btnEliminar = new Ext.Button({disabled:true,id:'btnEliAccion', hidden:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar, handler:function(){eliminarAccion();}  });
	/*btnAyuda = new Ext.Button({id:'btnAyuAccion', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
    btnBuscar = new Ext.Button({disabled:true,icon:perfil.dirImg+'buscar.png',iconCls:'btn',text:perfil.etiquetas.lbBtnBuscar, handler:function(){buscaraccion(accion.getValue());}}) 
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
	////------------ Arbol Acciones ------------////
	arbolAcc = new Ext.tree.TreePanel({
		title:perfil.etiquetas.lbTitArbolSistemas,
		collapsible:true,
		autoScroll:true,
		region:'west',
		split:true,
		width:'37%',
		loader: new Ext.tree.TreeLoader({
			dataUrl:'cargarsistfunc',
			listeners:{'beforeload':function(atreeloader, anode){ 
							atreeloader.baseParams = {};
							if(anode.attributes.idsistema)			
								atreeloader.baseParams.idsistema = anode.attributes.idsistema												
						}
					}
				
		})
	});
	////------------ Crear nodo padre del arbol ------------////
	padreArbolAcc = new Ext.tree.AsyncTreeNode({
	      text:perfil.etiquetas.lbRootNodeArbolSubsist,
		  expandable:false,
		  expanded:true,
		  id:'0'
	      });
	      
	arbolAcc.setRootNode(padreArbolAcc);
	
	////------------ Evento para habilitar botones ------------////
	arbolAcc.on('click', function (node, e){
		btnModificar.disable();
		btnEliminar.disable();
		btnAdicionar.disable();
                btnBuscar.disable();
		storeGrid.removeAll();
		if (node.isLeaf())
		{
                    grid.enable();
                    idfuncionalidad = node.attributes.idfuncionalidad;
                    storeGrid.removeAll();
                    storeGrid.load({params:{start:0,limit:15}});
                    btnAdicionar.enable();
                    btnBuscar.enable();
                    auxIns = true;
                    auxBus = true;
		}
		else
		{
			
			auxDel = false;
			auxBus = false;
			auxIns = false;
			auxMod = false;
		}
                if(node.id == 0)
                {
                    storeGrid.removeAll();
                    grid.disable();
                }
	}, this);
	
	////------------ Store del grid Accion ------------////
	var storeGrid =  new Ext.data.Store({
		url: 'cargargridacciones',
        listeners:{'beforeload':function(thisstore,objeto){
            objeto.params.idfuncionalidad = idfuncionalidad
                }},
            
			reader:new Ext.data.JsonReader({
				totalProperty: "cantidad_filas",
				root: "datos",
				id: "id"
				},
				[
					{name:'idaccion', mapping:'idaccion'},
					{name:'abreviatura', mapping:'abreviatura'},
					{name:'denominacion', mapping:'denominacion'},
					{name:'descripcion', mapping:'descripcion'},
					{name:'icono', mapping:'icono'}
				])
	});
	
	////------------ MODO DE SELECCION; SI singleSelect = true=> INDICA SELECCION SIMPLE ------------////
	var sm = new Ext.grid.RowSelectionModel({singleSelect:true});
	
	////------------ Para habilitar boton de modificar y eliminar ------------////
	sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record ){
				btnModificar.enable();
				btnEliminar.enable();
		}, this);
	////------------ Grid de Acciones ------------////
	var grid = new Ext.grid.GridPanel({ 
		text:perfil.etiquetas.lbMsgGridAcc,   
		region:'center',
		frame:true,
		width:'40%',
                disabled:true,
		iconCls:'icon-grid',  
		margins:'2 2 2 -4',
		autoExpandColumn:'expandir',
		store:storeGrid,
		sm:sm,
		columns: [
					{hidden:true, hideable: false, dataIndex: 'idaccion'},
					{id:'expandir',header:perfil.etiquetas.lbCampoDenom, width:200, dataIndex: 'denominacion'},
					{header: perfil.etiquetas.lbCampoAbreviatura, width:150, dataIndex: 'abreviatura'},
					{header: perfil.etiquetas.lbDescripcion, width:200, dataIndex: 'descripcion'},
					{hidden:true,header: perfil.etiquetas.lbIcono, width:150, dataIndex: 'icono'}
		 		 ],
		loadMask:{store:storeGrid},
		
		tbar:[
				new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbAccion}),
				accion = new Ext.form.TextField({width:150, id: 'nombreaccion'}),
				new Ext.menu.Separator(),			
				btnBuscar
			 ],
		
		 bbar:new Ext.PagingToolbar({
	            pageSize: 15,
	            id:'ptbaux',
	            store: storeGrid,
	            displayInfo: true,
	            displayMsg:  perfil.etiquetas.lbMsgPaginado,
	            emptyMsg: perfil.etiquetas.lbMsgEmpty
		})
	});
	////------------ Trabajo con el PagingToolbar ------------////
	Ext.getCmp('ptbaux').on('change',function(){
		sm.selectFirstRow();
	},this)	
	
	////------------ Panel ------------////
	var panel = new Ext.Panel({
		layout:'border',
		title:perfil.etiquetas.lbTitGestAccion,
		items:[arbolAcc,grid],
		tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
		keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    			if(auxDel && auxDelete && auxDel2 && auxDel3)
		    				eliminarAccion();
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
		    					buscaraccion(Ext.getCmp('nombreaccion').getValue());
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
	Ext.getCmp('nombreaccion').on('focus',function(){
		auxDelete = false;
	},this)
	Ext.getCmp('nombreaccion').on('blur',function(){
		auxDelete = true;
	},this)
	storeGrid.on('load',function(){
		if(storeGrid.getCount() != 0)
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
	////------------ Otros eventos ------------////
	////------------ ViewPort ------------////
	var vpGestSistema = new Ext.Viewport({
		layout:'fit',
		items:panel
	})
	
	////------------ Formulario ------------////
	var regAccion = new Ext.FormPanel({
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
								fieldLabel:perfil.etiquetas.lbCampoDenom,
								id:'denominacion',
                                maxLength:30,
								allowBlank:false,
					            blankText:perfil.etiquetas.lbMsgBlankTextDenom,
					            regex:tipos,
								regexText:perfil.etiquetas.lbMsgExpRegDenom,
								anchor:'95%'
							  }]
					   },
					   {
							columnWidth:.5,
							layout: 'form',
							items: [{
									xtype:'textfield',
									fieldLabel: perfil.etiquetas.lbCampoAbreviatura,
									id:'abreviatura',
                                    maxLength:30,
									allowBlank:false,
					            	blankText:perfil.etiquetas.lbMsgBlankTextDenom,
					            	regex:tipos,
									regexText:perfil.etiquetas.lbMsgExpRegDenom,
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
	
	////------------ Cargar Ventanas ------------////
	function winForm(opcion){
		switch(opcion){
			case 'Ins':{
				if(!winIns)
				{
					winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						title:perfil.etiquetas.lbTitAdicionarAcc,width:400,height:230,
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
							handler:function(){adicionarAccion('apl');}
						},
						{	
							icon:perfil.dirImg+'aceptar.png',
							iconCls:'btn',
							text:perfil.etiquetas.lbBtnAceptar,
							handler:function(){adicionarAccion();}
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
				winIns.add(regAccion);
                regAccion.getForm().reset();
				winIns.doLayout();
				winIns.show();
			}break;
			case 'Mod':{
				if(!winMod)
				{
					winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						title:perfil.etiquetas.lbTitModificarAcc,width:400,height:230,
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
							handler:function(){modificarAccion();}
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
				winMod.add(regAccion);
                winMod.doLayout();
				winMod.show();
                regAccion.getForm().loadRecord(sm.getSelected());
			}break;
		}
	}
	//}
		
	////------------ Adicionar Aciones ------------////
	function adicionarAccion(apl){
		if (regAccion.getForm().isValid()){
			regAccion.getForm().submit({
				url:'insertaraccion',
				waitMsg:perfil.etiquetas.lbMsgEsperaInsAcc,  
				params:{idfuncionalidad:arbolAcc.getSelectionModel().getSelectedNode().attributes.idfuncionalidad,idsistema:arbolAcc.getSelectionModel().getSelectedNode().parentNode.attributes.idsistema },
				failure: function(form, action){
					if(action.result.codMsg != 3){
						mostrarMensaje(action.result.codMsg,action.result.mensaje); 
						regAccion.getForm().reset(); 
						
						if(!apl) 
						winIns.hide();
						
						storeGrid.reload();
						sm.clearSelections();
						btnModificar.disable();
						btnEliminar.disable();
					}
					if(action.result.codMsg == 3){
					mostrarMensaje(action.result.codMsg,action.result.mensaje);
					}
					
				}
			});
		}
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);        
	}
	
	////------------ Modififcar Accion ------------////
	function modificarAccion(){
		if (regAccion.getForm().isValid())
		{
			regAccion.getForm().submit({
				url:'modificaraccion',
				waitMsg:perfil.etiquetas.lbMsgEsperaModAcc, 
				params:{idaccion:sm.getSelected().data.idaccion,idfuncionalidad:arbolAcc.getSelectionModel().getSelectedNode().attributes.idfuncionalidad},
				failure: function(form, action){
					if(action.result.codMsg != 3)
					{
						mostrarMensaje(action.result.codMsg,action.result.mensaje); 
						storeGrid.reload();
						winMod.hide();
					}
					if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
					
				}
			});
		}
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);        
	}
	
	////------------ Eliminar Accion ------------////
	function eliminarAccion(){
		mostrarMensaje(2,perfil.etiquetas.lbMsgDeseaEliminar,elimina);
		function elimina(btnPresionado){
			if (btnPresionado == 'ok')
			{
				Ext.Ajax.request({
					url: 'eliminaraccion',
					method:'POST',
					params:{idaccion:sm.getSelected().data.idaccion},
					callback: function (options,success,response){
							responseData = Ext.decode(response.responseText);
							if(responseData.codMsg == 1)
							{
								mostrarMensaje(responseData.codMsg,responseData.mensaje);
								storeGrid.reload();
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
	
	////------------ Buscar Accion ------------////
	    function buscaraccion(accion){  
		    storeGrid.load({params:{denominacion:accion,idfuncionalidad:arbolAcc.getSelectionModel().getSelectedNode().attributes.idfuncionalidad,start:0,limit:15}});
	    }
  }