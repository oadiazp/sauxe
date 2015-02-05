		var perfil = window.parent.UCID.portal.perfil;
        perfil.etiquetas = Object(); 
		UCID.portal.cargarEtiquetas('gestsistema', cargarInterfaz);
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
		////------------ Declarar variables ------------////
		var winIns,nodoarbol, winPass, fpPass, nodeGestor, nodeArbolConexSelect, winCon, winMod,winCamb,winExp,winImp,btnBuscar,idsistema,regEsquemas,fpbuscar,sistemas,winEs,panelAdicionar,arbolSistema,arbolConex,arbolLoaderConex,sistemaseleccionado, idpadre, fpCon, fpRegSistema,nodoSeleccionado, primernodobd,bandera =0;
		var arregloDeschequeados = [];
		var auxIns = false;
		var auxMod = false;
		var auxDel = false;
		var auxImp = false;
		var auxExp = false;	
		var auxIns2 = false;
		var auxMod2 = false;
		var auxDel2 = false;
		var auxImp2 = false;
		var auxExp2 = false;
		var auxIns3 = true;
		var auxMod3 = true;
		var auxDel3 = true;
		var auxImp3 = true;
		var auxExp3 = true;
		////------------ Area de Expresiones para validaciones ------------////
		var deschekear=0, cambiar = true, modificar = 0;
		tipos = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\-\@\#\_\s]*))$/;     
		esDirIp =  /(^(2([0-4][0-9])|2(5[0-5]))|^([0-1]?[0-9]?[0-9]))\.(((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))\.){2}((2([0-4][0-9])|2(5[0-5]))|([0-1]?[0-9]?[0-9]))$/ ;
        
		////------------ Funcion para cargar la interfaz ------------////
		function cargarInterfaz(){ 		
		////------------ Botones principales ------------////
		btnAdicionar = new Ext.Button({disabled:true, id:'btnAgrSist', hidden:true, icon:perfil.dirImg+'adicionar.png', iconCls:'btn', iconCls:'btn', text:'Adicionar', handler:function(){winForm('Ins');} });
		btnModificar = new Ext.Button({disabled:true, id:'btnModSist', hidden:true, icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:'Modificar', handler:function(){winForm('Mod');} });
		btnEliminar = new Ext.Button({disabled:true, id:'btnEliSist', hidden:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:'Eliminar',handler:function(){eliminarSistema();} });
		btnExportar = new Ext.Button({disabled:true, id:'btnExpSist', hidden:true, icon:perfil.dirImg+'exportar.png', iconCls:'btn', text:'Exportar',handler:function(){exportarSistema();} });
		btnImportar = new Ext.Button({disabled:true, id:'btnImpSist', hidden:true, icon:perfil.dirImg+'importar.png', iconCls:'btn', text:'Importar',handler:function(){winForm('Imp');} });
		/*btnAyuda = new Ext.Button({id:'btnAyuSist', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:'Ayuda' });*/		
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
		
		
		////------------ Arbol de sistemas ------------////
		arbolSistema = new Ext.tree.TreePanel({
			title:'Gestionar sistemas',
			tbar:[btnAdicionar,btnModificar,btnEliminar,btnImportar,btnExportar/*,btnAyuda*/],
			enableDD:true,
		    autoScroll:true,
			region:'west',
            //animate:false,
			width:150,
			margins:'2 2 2 2',
			loader: new Ext.tree.TreeLoader({
				dataUrl:'cargarsistema'
			}),
			keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    			if(auxDel && auxDel2 && auxDel3)
		    				eliminarSistema();
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
		    			fn: function(){buscargestor(Ext.getCmp('nombregestor').getValue());}
		    		  },
		    		  {
		    		  	key:"m",
		    			alt:true,
		    			fn: function(){
		    			if(auxMod && auxMod2 && auxMod3)
		    				winForm('Mod');
		    			}		    			
		    		  },
		    		  {
		    		  	key:"e",
		    			alt:true,
		    			fn: function(){
		    			if(auxExp && auxExp2 && auxExp3)
		    				exportarSistema();
		    			}},
		    			{
		    		  	key:"x",
		    			alt:true,
		    			fn: function(){
		    			if(auxImp && auxImp2 && auxImp3)
		    				winForm('Imp');
		    			}}])
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
		btnExportar.on('show',function(){
			auxExp2 = true;
		},this)
		btnImportar.on('show',function(){
			auxImp2 = true;
		},this)
		
		////------------ Crear nodo padre del arbol ------------////
		padreSistema = new Ext.tree.AsyncTreeNode({
			text: 'Subsistemas',
             animate:false,
             //layoutConfig:{animate:false   },
			 draggable:false,
			 expandable:false,
			 expanded:true,
			id:'0'
		});
		
		////------------ Crear lista de hijos ------------////
		arbolSistema.setRootNode(padreSistema);
		
		////------------ Evento para habilitar botones ------------////
		arbolSistema.on('click', function (node, e){
            sistemaseleccionado = node.id;
            sistemas =  node;
            bandera =0;
			if(node.id!=0){
	            idsistema = node.id;
				nodoSeleccionado=node;					
				btnModificar.enable();
				btnEliminar.enable();
				btnAdicionar.enable();
                btnImportar.enable();
                btnExportar.enable(); 	
                auxIns = true;
                auxMod = true;
                auxExp = true;
                auxImp = true;
                auxDel = true;
                
			}
			else {			
				btnAdicionar.enable();
				btnModificar.disable();
				btnEliminar.disable();
                btnImportar.enable();
                btnExportar.disable(); 	
                auxIns = true;
                auxImp = true;
                auxMod = false;
                auxExp = false;
                auxDel = false;
			}
		}, this);

		arbolLoaderConex = new Ext.tree.TreeLoader({
			dataUrl:'cargarservidores',
			listeners:{
				'beforeload':function(atreeloader, anode) {
					if(anode.attributes.namebd) {
						atreeloader.baseParams = {};
						atreeloader.baseParams.accion = 'cargaresquemas';
						atreeloader.baseParams.user = anode.attributes.user;
						atreeloader.baseParams.passw = anode.attributes.passw;
						atreeloader.baseParams.gestor = anode.attributes.gestor;
						atreeloader.baseParams.namebd = anode.attributes.namebd;
						atreeloader.baseParams.idservidor = anode.attributes.idservidor;
						atreeloader.baseParams.idgestor = anode.attributes.idgestor;
						atreeloader.baseParams.puerto = anode.attributes.puerto;
						atreeloader.baseParams.idsistema = sistemaseleccionado;
						atreeloader.baseParams.ipgestorbd = anode.attributes.ipgestorbd;
					}
					else if(anode.attributes.idgestor) {
						if (typeof(atreeloader.baseParams) != 'object' || !atreeloader.baseParams.user) {
							nodeArbolConexSelect = anode; 
							levantarVentana();
							return false;
						}
					}
					else if(anode.attributes.idservidor) {
						atreeloader.baseParams = {};
						atreeloader.baseParams.idservidor = anode.attributes.idservidor;
						atreeloader.baseParams.accion = 'cargargestores';
					} 
				},
				'load':function(treeLoaderObj, nodeObj, response) {
					respObj = Ext.decode(response.responseText);
					if (!respObj.codMsg) {
						btnAdicionar.disable();
						btnModificar.disable();
						btnEliminar.disable();
               			btnImportar.disable();
               			btnExportar.disable();
					}
					else if	(respObj.codMsg == 2 && nodeObj.attributes.idgestor) {
						Ext.get('divPassIncorrect').update(respObj.mensaje);
						nodeObj.reload();
					}			
				}
			}
		});
		////------------ Arbol de servidores ------------////
		arbolConex = new Ext.tree.TreePanel({
					title:'Seleccione servidores, gestores, base de datos y esquemas',
					border:false,
					autoScroll:true,
					region:'center',
					width:320,
					loader: arbolLoaderConex,
					margins:'2 2 2 2'
		});
		////////////////////////////////////////////////////////////////
		arbolConex.on('checkchange', function (node, e){
			if(winMod && node.attributes.marcado) {
				var esta = estaEnDeschequeados(arregloDeschequeados, node.attributes.marcado);
          		if(node.attributes.checked && esta != -1)
        			eliminarEnDeschequeados(arregloDeschequeados, esta);
        		else
        			adicionarEnDeschequeados(arregloDeschequeados, node.attributes.marcado);
        	}
        				
		}, this);
		
        function eliminarEnDeschequeados(arreglo, pos) {
            arreglo.splice(pos,1);
        }
        
        function adicionarEnDeschequeados(arreglo, id) {
            if(estaEnDeschequeados(arreglo, id) == -1)
               arreglo.push(id);
        }
        
        function estaEnDeschequeados(arreglonodos, idnodo) {
            for (p=0; p < arreglonodos.length; p++)
                if(arreglonodos[p] == idnodo)
                   return p;
            return -1;
        }
		
		////------------ Crear nodo padre del arbol ------------////
		padreConex = new Ext.tree.AsyncTreeNode({
			  text: 'Servidores',
			  draggable:false,
			  expandable:false,
			  id:'0'
			});
			
		////------------ Crear lista de hijos ------------////
		arbolConex.setRootNode(padreConex);
	
		////------------ Formulario de Datos de Sistema ------------////
		fpRegSistema = new Ext.FormPanel({
			labelAlign: 'top',
			frame:true,
			title:'Datos del sistema',
			width:200,
			region:'west',
			bodyStyle:'padding:5px 5px 0',
			items: [{
					layout:'column',
					items:[{
							columnWidth:1,
							layout:'form',
							items:[
							{
									xtype:'textfield',
									fieldLabel:'Denominaci&oacute;n',
									id:'denominacion',
                                                                        maxLength:50,
									allowBlank:false,
									blankText:'Este campo es obligatorio.',
									regex:tipos,
                                                                        regexText:'Este valor es incorrecto.',
									anchor:'100%'
							},
							{
									xtype:'textfield',
									fieldLabel:'Abreviatura',
									id:'abreviatura',
                                                                        maxLength:50,
									allowBlank:false,
									blankText:'Este campo es obligatorio.',
									regex:tipos,
                                                                        regexText:'Este valor es incorrecto.',
									anchor:'100%'
							},
							{
									xtype:'textfield',
									fieldLabel:'&Iacute;cono',
									id:'icono',
                                                                        maxLength:20,
									regex:tipos,
									regexText:'Este valor es incorrecto.',
									anchor:'100%'
							},{
					            xtype:'fieldset',
                                                    id:'externo',
					            checkboxToggle:true,
					            title: 'Externo',
					            autoHeight:true,
					            defaultType: 'textfield',
					            collapsed: false,
					            items :[{
					                    fieldLabel: 'Servidor web',
					                    name:'servidorweb',
                                        id:'servidorweb',
                                        maxLength:20,
                                        regex: esDirIp,
										anchor:'100%'
					                }]
					        },
							{
									xtype:'textarea',
									fieldLabel: 'Descripci&oacute;n',
									id: 'descripcion',
									height:60,
									anchor:'100%'
							}
							]
					}]
			}]
		});
        fpbuscar = new Ext.FormPanel({
			labelAlign: 'top',
			frame:true,
			fileUpload: true,
			bodyStyle:'padding:5px 5px 0',
			items: new Ext.form.FileUploadField({
				fieldLabel:'Seleccione el sistema para importar',
				name:'fileUpload',
				id:'fileUpload',
				allowBlank:false,
				anchor:'100%'
			})
		});
		
		////------------ Panel para auntenticar con el servidor de base de datos ------------////
		 fpCon = new Ext.FormPanel({
			labelAlign: 'left',
			frame:true,
			width:100,
			fileUpload: true,
			bodyStyle:'padding:5px 5px 0',
			items:[ 
					{
						xtype:'textfield',
						fieldLabel:'Usuario',
						id:'user',
						allowBlank:false,
						blankText:'Este campo es obligatorio.',
						regex:tipos,
						anchor:'100%'
					},
					{
						xtype:'textfield',
						fieldLabel:'Contrase&ntilde;a',
						id:'pass',
						inputType:'password',
						allowBlank:false,
						blankText:'Este campo es obligatorio.',
						anchor:'100%'
					}]
		});
		
		
		////------------ Panel ------------////
		panelAdicionar = new Ext.Panel({
		layout:'border',
		border:'false',
		items:[fpRegSistema,arbolConex]	
		});
		
	
		////------------ Cargar ventanas ------------////
		function winForm(opcion){
			switch(opcion){
				case 'Ins':{
					if(!winIns){
						winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
							title:'Adicionar sistema',width:580,height:450,
							buttons:[
							{
								icon:perfil.dirImg+'cancelar.png',
								iconCls:'btn',
								text:'Cancelar',
								handler:function(){winIns.hide();}
							},							
                            {    
                                icon:perfil.dirImg+'aplicar.png',
                                iconCls:'btn',
                                text:'Aplicar',
                                handler:function(){adicionarSistema('apl');}
                            },
							{	
								icon:perfil.dirImg+'aceptar.png',
								iconCls:'btn',
								text:'Aceptar',
								handler:function(){adicionarSistema();}
							}
							]
						});
						winIns.on('show',function(){
							auxIns3 = false;
							auxMod3 = false;
							auxDel3 = false;
							auxExp3 = false;
							auxImp3 = false;
						},this)
						winIns.on('hide',function(){
							auxIns3 = true;
							auxMod3 = true;
							auxDel3 = true;
							auxExp3 = true;
							auxImp3 = true;
							fpRegSistema.getForm().reset();
							Ext.getCmp('externo').uncheck();
						},this)
					}
                    modificar = 0; 
					arbolConex.getRootNode().reload();
					fpRegSistema.getForm().reset();
					winIns.add(panelAdicionar);			
					winIns.doLayout();
                    Ext.getCmp('externo').show();
                    if(arbolSistema.getSelectionModel().getSelectedNode().id != 0)
                        Ext.getCmp('externo').setVisible(false);
                    
					winIns.show();
				}break;
				case 'Mod':{
					if(!winMod){
						winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
							title:'Modificar sistema',width:580,height:450,
							buttons:[
                                        {
											icon:perfil.dirImg+'cancelar.png',
											iconCls:'btn',
											text:'Cancelar',
											handler:function(){winMod.hide();}
										},
										{	
											icon:perfil.dirImg+'aceptar.png',
											iconCls:'btn',
											text:'Aceptar',
											handler:function(){modificarSistema();}
										}
							]
						});
						winMod.on('show',function(){
							auxIns3 = false;
							auxMod3 = false;
							auxDel3 = false;
							auxExp3 = false;
							auxImp3 = false;
						},this)
						winMod.on('hide',function(){
							auxIns3 = true;
							auxMod3 = true;
							auxDel3 = true;
							auxExp3 = true;
							auxImp3 = true;
							fpRegSistema.getForm().reset();
						},this)
					}
                    modificar = 1;
					arbolConex.getRootNode().reload();
					fpRegSistema.getForm().reset();			
					arbolConex.getLoader().baseParams = {};
					arbolConex.getLoader().baseParams.idsistema = arbolSistema.getSelectionModel().getSelectedNode().id;				
					winMod.add(panelAdicionar);					
					winMod.doLayout();
                    
                    Ext.getCmp('externo').show();
                    if(arbolSistema.getSelectionModel().getSelectedNode().parentNode.id != 0)
                        Ext.getCmp('externo').setVisible(false);
					winMod.show();    
					fpRegSistema.getForm().loadRecord(dameNodeInfo());
                    
                    if(Ext.getCmp('servidorweb').getValue())
                    {        
                       if(bandera == 0)
                            {  
                                Ext.getCmp('externo').expand(false);
                               bandera =1; 
                            }
                    }
                    else
                    {
                         if(bandera == 0)
                                {  
                                    Ext.getCmp('externo'). collapse(false); 
                                   bandera =1; 
                                } 
                    }
				}break;
				case 'Imp': {
				if(!winImp){
					winImp = new Ext.Window({modal: false,closeAction:'hide',layout:'fit',
						title:' Importar sistema',width:400,height:150, items:fpbuscar,
						buttons:[{icon:perfil.dirImg+'cancelar.png',iconCls:'btn',
									text:'Cancelar',
									handler:function(){winImp.hide();}
								},{icon:perfil.dirImg+'aceptar.png',iconCls:'btn',
								text:'Aceptar',
								handler:function(){importarSistema();}}
						]
					});
					winImp.on('show',function(){
						auxIns3 = false;
						auxMod3 = false;
						auxDel3 = false;
						auxExp3 = false;
						auxImp3 = false;
					},this)
					winImp.on('hide',function(){
						auxIns3 = true;
						auxMod3 = true;
						auxDel3 = true;
						auxExp3 = true;
						auxImp3 = true;
					},this)
				}
				fpbuscar.getForm().reset();	
				winImp.show('btnImportar');
			}
			break;
			}
		}
		var vpGestSistema = new Ext.Viewport({
			layout:'fit',
			items:arbolSistema
		})
		
	//---------------obtener datos del sistema para modificar---------------------------//
	  var dameNodeInfo = function(){
	    var record = Ext.data.Record.create([
		        {name: 'denominacion', mapping: 'denominacion'},        
		    	])  
		    return new record({
		        denominacion: nodoSeleccionado.attributes.text,
		        abreviatura: nodoSeleccionado.attributes.abreviatura,  
				descripcion: nodoSeleccionado.attributes.descripcion,
		        icono: nodoSeleccionado.attributes.icono,
                servidorweb: nodoSeleccionado.attributes.servidorweb         
		 })

	}
	
	fpPass = new Ext.FormPanel({
	    frame:true,
	    width:100,
	    bodyStyle:'padding:5px 5px 0',
	    items: [{
            layout:'column',
            items:[{
	            columnWidth:2,
	            layout:'form',
	            items:[{
	                xtype:'textfield',
	                fieldLabel:perfil.etiquetas.lbUsuario,                                   
	                id:'user',
	                blankText:perfil.etiquetas.lbMsgBlankTextDenom,
	                allowBlank:false,     
					labelStyle:'width:80px',
					width:130
				},{
	                xtype:'textfield',
	                fieldLabel:perfil.etiquetas.lbTitMsgContrasena,
	                inputType:'password',
	                blankText:perfil.etiquetas.lbMsgBlankTextDenom,                                
	                id:'passw',
					labelStyle:'width:80px',
					allowBlank:false,
					width:130
				}]
			}]
	    }],
	    html: '<div id="divPassIncorrect" style="color: red"></div>'
	});
        
	function levantarVentana() {
		if (!winPass) {
			winPass = new Ext.Window({
				modal: true,
				closeAction:'hide',
				layout:'fit',
				title:perfil.etiquetas.lbBtnCambiarpass,
				width:290,
				height:150,
				resizable:false,
				buttons:[{
					icon:perfil.dirImg+'cancelar.png',
					iconCls:'btn',
					text:perfil.etiquetas.lbBtnCancelar,
					handler:function() {
						winPass.hide();
					}
	       		},{
					icon:perfil.dirImg+'aceptar.png',
					iconCls:'btn',
					handler:function() {
						if(fpPass.getForm().isValid()) {
							arbolLoaderConex.baseParams = {};
							arbolLoaderConex.baseParams.accion = 'cargarbd';
							arbolLoaderConex.baseParams.user = Ext.getCmp('user').getValue();
							arbolLoaderConex.baseParams.passw = Ext.getCmp('passw').getValue();                
							arbolLoaderConex.baseParams.gestor = nodeArbolConexSelect.attributes.gestor;
							arbolLoaderConex.baseParams.ipgestorbd = nodeArbolConexSelect.attributes.ipgestorbd;
							arbolLoaderConex.baseParams.idgestor = nodeArbolConexSelect.attributes.idgestor;
							arbolLoaderConex.baseParams.idservidor = nodeArbolConexSelect.attributes.idservidor;
							arbolLoaderConex.baseParams.puerto = nodeArbolConexSelect.attributes.puerto;
							arbolLoaderConex.baseParams.idsistema = sistemaseleccionado;
							arbolLoaderConex.load(nodeArbolConexSelect);
							winPass.hide();
							arbolLoaderConex.baseParams = {};
							Ext.get('divPassIncorrect').update('');
						}
						else
							mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
					},
					text:perfil.etiquetas.lbBtnAceptar
				}]
			});
			winPass.add(fpPass);        
			winPass.doLayout();
		}
		Ext.getCmp('user').reset();
		Ext.getCmp('passw').reset();
		winPass.show();	
	}
	
	    ////------------ Adicionar Sistema ------------////
		function adicionarSistema (apl)
		{
			if (fpRegSistema.getForm().isValid()){
			var arrayNodos = arbolConex.getChecked();
			arrayservidores = []; 
			arrayTemp = [];
			arrayRoles = [];
			var band = false;
			var post = 0;
			arrayRoles = getArrayRoles();
			if(arrayNodos.length > 0) {
				if(arrayRoles.length > 0) {
					for (var i = 0; i < arrayNodos.length; i++) {
						if (!arrayNodos[i].attributes.marcado) {
							if(arrayNodos[i].attributes.type == 'schemas') {
							band = true;
							arrayTemp.push(arrayNodos[i].getPath('id').split('/'));
							for(var j = 0; j < arrayRoles.length; j++) {
								if(arrayRoles[j][3] == arrayTemp[post][3]) {
									arrayTemp[post][arrayTemp[post].length] = arrayRoles[j][4];
									arrayservidores.push(arrayTemp[post]);
									post++;
									break;
									}
								
								/*else
									{alert(arrayRoles[j][3]+'roles');
									alert(arrayTemp[post][3]+'nodoselect');
									mostrarMensaje(3,perfil.etiquetas.MsgErrorConex);return;}*/
								}
							}
						else {
								if(!band) {
									mostrarMensaje(3,perfil.etiquetas.MsgErrorConexSchemas);
									band = false;
									return;
									}
							}
						}
					}
				}
				else
					{mostrarMensaje(3,perfil.etiquetas.MsgErrorConex);return;}
				}  
			      fpRegSistema.getForm().submit({
				    url:'insertarsistema',
				    params:{idpadre:arbolSistema.getSelectionModel().getSelectedNode().id, servidores:Ext.encode(arrayservidores)},
				    waitMsg:'Registrando sistema ...',
				    failure: function(form, action){
						    
						    if(action.result.codMsg != 3) {
                                mostrarMensaje(action.result.codMsg,action.result.mensaje);
                                if(sistemas.parentNode) 
                                    sistemas.parentNode.reload();
                                else
                                    sistemas.reload();                                                     
							    arbolConex.getRootNode().reload();
							    fpRegSistema.getForm().reset();
							    
							    
							    if(!apl)   
							       winIns.hide(); 
						    }
                    if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);	
				    }
		    });
			}
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);            
	
		}
		
		////------------ Modificar sistema ------------////
		function modificarSistema()
		{
		if (fpRegSistema.getForm().isValid())
		{
			var arrayNodos = arbolConex.getChecked();
			arrayservidores = []; 
			arrayTemp = [];
			arrayRoles = [];
			var band = false;
			var post = 0;
			arrayRoles = getArrayRoles();
			if(arrayNodos.length > 0) {
				if(arrayRoles.length > 0) {
					for (var i = 0; i < arrayNodos.length; i++) {
						if (!arrayNodos[i].attributes.marcado) {
							if(arrayNodos[i].attributes.type == 'schemas') {
							band = true;
							arrayTemp.push(arrayNodos[i].getPath('id').split('/'));
							for(var j = 0; j < arrayRoles.length; j++) {
								if(arrayRoles[j][3] == arrayTemp[post][3]) {
									arrayTemp[post][arrayTemp[post].length] = arrayRoles[j][4];
									arrayservidores.push(arrayTemp[post]);
									post++;
									break;
									}
								
								/*else
									{alert(arrayRoles[j][3]+'roles');
									alert(arrayTemp[post][3]+'nodoselect');
									mostrarMensaje(3,perfil.etiquetas.MsgErrorConex);return;}*/
								}
							}
						else{
							if(!band)
								{mostrarMensaje(3,perfil.etiquetas.MsgErrorConexSchemas);band = false;return;}
							}
						}
					}
				}
				else
					{mostrarMensaje(3,perfil.etiquetas.MsgErrorConex);return;}
				}
				fpRegSistema.getForm().submit({
							 url:'modificarsistema',					
							 waitMsg:'Modificando sistema...',																
							 params:{idsistema:arbolSistema.getSelectionModel().getSelectedNode().id, servidores:Ext.encode(arrayservidores), esquemasEliminados:Ext.encode(arregloDeschequeados)},
							 failure: function(form, action){
								if(action.result.codMsg != 3) {
											mostrarMensaje(action.result.codMsg,action.result.mensaje); 
											fpRegSistema.getForm().reset(); 
											winMod.hide();
											arbolSistema.getSelectionModel().getSelectedNode().parentNode.reload();									
											btnModificar.disable();
											btnEliminar.disable();
											auxIns = false;
											auxMod = false;
											auxDel = false;
											auxImp = false;
											auxExp = false;	
								}
								if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);	
							 }
				    });  
			}
            else
                mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);            
		}
		
		function arrayConexiones() {
			var arrayNodos = arbolConex.getChecked();
			arrayservidores = []; 
			arrayTemp = [];
			arrayRoles = [];
			var band = false;
			var post = 0;
			arrayRoles = getArrayRoles();
			if(arrayNodos.length > 0) {
				if(arrayRoles.length > 0) {
					for (var i = 0; i < arrayNodos.length; i++) {
						if (!arrayNodos[i].attributes.marcado && arrayNodos[i].attributes.type == 'schemas') {
							band = true;
							arrayTemp.push(arrayNodos[i].getPath('id').split('/'));
							for(var j = 0; j < arrayRoles.length; j++) {
								if(arrayRoles[j][3] == arrayTemp[post][3] && arrayNodos[i].attributes.type != 'roles') {
									arrayTemp[post][arrayTemp[post].length] = arrayRoles[j][4];
									arrayservidores.push(arrayTemp[post]);
									post++;
									break;
								}
								else
									{alert('pepe');mostrarMensaje(3,perfil.etiquetas.MsgErrorConex);return}
							}
						}
						else{
							if(!band)
								{alert('lolo');mostrarMensaje(3,perfil.etiquetas.MsgErrorConexSchemas);return}
							}
					}
				}
				else
					{mostrarMensaje(3,perfil.etiquetas.MsgErrorConex);return;}
				}
				return arrayservidores;
		}
		
		function getArrayRoles() {
			array = arbolConex.getChecked();
			arrayResult = [];
			for(i = 0; i < array.length; i++) {
				if(array[i].attributes.type == 'roles')
					arrayResult.push(array[i].getPath('id').split('/'));
			}
			return arrayResult;
		}
		
		////------------------- Eliminar Sistema ------------------------////
		function eliminarSistema(){
			mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminar el sistema?',elimina);
			function elimina(btnPresionado)
			{
				if (btnPresionado == 'ok')
				{
					Ext.Ajax.request({
					url: 'eliminarsistema',
					method:'POST',
					params:{idsistema:arbolSistema.getSelectionModel().getSelectedNode().attributes.id},
					callback: function (options,success,response){
					responseData = Ext.decode(response.responseText);
						if(responseData.codMsg == 1)
						{
							arbolSistema.getSelectionModel().getSelectedNode().parentNode.reload();
							mostrarMensaje(responseData.codMsg,responseData.mensaje);
							btnEliminar.disable();
							sistemas = null;
                            btnModificar.disable();
                            btnAdicionar.disable();
                            btnExportar.disable();
                            btnImportar.disable();
                            btnAdicionar.disable();
                            auxIns = false;
							auxMod = false;
							auxDel = false;
							auxImp = false;
							auxExp = false;	
						}
						if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
						}
					});
				}
			}
		}
////------------------- Exportar Sistema ------------------------////
	function exportarSistema()
	{
	    auxIns3 = false;
		auxMod3 = false;
		auxDel3 = false;
		auxExp3 = false;
		auxImp3 = false;
	    document.getElementById('idsistema').value = arbolSistema.getSelectionModel().getSelectedNode().attributes.id;
	    var formexport = document.getElementById('exportarsistemas');
	    formexport.method = 'POST';
	    formexport.target = '_blank';
	    formexport.action = 'exportarsistema';
	    formexport.submit();   
	    auxIns3 = true;
		auxMod3 = true;
		auxDel3 = true;
		auxExp3 = true;
		auxImp3 = true;
	}
	////------------------- Importar Sistema ------------------------////
	function importarSistema()
	{
		fpbuscar.getForm().submit({
			url:'importarXML',
			waitMsg:'Tramitando al servidor',
			params:{idsistema:arbolSistema.getSelectionModel().getSelectedNode().attributes.id},
			failure: function(form, action){
				if(action.result.codMsg == 1){
					mostrarMensaje(action.result.codMsg,action.result.mensaje); 
					winImp.hide();
                    if(arbolSistema.getSelectionModel().getSelectedNode().attributes.id ==0)
                        arbolSistema.getSelectionModel().getSelectedNode().reload();     
                    else
					    arbolSistema.getSelectionModel().getSelectedNode().parentNode.reload();			
					}
                                    else
                                        alert('pepe');
			}
		});
	}
	
}