		var perfil = window.parent.UCID.portal.perfil;
		perfil.etiquetas = Object();
		UCID.portal.cargarEtiquetas('gestgestor',cargarInterfaz);
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
			
		////------------ Declarar Variables ------------////
		var winIns, winMod, idservidor,nodoPadreBandera;
		var auxIns = false;
		var auxDel = false;
		var auxIns2 = true;
		var auxDel2 = true;
		var auxDel1 = false;
		function cargarInterfaz(){
		////------------ Botones ------------////
		btnAdicionar = new Ext.Button({disabled:true, id:'btnAgrgestorBd', hidden:true, icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');}  });
		btnEliminar = new Ext.Button({disabled:true, id:'btnEligestorBd', hidden:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn',text: perfil.etiquetas.lbBtnEliminar,handler:function(){eliminargestor();}});
		/*btnAyuda = new Ext.Button({id:'btnAyugestorBd', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda});*/
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        
		////------------ Arbol Servidores-Gestores ------------////
		arbolGestor = new Ext.tree.TreePanel({
			title:perfil.etiquetas.lbTitReg,
			collapsible:true,
			autoScroll:true,
			region:'west',
			split:true,
			width:'37%',
			loader: new Ext.tree.TreeLoader({
				dataUrl:'cargarservidores'
		})
    	});
    	
    	////------------ Crear nodo padre del arbol ------------////
   		padreArbolGestor = new Ext.tree.AsyncTreeNode({
          text: perfil.etiquetas.lbTit,
		  expandable:false,
		  expanded:true,
		  id:'0'
        });
		arbolGestor.setRootNode(padreArbolGestor);
		
		////--------------- Evento para habilitar botones -------------////
		arbolGestor.on('click', function (node, e){
			btnEliminar.disable();
			btnAdicionar.disable();
			stGpGestor.removeAll();
			if (node.isLeaf())
			{
				gpGestor.enable();
                                stGpGestor.removeAll();
				idservidor=node.id;
				stGpGestor.load({params:{start:0,limit:15}});
				btnAdicionar.enable();
				btnEliminar.disable();
				auxIns = true;
				auxDel1 = true;
			}
			else
			{
				auxDel1 = false;
				auxIns = false;
			}
                        if(node.id == 0)
                            gpGestor.disable();
                            

		}, this);
		
		////------------- Store del Grid de Gestores -------------- ////
	    stGpGestor =  new Ext.data.Store({
			url: 'cargargestores',
			listeners:{'beforeload':function(thisstore,objeto){objeto.params.idservidor=idservidor}},
			reader:new Ext.data.JsonReader(
				{
					totalProperty: "cantidad_filas",
					root: "datos",
					id: "id"
				},
				[
					{name:'idgestor',mapping:'idgestor'},
			 		{name:'gestor',mapping:'gestor'},
			 		{name:'puerto',mapping:'puerto'},
			 		{name:'descripcion',mapping:'descripcion'}
				]
			)
		});
		
		////------------ Establesco modo de seleccion de grid (single) ---------////
		var sm = new Ext.grid.RowSelectionModel({singleSelect:true});
		sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
					btnEliminar.enable();
				}, this);
		////---------- Defino el grid de gestores ----------////
		var gpGestor = new Ext.grid.GridPanel({
			frame:true,
			region:'center',
			iconCls:'icon-grid',
			autoExpandColumn:'expandir',
			store:stGpGestor,
			sm:sm,
                        disabled:true,
			columns: [
						{ hidden: true, hideable: false,  dataIndex: 'idgestor'},
						{ header: perfil.etiquetas.lbGestor, width:150, dataIndex: 'gestor'},
						{ header: perfil.etiquetas.lbPuerto, dataIndex: 'puerto'},
						{ id:'expandir',header: perfil.etiquetas.lbDes,width:300, dataIndex: 'descripcion'}
	 				 ],

			loadMask:{store:stGpGestor},		
	 		bbar:new Ext.PagingToolbar({
				pageSize: 15,
				id:'ptbaux',
				store: stGpGestor,
				displayInfo: true,
				displayMsg: 'Resultados {0} - {1} de {2}',
				emptyMsg: "Ning&uacute;n resultado para mostrar"
			})
		});
		////------------ Trabajo con el PagingToolbar ------------////
		Ext.getCmp('ptbaux').on('change',function(){
			sm.selectFirstRow();
		},this)
		////------------- modo de seleccion del combo ------------------////
		//var cms = new Ext.grid.RowSelectionModel({singleSelect:true});
		
		////------------------ Store del combobox de expresiones -----------------////	
		var storeGestor =  new Ext.data.Store({
			url: 'cargarcombogestores',
			
			reader:new Ext.data.JsonReader(
			{
				id:'id'
			},
			[
				{name: 'idgestor', mapping:'idgestor'},
				{name:'gestor', mapping:'gestor'}
			])
		});
		
		////------------- Renderiar el Arbol ----------------////
		var panel = new Ext.Panel({
			layout:'border',
			title:perfil.etiquetas.lbTitTitulo,
			items:[gpGestor,arbolGestor],
			tbar:[btnAdicionar,btnEliminar/*,btnAyuda*/],
			keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    				if(auxDel && auxDel1 && auxDel2)
		    					eliminargestor();}
		    		  },
		    		  {
		    		  	key:"i",
		    			alt:true,
		    			fn: function(){
		    				if(auxIns && auxIns2)
		    					winForm('Ins');}
		    		  }])
		});
		////---------- Eventos para hotkeys ----------////
		btnAdicionar.on('show',function(){
			auxIns = true;
		},this)
		btnEliminar.on('show',function(){
			auxDel = true;
		},this)
		stGpGestor.on('load',function(){
			if(stGpGestor.getCount() != 0)
				auxDel1 = true;
			else
				auxDel1 = false;
		},this)

		var vpGestGestor = new Ext.Viewport({
			layout:'fit',
			items:panel
		})

		////------------- Formulario --------------////
		var regGestor = new Ext.FormPanel({
			labelAlign: 'top',
			frame:true,
			bodyStyle:'padding:5px 5px 0',
			items: [{
					layout:'column',
					items:[{
							columnWidth:.8,
							layout:'form',
							items:[new Ext.form.ComboBox({
											emptyText:perfil.etiquetas.lbMsgSelect,
											editable:false,
				                        	fieldLabel:perfil.etiquetas.lbMsgNombreG,
				                     		store:storeGestor,
				                     		valueField:'idgestor',
				                        	displayField:'gestor',
				                        	hiddenName:'idgestor',
											forceSelection:true,
				                        	typeAhead: true,
				                        	mode: 'local',
				                        	allowBlank:false,
				                        	triggerAction: 'all',                        
				                        	selectOnFocus:true,
				                        	anchor:'90%'
                     		})]
					}]
			}]
		});
        
		////------------- Cargar la Ventana ---------------////
		function winForm(opcion){
			switch(opcion){
				case 'Ins':
				{
					if(!winIns){
							winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
								title:perfil.etiquetas.lbTitAdicionarG,width:400,height:150,
								buttons:[
											 {
												 icon:perfil.dirImg+'cancelar.png',
												 iconCls:'btn',
												 text:perfil.etiquetas.lbBtnCancelar,
												 handler:function(){winIns.hide();}
											 },{	
											 	 icon:perfil.dirImg+'aplicar.png',
											 	 iconCls:'btn',
											 	 text:perfil.etiquetas.lbBtnAplicar,
											 	 handler:function(){adicionargestor('apl');}
											 },{	
											 	 icon:perfil.dirImg+'aceptar.png',
											 	 iconCls:'btn',
											 	 text:perfil.etiquetas.lbBtnAceptar,
											 	 handler:function(){adicionargestor();}
									 }]
							});
							winIns.on('show',function(){
								auxIns2 = false;
								auxDel2 = false;
							},this)
							winIns.on('hide',function(){
								auxIns2 = true;
								auxDel2 = true;
							},this)
				   }
				   storeGestor.load({params:{idservidor:arbolGestor.getSelectionModel().getSelectedNode().attributes.id}});
				   regGestor.getForm().reset();
				   winIns.add(regGestor);
				   winIns.doLayout();
				   winIns.show();
				   
				   
			 }break;
			}
		}
       
    	////------------------- Adicionar Gestor ------------------------////
		function adicionargestor(apl){
			if (regGestor.getForm().isValid()){
				regGestor.getForm().submit({
				url:'insertargestorservidor',
				waitMsg:perfil.etiquetas.lbMsgAdicionandoG,
				params:{idservidor:arbolGestor.getSelectionModel().getSelectedNode().attributes.id},
				failure: function(form, action){
								if(action.result.codMsg != 3)
								{
										mostrarMensaje(action.result.codMsg,action.result.mensaje); 
										
										
										if(!apl) 
											winIns.hide();
											
										regGestor.getForm().reset(); 
										storeGestor.load({params:{idservidor:arbolGestor.getSelectionModel().getSelectedNode().attributes.id}});	
										stGpGestor.reload();
										sm.clearSelections();
										btnEliminar.disable();
								}
								
								if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
							}	
				});
			}
            else
              mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);           
		}
		
		////------------------- Eliminar Gestor ------------------------////
		function elimina1(btnPresionado)
		{
    		if (btnPresionado == 'ok')
    		{
        		Ext.Ajax.request({
                url: 'eliminargestores',
                method:'POST',
                params:{idservidor:arbolGestor.getSelectionModel().getSelectedNode().attributes.id,idgestor:sm.getSelected().data.idgestor},
                callback: function (options,success,response){
                responseData = Ext.decode(response.responseText);
                        if(responseData.codMsg == 1)
                        {
                       		mostrarMensaje(responseData.codMsg,responseData.mensaje);
                       		stGpGestor.reload();
							sm.clearSelections();
							btnEliminar.disable();
                        }
                        if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
                  }
            	});
   			}
    	} 
	
		function eliminargestor(){
			mostrarMensaje(2,perfil.etiquetas.lbMsgEliminarG,elimina);
			function elimina(btnPresionado)
			{
				if (btnPresionado == 'ok')
				{
					Ext.Ajax.request({
					url: 'comprobargestores',
					method:'POST',
					params:{idservidor:arbolGestor.getSelectionModel().getSelectedNode().attributes.id,idgestor:sm.getSelected().data.idgestor},
					callback: function (options,success,response){
					responseData = Ext.decode(response.responseText);
						if(responseData.tiene == 1)
						mostrarMensaje(2,perfil.etiquetas.lbMsgEliminarGA,elimina1)
						if(responseData.codMsg == 1)
						{
							mostrarMensaje(responseData.codMsg,responseData.mensaje);
							stGpGestor.reload();
							sm.clearSelections();
							btnEliminar.disable();
						}
						if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
						}
					});
				}
			}
		}
 }



