		var perfil = window.parent.UCID.portal.perfil;
		
		perfil.etiquetas=Object();
		UCID.portal.cargarEtiquetas('gestserviciocons',cargarInterfaz);
		
		// 3. Inicializo el singlenton QuickTips
		Ext.QuickTips.init();
		
		
		////------------ Declarar variables ------------////
		var sistema, nodeFlag;
		var idsistema;
		var winIns;
		var auxIns = false;
		var auxDel = false;
		var auxIns2 = false;
		var auxDel2 = false;
		var auxIns3 = true;
		var auxDel3 = true;
		
		function cargarInterfaz(){
			btnAdicionar = new Ext.Button({disabled:true,id:'btnAgrServCons', hidden:true, icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');} });
			btnEliminar = new Ext.Button({disabled:true,id:'btnEliServCons', hidden:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar , handler:function(){eliminarServicio();}});
			/*btnAyuda = new Ext.Button({id:'btnAyuServCons', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
		    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
			////------------ Arbol de sistemas ------------////
			arbolSistema = new Ext.tree.TreePanel({
					title:perfil.etiquetas.lbTitArbolSistemas,
					collapsible:true,
					autoScroll:true,
					region:'west',
					split:true,
					width:'30%',
					margins:'2 0 2 2',
					loader: new Ext.tree.TreeLoader({
						dataUrl:'../gestsistema/cargarsistema'
					})
			});
				
			////------------ Crear nodo padre del arbol ------------////
			padreSistema = new Ext.tree.AsyncTreeNode({
			        text: perfil.etiquetas.lbTitRootNodeArbolSubsist,
					expandable:false,
					expanded:true,
					id:'0'
			});
					
			////------------ Habilitar botones y cargar id de Nodos ------------////
			arbolSistema.setRootNode(padreSistema);	
			
			////------------ Evento para habilitar botones ------------////
			arbolSistema.on('click', function (node, e){
				sistema = node.id;
				idsistema = node.id;
				btnEliminar.disable();
				btnAdicionar.disable();
				storeGrid.removeAll();
				if (node.isLeaf())
				{
					grid.enable();
                                        storeGrid.removeAll();
					storeGrid.load({params:{start:0,limit:15,idsistema:node.id}});
					storeConsSrv.removeAll();
                                        nodeFlag = node.id;
					storeConsSrv.load({params:{idsistema:node.id}});
					btnAdicionar.enable();
					auxIns = true;
					auxDel = true;
				}
				else
				{
					auxDel = false;
					auxIns = false;
				}
                                if(node.id == 0)
                                    grid.disable();
			}, this);
		
			////------------ Store del Grid ------------////                                             
			var storeGrid =  new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({
						url: 'cargarserviciocons'
					}),
					listeners:{'beforeload':function(thisstore, objeto){objeto.params.idsistema=sistema}},
					reader:new Ext.data.JsonReader({
					totalProperty: "cantidad_filas",
					root: "datos",
					id: "ide"
				},
				       [
				        	{name:'idservicio',mapping:'idservicio'},
							{name:'denominacion',mapping:'denominacion'},
							{name:'descripcion',mapping:'descripcion'},
							{name:'wsdl',mapping:'wsdl'},
							{name:'idsistema',mapping:'idsistema'}
					   ])
			});
			
			////------------ Modo seleccion del Grid ------------////
			var sm = new Ext.grid.RowSelectionModel({singleSelect:true});
			
			sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
									btnEliminar.enable();
								}, this);				
			////----------- Grid ------------////
			var grid = new Ext.grid.GridPanel({ 
			  title:perfil.etiquetas.lbTitServiciosConsume,   
				region:'center',
				frame:true,
                                disabled:true,
				autoExpandColumn:'expandir',
				margins:'2 2 2 -4',
				store:storeGrid,
				sm:sm,
				columns:[
					    	{hidden: true, hideable: false, dataIndex: 'idservicio'},
							{hidden: true, hideable: false, dataIndex: 'idsistema'},
							{header: perfil.etiquetas.lbDenominacion, width:150, dataIndex: 'denominacion'},
							{header: perfil.etiquetas.lbDescripcion, width:150, dataIndex: 'descripcion'},
							{header: perfil.etiquetas.lbWSDL, width:200, dataIndex: 'wsdl', id:'expandir'}
						],
				loadMask:{store:storeGrid},
				
				bbar:new Ext.PagingToolbar({
		            pageSize: 15,
		            id:'ptbaux',
		            store: storeGrid,
		            displayInfo: true,
		            displayMsg:perfil.etiquetas.lbMsgPaginado,
		            emptyMsg: perfil.etiquetas.lbMsgEmpty
				})
			});
			////------------ Trabajo con el PagingToolbar ------------////
			Ext.getCmp('ptbaux').on('change',function(){
				sm.selectFirstRow();
			},this)	
			
			////------------ Panel con los componentes ------------////
			var panel = new Ext.Panel({
			title:perfil.etiquetas.lbTitGestServCons,
				layout:'border',
				renderTo:'panel',
				items:[grid, arbolSistema],
				tbar:[btnAdicionar,btnEliminar/*,btnAyuda*/],
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
		    		  	}])
			});
			////---------- Eventos para hotkeys ----------////
			btnAdicionar.on('show',function(){
				auxIns2 = true;
			},this)
			btnEliminar.on('show',function(){
				auxDel2 = true;
			},this)
			storeGrid.on('load',function(){
				if(storeGrid.getCount() != 0)
					auxDel = true;
				else
					auxDel = false;
			},this)
			////------------ Viewport ------------////
			var vpGestSistema = new Ext.Viewport({
				layout:'fit',
				items:panel
			})
			 
			var storeConsSrv =  new Ext.data.Store({
				url: 'cargarservicionocons',	
				reader:new Ext.data.JsonReader({
				id:'idgrupos'
					},
					[
						{name: 'idservicio', mapping:'idservicio'},
						{name:'denominacion', mapping:'denominacion'}
					])
			});
			
			////------------ Registrar Accion ------------////
			var formConsServ = new Ext.FormPanel({
					labelAlign: 'top',
					frame:true,
					bodyStyle:'padding:5px 5px 0',
					items: [{
							layout:'column',
							items:[{
										columnWidth:1,
										layout:'form',
										items:[new Ext.form.ComboBox({
														emptyText:perfil.etiquetas.lbMsgBlankTextServ,
														editable:false,
							                       	 	fieldLabel:perfil.etiquetas.lbServCons,
							                     		store:storeConsSrv,
							                     		valueField:'idservicio',
							                        	displayField:'denominacion',
							                        	hiddenName:'idservicio',
														forceSelection:true,
							                        	typeAhead: true,
							                        	mode: 'local',
							                        	allowBlank:false,
							                        	triggerAction: 'all',                        
							                        	selectOnFocus:true,
							                        	anchor:'100%'
			                     })]
							}]
					}]
			});
			
			////------------ Cargar ventanas ------------////
			function winForm(opcion){
				switch(opcion){
					case 'Ins':{
						if(!winIns)
						{
							winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
								title:perfil.etiquetas.lbTitAdicionarServCons,width:500,height:150,
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
									handler:function(){adicionarServicio('apl');}
								},
								{
									icon:perfil.dirImg+'aceptar.png',
									iconCls:'btn',
									text:perfil.etiquetas.lbBtnAceptar,
									handler:function(){adicionarServicio();}
								}]
							});
							winIns.on('show',function(){
									auxIns3 = false;
									auxDel3 = false;
								},this)
								winIns.on('hide',function(){
									auxIns3 = true;
									auxDel3 = true;
                                                                        formConsServ.getForm().reset();
								},this)
						}
						winIns.add(formConsServ);
						winIns.doLayout();
						winIns.show();
					}break;
				}
			}
			
			////------------ Funcion Adicionar ------------////
			function adicionarServicio(apl){
				if (formConsServ.getForm().isValid())
				{
					formConsServ.getForm().submit({
						url:'insertarserviciocons',
						waitMsg:perfil.etiquetas.lbMsgRegServCons,
						params:{idsistema:idsistema},
						failure: function(form, action){
							if(action.result.codMsg != 3)
							{
								mostrarMensaje(action.result.codMsg,action.result.mensaje); 
								formConsServ.getForm().reset(); 
								if(!apl)					
								winIns.hide();
								storeGrid.reload();
                                                                storeConsSrv.load({params:{idsistema:nodeFlag}});
								sm.clearSelections();
								//btnModificar.disable();
								btnEliminar.disable();
							}
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
						}
					});
				}
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
			}
			
			////------------ Funcion eliminar ------------////
			function eliminarServicio(){
				mostrarMensaje(2,perfil.etiquetas.lbMsgDeseaEliminar,elimina);
				function elimina(btnPresionado){
					if (btnPresionado == 'ok')
					{
						Ext.Ajax.request({
							url: 'eliminarserviciocons',
							method:'POST',
							params:{idsistema:idsistema,idservicio:sm.getSelected().data.idservicio},
							callback: function (options,success,response){
								responseData = Ext.decode(response.responseText);
								if(responseData.codMsg == 1)
								{
									mostrarMensaje(responseData.codMsg,responseData.mensaje);
									storeGrid.reload();
                                                                        storeConsSrv.load({params:{idsistema:nodeFlag}});
									sm.clearSelections();
									btnEliminar.disable();
									auxIns = false;
									auxDel = false;
								}
								if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
							}
						});
					}
				}
			}
			
	}