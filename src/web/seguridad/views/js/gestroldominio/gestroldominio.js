
    	var perfil = window.parent.UCID.portal.perfil;	
    	UCID.portal.cargarEtiquetas('gestroldominio', function(){cargarInterfaz();});
		
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
		
		////------------ Declarar variables ------------////
		var winIns,auxRecord,panelUsuariosDominios,btnguardarcambios, winMod,winRegular,winRol,winPass,fpPass,btnRol,panelRolEntidad,arbolEstructura, arrayPadresEliminar = [], arregloDeschequeados = [], arrayTiene = [];
		var idsist,iduser,idi,idu,idr,storeGridRol,aBandera = false;
		var idsistema,idrol,nodo,gridRol,sm, smrol;
		var esDirIp, usuario,regUsuario,modificar = 0, idrol = 0;
		////------------ Botones ------------////
		btnguardarcambios = new Ext.Button({disabled:true,icon:perfil.dirImg+'aceptar.png',iconCls:'btn',text:'Guardar cambios'})
		////------------ Funcion Cargar Interfaz ------------////
		function cargarInterfaz()
        {	
		////------------ Modo de seleccion del grid de usuarios ------------////
		sm = new Ext.grid.RowSelectionModel({singleSelect:false});
		////------------ Eventos ------------////
		sm.on('rowselect', function (smodel, rowIndex, record)
		{
			idrol = record.data.idrol;
			arbolDominios.enable();
			btnguardarcambios.enable();
			arbolDominios.getRootNode().reload();
			auxRecord = record;
	    }, this);
	    sm.on('rowdeselect', function (SelectionModel, rowIndex, record) {
			asignarUsuarioDominio(null, record);
		}, this);
			
        ////------------Store para cargar el grid de roles---------------////
        storeGridRoles =  new Ext.data.Store({
	        url: 'cargarRolDominio',
	        reader:new Ext.data.JsonReader({
		        totalProperty: "cantidad_filas",
		        root: "datos",
		        id: "idrol"
	        },
            [
            {name:'idrol',mapping:'idrol'},
            {name:'denominacion',mapping:'denominacion'}
            ])    
        });
        ////------------ Creando el Grid de roles ------------////
        gridRoles = new Ext.grid.GridPanel({  
            region:'west',
            frame:true,
            width:400,
            iconCls:'icon-grid',    
            autoExpandColumn:'expandir',
            margins:'2 2 2 -4',
            store:storeGridRoles,
            sm:sm,
            columns: [
                        {header: perfil.etiquetas.lbRol, width:150, dataIndex: 'denominacion', id:'expandir'},
                        {hidden: true, hideable: false, dataIndex: 'idrol'}
                     ],
            
            loadMask:{store:storeGridRoles},
            
            tbar:[
                    new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbTitDenRolBuscar}),
                    denrolbuscado = new Ext.form.TextField({width:80, id: 'denrolbuscado'}),
                    new Ext.menu.Separator(),            
                    new Ext.Button({icon:perfil.dirImg+'buscar.png',iconCls:'btn',text:perfil.etiquetas.lbBtnBuscar, handler:function(){buscarnombrerol(denrolbuscado.getValue());}})
                 ],
            bbar:new Ext.PagingToolbar({
                    pageSize: 15,
                    store: storeGridRoles,
                    displayInfo: true,
                    displayMsg: perfil.etiquetas.lbTitMsgResultados,
                    emptyMsg: perfil.etiquetas.lbTitMsgNingunresultadoparamostrar
            })
        });
        /////------------ Arbol de entidades que estan en mi dominio ------------////
        arbolDominios = new Ext.tree.TreePanel({
	        autoScroll:true,
	        region:'center',
	        split:true,
	        disabled:true,           
	        width:'37%',
	        loader:new Ext.tree.TreeLoader({
	            dataUrl:'cargarArbolDominios',
	            listeners:{'beforeload':function(atreeloader, anode){ 
	                            atreeloader.baseParams = {}; 
	                            atreeloader.baseParams.idrol = sm.getSelected().data.idrol;
	                           
	                    }
	            } 
	    })
    	});
	    ////------------ Crear nodo padre del arbol ------------////
	    padreArbolDominios= new Ext.tree.AsyncTreeNode({
	          text: 'Dominios',
	          expandable:false,
	          id:'0'
	    });         
	    
	    arbolDominios.setRootNode(padreArbolDominios);
	    
	    btnguardarcambios.on('click',function(){
	    	asignarUsuarioDominio(null, 0)
			arbolDominios.disable();
			arbolDominios.getRootNode().reload();
			sm.clearSelections();
			btnguardarcambios.disable();
		})	
	     arbolDominios.on('checkchange', function (node, e)
	     {
	        var esta = estaEnDeschequeados(arregloDeschequeados, node.attributes.id);
	        var id = node.attributes.id;
	        var esta1 = estaEnDeschequeados(arrayPadresEliminar, id);
	           
	        if(node.attributes.checked)
	        {
	            marcarHijos(node, true);
	            if(esta != -1)
	                eliminarEnDeschequeados(arregloDeschequeados, esta);
	            if(!node.isLeaf() && esta1 != -1)
	                eliminarEnDeschequeados(arrayPadresEliminar, esta1);
	        }
	        else
	        {
	            aBandera = false;
	            adicionarEnDeschequeados(arregloDeschequeados, node.attributes.id);
	            if(!node.isLeaf() && node.childNodes.length == 0)
	                adicionarEnDeschequeados(arrayPadresEliminar, id); 
	        }
	    	}, this);
			////------------ Panel con los componentes de roles y entidades ------------////
	        panelUsuariosDominios = new Ext.Panel({
	            layout:'border',
	            region:'center',
	            items:[gridRoles,arbolDominios],
	            buttons:[btnguardarcambios]
	        });
	        ////------------ Viewport ------------////
	        viewport = new Ext.Viewport({
	        	layout:'border',
	        	items:[panelUsuariosDominios]
	        })
			storeGridRoles.load({params:{start:0,limit:15}});
			////------------- Funciones -------------////
			function asignarUsuarioDominio(apl, record)
	        {
			if (!idrol)
				return;
			        var arrayNodos = arbolDominios.getChecked();
	                var arrayEnt = new Array();
	                var arrayPadres = new Array();
	                var arrayarbol = new Array();
	                buscarNodosTiene(arbolDominios.getRootNode());
	                if(arrayNodos.length > 0)
	                {
	                    for (var i=0; i<arrayNodos.length; i++)
	                    {                   
	                            arrayEnt.push(arrayNodos[i].attributes.id);
	                            if(!arrayNodos[i].isLeaf() && arrayNodos[i].childNodes.length == 0)
	                            {       
	                                arrayPadres.push(arrayNodos[i].attributes.id);
	                            }
	                    }
	                 }
	                    Ext.getBody().mask('Por favor espere....'); 
	                    Ext.Ajax.request({
	                    url: 'insertarRolesDominios',
	                    method:'POST',
	                    params:{arrayDominios: Ext.encode(arrayEnt),arrayDominiosEliminar:Ext.encode(arregloDeschequeados), arrayPadres:Ext.encode(arrayPadres), arrayPadresEliminar:Ext.encode(arrayPadresEliminar),idrol:idrol},
	                    callback: function (options,success,response){                    
	                    Ext.getBody().unmask();
	                    arrayPadresEliminar = [];
	                    arregloDeschequeados = [];
	                    arrayTiene = [];
	                    responseData = Ext.decode(response.responseText);
	                    }});
	        }
	        ////------------ Funciones Auxiliares ------------////
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
	        ////------------ Buscar Usuarios ------------////
	        function buscarnombrerol(denrol)
	        {
		        storeGridRoles.baseParams.denrolbuscado = denrol;
		        storeGridRoles.load({params:{start:0,limit:15}});
	        } 
	    }
	        
