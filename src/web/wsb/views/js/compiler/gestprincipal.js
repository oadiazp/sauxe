Ext.QuickTips.init();
Ext.form.Field.prototype.msgTarget = 'qtip';
var perfil = window.parent.UCID.portal.perfil


function cargarInterfaz(){

var BtnAceptarCom;
var BtnCancelarCom;
var BtnAceptarPrueba;
var BtnCancelarPrueba;
var tfRetornoCom ;
var taDescripcion;
var formularioCom;
var VentComentario;
var VentProbar;
var gridParametros;
var gridParametrosPrueba
var formularioPrueba;
var lMaskk;


var stparametroPrueba= new Ext.data.Store({
    reader:new Ext.data.JsonReader({
                root:'datos'
                },
                [{name: 'parametro',mapping:'param'},
                 {name: 'tipodato',mapping:'tipo'},
                 {name: 'valor',mapping:'valor'}
                ])
    });
var parametros;
function MostrarVentanaProbar(idfachada)
{
    parametros = "";
        if(!VentProbar)
        {
            BtnAceptarPrueba= new Ext.Button({
                    id      : 'btsdadd',
                    text    : '<b>Aceptar</b>',
                    iconCls :'btn',
                    icon:perfil.dirImg+'aceptar.png'
            });

            BtnCancelarPrueba = new Ext.Button({
                    id      : 'btndadd',
                    text    : '<b>Cancelar</b>',
                    iconCls : 'btn',
                    icon:perfil.dirImg+'cancelar.png',
                    handler : function(){
                        VentProbar.hide();
                    }

            });

            gridParametrosPrueba = new Ext.grid.EditorGridPanel({
                    store: stparametroPrueba,
                    anchor:'100%',
                    autoExpandColumn:'uno',
                    height:250,
                    title:'Parámetros',
                    loadMask:true,
                    frame:true,
                    clicksToEdit:1,
                    columns:[{
                             id:'uno',
                             header: "Nombre",
                             dataIndex: 'parametro',
                             resizable :false
                             },{
                             id:'otro',
                             header: "Tipo de dato",
                             dataIndex: 'tipodato',
                             width: 85,
                             resizable :false
                             },{
                             id:'valor',
                             header: "Valor",
                             dataIndex: 'valor',
                             width: 85,
                             resizable :false,
                             editor: new Ext.form.TextField({
                                            selectOnFocus:true,
                                            allowBlank:false,
                                            value: 0
                                        })
                             }]
            });

            

            VentProbar = new Ext.Window({
                title: 'Probador de servicios',
                width: 500,
                height:420,
                modal:true,
                resizable:false,
                closeAction :'hide',
                layout: 'form',
                plain:true,
                bodyStyle:'padding:5px;',
                items: [gridParametrosPrueba],//formularioPrueba
                buttons: [BtnCancelarPrueba,BtnAceptarPrueba]
            });
        }
        BtnAceptarPrueba.setHandler( function (){
            if(true){
                for(var i = 0; i < stparametroPrueba.getCount();i++)
                {
                    parametros += stparametroPrueba.getAt(i).data.valor;
                    if(i != stparametroPrueba.getCount()-1){
                        parametros += ",";
                    }
                }
                InvocarServicio(idfachada,parametros);
                VentProbar.hide();
            }

        });
		
        stparametroPrueba.removeAll();
        lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Cargando parámetros...'});
        lMaskk.show();
        Ext.Ajax.request({
                url: 'loadparametros',
                method:'POST',
                params:{idfachada:idfachada},
                callback: function (options,success,response){
                                lMaskk.hide();
                                var data = Ext.decode(response.responseText);
                                if(data.datos.length){
                                    VentProbar.show();
                                    stparametroPrueba.loadData(data);
                                }
                                else{
                                    InvocarServicio(idfachada,"");
                                }
                            }
        });

        
};

function InvocarServicio(idfachada,parametros){
            lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Ejecutando prueba...'});
            lMaskk.show();
            Ext.Ajax.request({
                url: 'probarservicio',
                method:'POST',
                params:{idfachada:idfachada, parametros:parametros},
                callback: function (options,success,response){
                                gridPaquetesServicios.getStore().reload();
                                lMaskk.hide();
                                var data = Ext.decode(response.responseText);
                                if(data.codigo == 1){
                                    mostrarMensaje(1,"El servicio fue consumido satisfactoriamente.<br> La funcionalidad invocada dió como respuesta: "+data.respuesta);
                                }
                                else if(data.codigo == 2){
                                    mostrarMensaje(1,"El servicio fue consumido satisfactoriamente.<br> La funcionalidad invocada lanzó la siguiente excepción:<br>"+data.respuesta);
                                }
                                else{
                                    mostrarMensaje(3, "El servicio falló la prueba realizada.");
                                }
                            }
            });
            parametros = "";
        }
var stparametro= new Ext.data.Store({
    reader:new Ext.data.JsonReader({
                root:'datos'
                },
                [{name: 'parametro',mapping:'param'},
                 {name: 'tipodato',mapping:'tipo'}])
    });
function MostrarVentanaComentario(path,clase,metodo)
{
        if(!VentComentario)
        {
                //----------------------Ventana Comentario-----------------------
                        BtnAceptarCom= new Ext.Button({
                                id      : 'btsdadd',
                                text    : '<b>Aceptar</b>',
                                iconCls :'btn',
                                icon:perfil.dirImg+'aceptar.png'
                                //handler :function(){ Enviar()}
                        });

                        BtnCancelarCom = new Ext.Button({
                                id      : 'btndadd',
                                text    : '<b>Cancelar</b>',
                                iconCls : 'btn',
                                icon:perfil.dirImg+'cancelar.png',
                                handler : function(){
                                                        VentComentario.hide();
                                }

                        });

                        gridParametros = new Ext.grid.EditorGridPanel({
                                store: stparametro,
                                anchor:'100%',
                                autoExpandColumn:'uno',
                                height:250,
                                title:'Parámetros',
                                loadMask:true,
                                frame:true,
                                clicksToEdit:1,
                                columns:[{
                                                 id:'uno',
                                                 header: "Nombre",
                                                 dataIndex: 'parametro',
                                                 resizable :false
                                              },{
                                                 id:'otro',
                                                 header: "Tipo de dato",
                                                 dataIndex: 'tipodato',
                                                 width: 85,
                                                         resizable :false,
                                                 editor: new Ext.form.TextField({
                                                                selectOnFocus:true,
                                                                allowBlank: true
                                                 })
                                              }
                                          ]
                        });

                        tfRetornoCom = new Ext.form.TextField({
                                fieldLabel: 'Tipo del retorno',
                                selectOnFocus:true,
                                name: 'to',
                                emptyText:'<undefined>',
                                anchor:'100%'
                        });

                        taDescripcion = new Ext.form.TextArea({
                                fieldLabel:'Descripci&oacute;n',
                                selectOnFocus:true,
                                emptyText:'Sin descripción.',
                                anchor:'100%'
                        });

                        formularioCom = new Ext.form.FormPanel({
                        baseCls: 'x-plain',
                        defaultType: 'textfield',
                        //labelWidth: 70,
                        items: [tfRetornoCom,taDescripcion]
                  });

                        VentComentario = new Ext.Window({
                        title: 'Editor de comentario',
                        width: 500,
                        height:420,
                        modal:true,
                        resizable:false,
                        closeAction :'hide',
                        layout: 'form',
                        plain:true,
                        bodyStyle:'padding:5px;',
                        items: [formularioCom,gridParametros],
                        buttons: [BtnCancelarCom,BtnAceptarCom]
                        });
        }

        formularioCom.getForm().reset();
        stparametro.removeAll();
        var retornoold = "";
        var descripcionold = "";
        lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Cargando comentario...'});
        lMaskk.show();
        Ext.Ajax.request({
                url: 'loadcomentario',
                method:'POST',
                params:{path:path,clase:clase,metodo:metodo},
                callback: function (options,success,response){
                            lMaskk.hide();
                            var data = Ext.decode(response.responseText);
                            stparametro.loadData(data);
                            if(data.retorno != "<undefined>")
                            {
                                            tfRetornoCom.setValue(data.retorno);
                                            retornoold = data.retorno;
                            }
                            if(data.descripcion != "no tiene descripción")
                            {
                                            taDescripcion.setValue(data.descripcion);
                                            descripcionold = data.descripcion;
                            }
                        }
        });
        BtnAceptarCom.setHandler( function (){


                        if( stparametro.getModifiedRecords().length != 0 || tfRetornoCom.getValue() != retornoold || taDescripcion.getValue() != descripcionold)
                        {
                                var envio = [];
                                for(var i = 0; i < stparametro.getCount();i++)
                                {
                                         envio.push(stparametro.getAt(i).data);
                                }
                                lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Modificando comentario...'});
                                lMaskk.show();
                                Ext.Ajax.request({
                                        url: 'setcomentario',
                                        method:'POST',
                                        params:{
                                                                path:path,
                                                                clase:clase,
                                                                metodo:metodo,
                                                                datos:Ext.encode(envio),
                                                                retorno:tfRetornoCom.getValue(),
                                                                descripcion:taDescripcion.getValue()
                                                        },
                                        callback: function (options,success,response){lMaskk.hide();}
                                });

                        }
                        VentComentario.hide();
        });

        VentComentario.show();
};
	
var clickednode;
menu = new Ext.menu.Menu({
    items: [{text: 'Comentar', handler: function () {MostrarVentanaComentario(clickednode.attributes.path,clickednode.parentNode.text,clickednode.text);}},
            {text: 'Crear sevicio', handler: function () {MostrarVentanaCrearServicio(clickednode.text,clickednode.parentNode.text,clickednode.attributes.path);}}]
});

var BtonAceptar;
var BtonCancelar; 
var texboxombre; 
var texboxAutor; 
var VentCompilar;
var VentEliminarPaquete;
		
var arbolExplorador = new AD.Tree ({
    root : new Ext.tree.AsyncTreeNode({
        id: 'root',
        text: 'Soluci&oacute;n',
        leaf: false,
        type: 'folder'
    }),
    checkeable: true,
    title: 'Explorador de soluciones',
    region: 'west',
    collapsible:true,
    listeners:{contextmenu : function (pNode) {
                                if (pNode.attributes.type == 'method') {
                                            pNode.select ();
                                            var selNodes = arbolExplorador.getChecked();
                                                Ext.each(selNodes, function(node){
                                                   node.ui.toggleCheck();
                                                });
                                            if(!pNode.attributes.checked){
                                                pNode.ui.toggleCheck();
                                            }
                                            clickednode = pNode;
                                            menu.show (pNode.ui.getAnchor());
                                }
                             },
               'checkchange': function(node, checked){
                        if(arbolExplorador.getChecked().length){
                            Ext.getCmp("botonpaquete").enable();
                        }
                        else{
                            Ext.getCmp("botonpaquete").disable();
                        }
                }

            },
    split: true,
    url:"../compiler/loadtree",
    border:true,
    bbar: ['->',{text: 'Cragar solución',
                   id:"botonsolucion",
                   icon:perfil.dirImg+'actualizar.png',
                   iconCls:'btn',
                   handler :function(){
                                    arbolExplorador.collapseAll();
                                    Ext.getCmp ('winSelectSolution').show ();
                            }
                   },
                  {text: 'Generar paquete',
                   id:"botonpaquete",
                   disabled:true,
                   icon:perfil.dirImg+'exportar.png',
                   iconCls:'btn',
                   handler :function(){
                                    MostrarVentanaCompilar()
                            }
                   }
            ]
});
var VentCrearServicio;
function MostrarVentanaCrearServicio(met,clase,path){
        if(!VentCrearServicio){

            //----------------------Ventana crear servicio-----------------------\\
            stS = new Ext.data.Store({
                    url: 'loadpaquetes',
                    reader: new Ext.data.JsonReader({
                    root: 'data'
                    }, ['idpaquete', 'nombrepaquete'])
            });

            cbS = new Ext.form.ComboBox ({
                store: stS,
                displayField: 'nombrepaquete',
                valueField: 'idpaquete',
                fieldLabel: 'Paquete',
                triggerAction: 'all',
                emptyText: 'Paquete de servicios...',
                allowBlank: false,
                blankText: 'Seleccione un paquete.',
                typeAhead: true,
                id: 'pack',
//                forceSelection: true,
                editable: false,
                mode: 'local',
                disabled: true
            });
            frmS =  new Ext.form.FormPanel ({
                labelWidth: 60,
                defaults: {layout: 'form', frame: true},
                items: {
                    defaultType: 'radio',
                    items: [{
                        checked: true,
                        boxLabel: 'Nuevo paquete',
                        name: 'opcion',
                        labelSeparator: '',
                        inputValue: 'new'
                    }, {
                        fieldLabel: '',
                        labelSeparator: '',
                        boxLabel: 'Paquete existente',
                        name: 'opcion',
                        inputValue: 'exists',
                        listeners: {
                            check: function (c, ch) {
                                if (ch)
                                    stS.load ();
                                else {
                                        cbS.setValue ("");
                                }
                                cbS.setDisabled(! ch);
                            }
                        }
                    }, cbS]
                }
            });
           VentCrearServicio = new Ext.Window ({
                title: 'Crear servicio',
                frame: true,
                modal: true,
                layout: 'form',
                width: 320,
                closable: false,
                id: 'winCreateService',
                items: frmS,
                buttons: [{id: 'cancre', text: 'Cancelar',icon:perfil.dirImg+'cancelar.png',iconCls:'btn', handler: function(){frmS.getForm().reset();Ext.getCmp ('winCreateService').hide ();}},
                          {id: 'okcre', text: 'Aceptar', icon:perfil.dirImg+'aceptar.png',iconCls:'btn'}]
            });
        }
        Ext.getCmp("okcre").setHandler(function () {
                                if (frmS.getForm().isValid ()) {
                                    opcion = frmS.getForm ().getValues()['opcion'];
                                    if (opcion == 'new') {
                                        Ext.getCmp ('winCreateService').hide ();
                                        MostrarVentanaCompilar();
                                    }
                                    else {
                                        lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Creando servicio...'});
                                        lMaskk.show();
                                        Ext.Ajax.request({
                                            url: 'addservice',
                                            params: {
                                                    idpaquete : cbS.getValue (),
                                                    metodo:met,
                                                    clase: clase,
                                                    path:path
                                                    },
                                            callback: function(options,success,response){
                                                    lMaskk.hide();
                                                    responsedata = Ext.decode(response.responseText);
                                                    if(responsedata.codigo == 1){
                                                        mostrarMensaje(1,responsedata.message);
                                                        gridPaquetesServicios.getStore().reload();
                                                    }
                                                    else if(responsedata.codigo == 2){
                                                        mostrarMensaje(3,responsedata.message);
                                                    }
                                                    else {
                                                        mostrarMensaje(3,"Ha ocurrido un error interno del sistema.");
                                                    }
                                                    frmS.getForm().reset();
                                                    VentCrearServicio.hide();
                                                }
                                            });
                                    }
                                }
                            });
        VentCrearServicio.show();
}

function MostrarVentanaEliminarPaquete(){
    if(!VentEliminarPaquete){

            //----------------------Ventana crear servicio-----------------------\\
            stSp = new Ext.data.Store({
                    url: 'loadpaquetes',
                    reader: new Ext.data.JsonReader({
                        root: 'data'
                    }, ['idpaquete', 'nombrepaquete'])
            });

            cbSp = new Ext.form.ComboBox ({
                store: stSp,
                displayField: 'nombrepaquete',
                valueField: 'idpaquete',
                fieldLabel: 'Selecione el paquete que desea eliminar',
                triggerAction: 'all',
                emptyText: 'Paquete de servicios...',
                allowBlank: false,
                blankText: 'Seleccione un paquete.',
                typeAhead: true,
                id: 'packs',
                editable: false
            });
            
            frmSp =  new Ext.form.FormPanel ({
                labelWidth: 200,
                labelAlign:'top',
                frame: true,
                items: [cbSp]
            });
            
           VentEliminarPaquete = new Ext.Window ({
                title: 'Eliminar paquete',
                frame: true,
                modal: true,
                layout: 'form',
                width: 260,
                closable: false,
                id: 'winEliminarPaquete',
                items: frmSp,
                buttons: [{id: 'cancelimp', text: 'Cancelar',icon:perfil.dirImg+'cancelar.png',iconCls:'btn', handler: function(){Ext.getCmp ('winEliminarPaquete').hide ();frmSp.getForm().reset();}},
                          {id: 'okelimp', text: 'Aceptar', icon:perfil.dirImg+'aceptar.png',iconCls:'btn', handler: function () {
                              if (frmSp.getForm().isValid ()) {
                                lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Eliminando paquete de servicios...'});
                                lMaskk.show();
                                Ext.Ajax.request({
                                            url: 'removepackage',
                                            params: {idpaquete : cbSp.getValue ()},
                                            callback: function(options,success,response){
                                                    lMaskk.hide();
                                                    responsedata = Ext.decode(response.responseText);
                                                    if(responsedata.codigo == 1){
                                                        mostrarMensaje(1,responsedata.message);
                                                        gridPaquetesServicios.getStore().reload();
                                                    }
                                                    else if(responsedata.codigo == 2){
                                                        mostrarMensaje(3,responsedata.message);
                                                    }
                                                    else {
                                                        mostrarMensaje(3,"Ha ocurrido un error interno del sistema.");
                                                    }
                                                    frmSp.getForm().reset();
                                                    VentEliminarPaquete.hide();
                                                }
                                            });
                              }
                            }}]
            });
        }
        stSp.load ();
        VentEliminarPaquete.show();
}

function MostrarVentanaCompilar(){
    if(arbolExplorador.getChecked().length){
        if(!VentCompilar){
            //----------------------Ventana compilar-----------------------\\
            BtonAceptar= new Ext.Button({
                id      : 'btsdadd',
                text    : '<b>Aceptar</b>',
                iconCls : 'btn',
                icon:perfil.dirImg+'aceptar.png',

                handler : function(){compilar();}
            });
            BtonCancelar = new Ext.Button({
                id      : 'btndadd',
                text    : '<b>Cancelar</b>',
                icon: perfil.dirImg+'cancelar.png',
                iconCls : 'btn',

                onClick :function(){
                            formulario.getForm().reset()
                            VentCompilar.hide();
                        }
            });
            texboxombre = new Ext.form.TextField({
                fieldLabel:'Nombre',
                allowBlank: false,
                blankText : 'Este campo es obligatorio.',
                vtype: 'nombre' ,
                anchor: '95%',
                id:'nombre'
            });
            texboxAutor = new Ext.form.TextField({
                fieldLabel:'Autor',
                anchor: '95%',
                id:'autor'
            });
            texboxdescri = new Ext.form.TextArea({
                fieldLabel:'Descripci&oacute;n',
                anchor:'95%',
                id:'descripcion'
            });


            formulario = new Ext.form.FormPanel({
                baseCls: 'x-plain',
                labelWidth: 70,
                frame :true,
                region      :'center',
                layout      :'form',
                width       : 560,
                height      : 160,
                autoScroll  : true,
                defaultType: 'textfield',
                items :[texboxombre,texboxAutor,texboxdescri]
            });
            VentCompilar = new Ext.Window({
                title 	:'Generar paquete',
                width 	:400,
                height	:198,
                resizable 	:false,
                closeAction :'hide',
                modal 	:true,
                plain       : true,
                layout	:'fit',
                bodyStyle 	:'padding:5px;',
                items	:formulario,
                buttons	:[BtonCancelar,BtonAceptar]
            });
        }
        VentCompilar.show();
    }
    else{
        mostrarMensaje(3,"Debe seleccionar al menos un método.");
    }
}

    var expresion = /^([a-zA-Z0-9_])+$/i;
    Ext.apply(Ext.form.VTypes, {
        //  vtype validation function
        nombre: function(val, field) {
            return expresion.test(val);
        },
        // vtype Text property: The error text to display when the validation function returns false
        nombreText: 'Nombre no válido.'
    });
    function compilar(){
        if(formulario.getForm().isValid()){
            var selNodes = arbolExplorador.getChecked();

            var clases = [];
            var metodos = [];
            var paths = [];
            var seleccion = [];

            Ext.each(selNodes, function(node){
                clases.push(node.parentNode.text);
                metodos.push(node.text);
                paths.push(node.attributes.path);
            });

            seleccion.push(clases);
            seleccion.push(metodos);
            seleccion.push(paths);
            lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Creando paquete de servicios...'});
            lMaskk.show();
            Ext.Ajax.request({
                url: 'compile',
                params: {
                        seleccion : Ext.encode(seleccion),
                        necesarios : Ext.encode(formulario.getForm().getValues())
                        },
                callback: function(options,success,response){
                        lMaskk.hide();
                        responsedata = Ext.decode(response.responseText);
                        if(responsedata.codigo == 1){
                            mostrarMensaje(1,"El paquete fue creado con &eacute;xito.");
                            gridPaquetesServicios.getStore().reload();
                            Ext.each(selNodes, function(node){
                               node.ui.toggleCheck();
                            });
                        }
                        else if(responsedata.codigo == 3){
                            mostrarMensaje(3,"Existen dependencias no resueltas en la solución.");
                        }
                        else{
                            mostrarMensaje(3,"Debe especificar los par&aacute;metros y tipo de retorno de los m&eacute;todos a exportar como servicios web.");
                            gridErrores.getStore().loadData(responsedata);
                            gridErrores.expand(true);
                        }
                        formulario.getForm().reset();
                        VentCompilar.hide();
                    }
                }
            );
        }
    }
	
	Ext.grid.MyCheckboxSelectionModel = Ext.extend(Ext.grid.CheckboxSelectionModel,                {
                header:"<div id='paco' class=\"x-grid3-hd-checker\">&#160;</div>",
                width:20,
                sortable:false,
                menuDisabled:true,
                fixed:true,
                dataIndex:"",
                id:"checker",
                cabecera:-1,
                initEvents:function()
                        {
                                Ext.grid.CheckboxSelectionModel.superclass.initEvents.call(this);
                                this.grid.on("render",function(){
                                                    var A=this.grid.getView();
                                                    A.mainBody.on("mousedown",this.onMouseDown,this);
                                                    Ext.fly(A.innerHd).on("mousedown",this.onHdMouseDown,this)
                                                },
                                        this
                                )
                        },

                onMouseDown:function(C,B)
                {
                        if(C.button===0&&B.className=="x-grid3-row-checker")
                        {
                                C.stopEvent();
                                var D=C.getTarget(".x-grid3-row");
                                if(D)
                                {
                                        var A=D.rowIndex;
                                        if(this.isSelected(A))
                                        {


                                                /*//si la cabecera de columna esta seleccionada la deselecci�no
                                                if(this.cabecera != -1)
                                                {


                                                        Ext.fly(this.cabecera.parentNode).removeClass("x-grid3-hd-checker-on");

                                                }*/
                                                this.deselectRow(A);
                                        }else
                                        {
                                                this.selectRow(A,true);
                                                /*if(this.getCount() == gridPaquetesServicios. getStore().getCount())
                                                {
                                                        Ext.fly(this.cabecera.parentNode).addClass("x-grid3-hd-checker-on");
                                                }*/
                                        }
                                }
                        }
                },
                onHdMouseDown:function(C,A)
                                        {
                                                if(A.className=="x-grid3-hd-checker")
                                                {
                                                        C.stopEvent();
                                                        var B=Ext.fly(A.parentNode);
                                                        var D=B.hasClass("x-grid3-hd-checker-on");
                                                        if(D)
                                                        {
                                                                B.removeClass("x-grid3-hd-checker-on");
                                                                //document.getElementById('paco').className = "x-grid3-hd-checker";
                                                                this.clearSelections();
                                                        }
                                                        else
                                                        {
                                                                this.cabecera = A;
                                                                B.addClass("x-grid3-hd-checker-on");
                                                                this.selectAll()
                                                        }
                                                }
                                        },
                renderer:function(B,C,A)
                                        {
                                                return"<div class=\"x-grid3-row-checker\">&#160;</div>";
                                        }
        });
	
	var smservicios = new Ext.grid.MyCheckboxSelectionModel();
	
	var expander = new Ext.grid.RowExpander({
		tpl : new Ext.Template(
			'<p><b>Descripci&oacute;n:</b> <br/> &nbsp; &nbsp; &nbsp;{descripcion}</p><br/><p><b>Ubicaci&oacute;n:</b> <br/> &nbsp; &nbsp; &nbsp;{uri}</p>'
		)
        });
        
	
    var groping = new Ext.data.GroupingStore({
                    url:"loadservices",
                    reader: new Ext.data.JsonReader({
                            totalProperty: "cantidad_filas",
                            root: "datos",
                            id:'idred'
                            }, [
                           {name: 'idpaquete', mapping:'idpaquete'},
                           {name: 'idfachada', mapping:'idfachada'},
                           {name: 'nombrepaquete', mapping:'nombrepaquete'},
                           {name: 'idservicio', mapping:'idservicio'},
                           {name: 'nombreservicio', mapping:'nombreservicio'},
                           {name: 'descripcion', mapping:'descripcion'},
                           {name: 'uri', mapping:'uri'},
                           {name: 'estado', mapping:'estado'}
                    ]),
                    sortInfo:{field: 'idservicio', direction: "ASC"},
                    groupField:'nombrepaquete'
                });
	

	var VentGenerador;
	var gridRespuestas;
	var arbolResultado;
	var formgenerar;
	var VentPrueba;
	
    var gridPaquetesServicios = new Ext.grid.GridPanel ({
	
	title: 'Paquetes de servicios',
	store: groping,
        layout:'fit',
	sm:smservicios,
	split: true,
	autoExpandColumn:'nomservice',
	region:'center',
	width :300,
	plugins: expander,
	border:true,
        bbar: ['->',{id: 'remPaquete', text: 'Eliminar paquete',icon:perfil.dirImg+'eliminar.png',iconCls:'btn', disabled: true, handler: function () {
                       MostrarVentanaEliminarPaquete();
                    }},
                    {id: 'remServicio', text: 'Eliminar servicio',icon:perfil.dirImg+'eliminar.png',iconCls:'btn', disabled: true, handler: function () {
                       Ext.MessageBox.confirm('Eliminar servicios', 'Esta seguro que desea eliminar los servicios seleccionados?',
                        function (btn){
                            if(btn=='yes'){
                                var selec = smservicios.getSelections();
                                if(selec.length){
                                    services = [];
                                    Ext.each(selec, function(elem){
                                        services.push(elem.data.idfachada);
                                    });
                                    lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Eliminando servicios...'});
                                    lMaskk.show();
                                    Ext.Ajax.request({
                                        url: 'removeservices',
                                        params: {seleccion : Ext.encode(services)},
                                        callback: function(options,success,response){
                                                lMaskk.hide();
                                                gridPaquetesServicios.getStore().reload();
                                            }
                                    });
                                }
                                else{
                                    mostrarMensaje(3,"Debe seleccionar al menos un proxy.");
                                }
                            }
                        });
                    }},
                    {id: 'testServicio', text: 'Probar servicio',icon:perfil.dirImg+'falta.png',iconCls:'btn', disabled: true, handler: function () {
                       MostrarVentanaProbar(smservicios.getSelected().data.idfachada);
                    }},
                    {
                    text: 'Generar proxy',
                    icon:perfil.dirImg+'exportar.png',
                    id:"botonproxy",
                    disabled:true,
                    iconCls:'btn',
                    handler :function(){
                            if(smservicios.getSelections().length){
                            if(!VentGenerador)
                            {
                                //--------------------------------Ventana Generador---------------------------------------------\\
                                BtonAceptar= new Ext.Button({
                                        id      : 'btdadd',
                                        text    : '<b>Aceptar</b>',
                                        iconCls : 'btn',
                                        icon:perfil.dirImg+'aceptar.png',
                                        handler : function(){
                                                      if(formgenerar.getForm().isValid())
                                                      {

                                                          selected = smservicios.getSelections();
                                                          if(selected.length){
                                                                var serv = [];
                                                                Ext.each(selected, function(elem){
                                                                    serv.push(elem.data.idfachada);
                                                                });
                                                                lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Creando proxy...'});
                                                                lMaskk.show();
                                                                Ext.Ajax.request({
                                                                    url: 'createproxy',
                                                                    params: {seleccion : Ext.encode(serv),
                                                                             datos : Ext.encode(formgenerar.getForm().getValues())
                                                                            },
                                                                    callback: function(options,success,response){
                                                                            lMaskk.hide();
                                                                            gridProxies.getStore().reload();
                                                                            formgenerar.getForm().reset();
                                                                            VentGenerador.hide();
                                                                        }
                                                                });
                                                          }
                                                          else
                                                              mostrarMensaje(3,"Debe seleccionar al menos un servicio.");
                                                      }

                                                  }
                                });

                                BtonCancelar = new Ext.Button({
                                    id      : 'btnddd',
                                    text    : '<b>Cancelar</b>',
                                    iconCls : 'btn',
                                    icon:perfil.dirImg+'cancelar.png',
                                    onClick :function(){
                                            formgenerar.getForm().reset();
                                            VentGenerador.hide();
                                            }
                                    });

                                texboxombre = new Ext.form.TextField({
                                    fieldLabel  :'Nombre del proxy',
                                    anchor  	:'95%',
                                    allowBlank : false,
                                    blankText : 'Este campo es obligatorio.',
                                    vtype: 'nombre' ,
                                    id          :'Nombreproxy'
                                    });
                                texboxdescrip = new Ext.form.TextArea({
                                    fieldLabel  :'Descripci&oacute;n',
                                    anchor  	:'95%',
                                    id          :'descripcionproxy'
                                    });

                                formgenerar = new Ext.form.FormPanel({
                                                region      :'center',
                                    baseCls	 :'x-plain',
                                    layout      :'form',
                                    width       : 310,
                                    height	:  158,
                                    items       :[texboxombre,texboxdescrip]
                                    });

                                VentGenerador = new Ext.Window({
                                    title 		:'Generador de proxies',
                                    layout		:'fit',
                                    width 		:400,
                                    height		:165,
                                    modal 		:true,
                                    plain		:true,
                                    closeAction :'hide',
                                    resizable : false,
                                    bodyStyle	:'padding:5px;',
                                    items		:[formgenerar],
                                    buttons	:[BtonCancelar,BtonAceptar]
                                    });
                                    //--------------------------------Fin Ventana Generador---------------------------------------------\\
                            }

                            VentGenerador.show();
                    }
                    else{
                        mostrarMensaje(3,"Debe seleccionar al menos un servicio.");
                    }
                    }
                    }],
		columns: [
			expander,
			smservicios,
                        {id:'nomservice',header: "Nombre del servicio", width: 280, sortable: true, dataIndex: 'nombreservicio'},
                        {header: "Estado", width: 120, sortable: true, dataIndex: 'estado'},
                        {id:'idpaquete', hidden :true, header: "IdPaquete", sortable: true, dataIndex: 'idpaquete'},
                        {header: "Paquete", hidden :true, sortable: true, dataIndex: 'nombrepaquete'},
                        {header: "Fachada", hidden :true, sortable: true, dataIndex: 'idfachada'},
                        {header: "IdServicio", hidden :true, sortable: true, dataIndex: 'idservicio'}
                        ],
		
		view: new Ext.grid.GroupingView({
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Servicios" : "Servicio"]})'
        })
    });
    groping.on("load",function(st){
        if(st.getCount()){
            Ext.getCmp("remPaquete").enable();
        }
        else{
            Ext.getCmp("remPaquete").disable();
        }
    });
    smservicios.on("selectionchange",function(sm){
        if(sm.getSelections().length){

            Ext.getCmp("botonproxy").enable();
            Ext.getCmp("remServicio").enable();
            if(sm.getSelections().length == 1){
                Ext.getCmp("testServicio").enable();
            }
            else{
                Ext.getCmp("testServicio").disable();
            }
        }
        else{
            Ext.getCmp("botonproxy").disable();
            Ext.getCmp("testServicio").disable();
            Ext.getCmp("remServicio").disable();
        }
    });
    var proxyexpander = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
                '<p><b>Descripci&oacute;n:</b> <br/> &nbsp; &nbsp; &nbsp;{descripcion}</p><br/><p><b>Servicios:</b> <br/> &nbsp; &nbsp; &nbsp;{servicios}</p>'
        )
    });
    var smproxys = new Ext.grid.MyCheckboxSelectionModel();
    var gridProxies = new Ext.grid.GridPanel ({
        title: 'Proxies',
        store: new Ext.data.Store ({
                url:"loadproxies",
                reader:new Ext.data.JsonReader({
                    totalProperty: "cantidad_filas", 
                    root: "datos", 
                    id: "idrep"
                    }, 
                    [{name:'idproxy', mapping:'idproxy'},
                     {name:'descripcion', mapping:'descripcion'},
                     {name:'servicios', mapping:'servicios'},
                     {name:'nombre', mapping:'nombre'}]
                    )
        }),
		sm:smproxys,
        region:'east',
        split: true,
        width :250,
        collapsible:true,
		autoExpandColumn:'exp',
        layout:'fit',
		plugins: proxyexpander,
        border:0,
        bbar:['->',{id: 'remProxy', text: 'Eliminar',icon:perfil.dirImg+'eliminar.png',iconCls:'btn', disabled: true, handler: function () {
                      Ext.MessageBox.confirm('Eliminar proxies', 'Esta seguro que desea eliminar los proxies seleccionados?',
                        function (btn){
                            if(btn=='yes'){
                                var selec = smproxys.getSelections();
                                if(selec.length){
                                    proxies = [];
                                    Ext.each(selec, function(elem){
                                        proxies.push(elem.data.idproxy);
                                    });
                                    lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Eliminando proxies...'});
                                    lMaskk.show();
                                    Ext.Ajax.request({
                                        url: 'removeproxies',
                                        params: {seleccion : Ext.encode(proxies)},
                                        callback: function(options,success,response){
                                                lMaskk.hide();
                                                gridProxies.getStore().reload();
                                            }
                                    });
                                }
                                else{
                                    mostrarMensaje(3,"Debe seleccionar al menos un proxy.");
                                }
                            }
                        });
                    }},
                    {
                        text:'Descargar',
                        id:"botondescarga",
                        disabled:true,
                        icon:perfil.dirImg+'descargar.png',
                        iconCls:'btn',
                        handler: function(){
                            selec = smproxys.getSelections();
                            if(selec.length){
                            var selec = smproxys.getSelections();proxies = [];
                            Ext.each(selec, function(elem){
                                proxies.push(elem.data.idproxy);
                            });
                            document.getElementById('idproxies').value = Ext.encode(proxies);
                            var formexport = document.getElementById('donwloadproxy');
                            formexport.method = 'POST';
                            formexport.target = '_blank';
                            formexport.action = 'downloadproxy';
                            formexport.submit();
                             
                            }
                            else{
                                 mostrarMensaje(3,"Debe seleccionar al menos un proxy.");
                            }
                        }
                }

        ],
        columns: [
					
					proxyexpander,
					smproxys,
                                        {header: 'idproxy', hidden:true, dataIndex: 'idproxy',id:'idp'},
					{header: 'Nombre del proxy',width: 120 , dataIndex: 'nombre',id:'exp'}
				]
    });
    smproxys.on("selectionchange",function(){
        if(smproxys.getSelections().length){
            Ext.getCmp("botondescarga").enable();
            Ext.getCmp("remProxy").enable();
        }
        else{
            Ext.getCmp("botondescarga").disable();
            Ext.getCmp("remProxy").disable();
        }

    });
    var gridErrores = new Ext.grid.GridPanel ({
        title: 'Errores',
        collapsible: true,
	collapsed:true,
        store: new Ext.data.Store ({
            reader:new Ext.data.JsonReader( 
                    { 
                    totalProperty: "cantidad_filas", 
                    root: "datos", 
                    id: "idre"
                    }, 
                    [ 
                    {name:'tipo', mapping:'tipo'},
                    {name:'descripcion', mapping:'descripcion'},
                    {name:'clase', mapping:'clase'},
                    {name:'metodo', mapping:'metodo'},
                    {name:'path', mapping:'path'}
                    ] 
                    )
        }),
        region: 'south',
        height: 280,
        columns: [{header: 'Tipo de error',width: 220, dataIndex:'tipo'},
                  {header: 'Especificaciones',width: 800, dataIndex:'descripcion'}]
    });
    gridErrores.on("rowclick",function(grid,rowIndex,e){
        var datos = grid.getStore().getAt(rowIndex).data;
        MostrarVentanaComentario(datos.path,datos.clase,datos.metodo);
    });
	
        var Medio = new Ext.Panel({
            width :100,
            layout:'border',
            height:35,
            border:0,
            items:[arbolExplorador,gridPaquetesServicios,gridProxies]
        });
	var arriba = new Ext.Panel({
            region:'center',
            layout:'fit',
            items:[Medio]
        });
	
	
    new Ext.Viewport ({
        layout: 'border',
        items: [
            arriba,
            gridErrores
        ]
    })



//alert(document.getElementById("ext-comp-1004-xcollapsed"));


//------------------------------------------*****FUNCIONES*****----------------------------------------------------\\

Ext.Ajax.method = 'post';

st = new Ext.data.Store({
    url: 'loadSolutions',
    reader: new Ext.data.JsonReader({
        root: 'data'
    }, ['idsolucion', 'solucion'])
});

cb = new Ext.form.ComboBox ({
    store: st,
    displayField: 'solucion',
    valueField: 'idsolucion',
    fieldLabel: 'Solución',
    triggerAction: 'all',
    emptyText: 'Solución ...',
    allowBlank: false,
    blankText: 'Seleccione un solución.',
    typeAhead: true,
    id: 'solution',
    forceSelection: true,
    editable: false,
    mode: 'local',
    disabled: true,
    listeners: {
        select : function () {
            Ext.getCmp ('rem').enable ();
        }
    }
});

frm =  new Ext.form.FormPanel ({
        labelWidth: 60,
     defaults: {layout: 'form', frame: true},
     items: {
                defaultType: 'radio',
                
                items: [{
                    checked: true,
                    boxLabel: 'Nueva solución',
                    name: 'opcion',
                    labelSeparator: '',
                    inputValue: 'new'
                }, {
                    fieldLabel: '',
                    labelSeparator: '',
                    boxLabel: 'Solución existente',
                    name: 'opcion',
                    inputValue: 'exists',
                    listeners: {
                        check: function (c, ch) {
                            if (ch)
                                st.load ();
                            else {
                                    Ext.getCmp ('rem').disable ();
                                    cb.setValue ("");
                            }

                            cb.setDisabled(! ch);
                        }
                    }
                }, cb]
            }
});

treePath = new AD.Tree ({
    root : new Ext.tree.AsyncTreeNode({
        id: 'root',
        text: 'Soluci&oacute;n',
        leaf: false,
        type: 'folder'
    }),
   // width: 483,
    title: 'Ruta',
    layout:'fit',
    only_folders: true,
    height: 293,
    closable: false,
    checkeable: false,
    url:"../compiler/loadinittree"
});



nuevasolucion = new Ext.FormPanel({
    
    layout:'form',
    labelWidth :50,
    items:[new Ext.Panel({
        frame:true,
        layout:'form',
        labelWidth :50,
        items:{
	fieldLabel: 'Nombre',
        xtype: 'textfield',
        id: 'solucion',
        allowBlank: false,
        blankText : 'Este campo es obligatorio.',
        msgTarget :'qtip'
        }
        })
        ,treePath
        ]
    });


winSelectSolution = new Ext.Window ({
    title: 'Solución',
    frame: true,
    modal: true,
    layout: 'form',
    width: 320,
    closable: false,
    id: 'winSelectSolution',
    items: frm,
    buttons: [{id: 'rem', text: 'Eliminar',icon:perfil.dirImg+'eliminar.png',iconCls:'btn', disabled: true, handler: function () {
       if (frm.getForm().isValid ())
           Ext.MessageBox.confirm('Eliminar solución', 'Esta seguro que desea eliminar la solución?',
            function (btn){
               if(btn=='yes'){
                   lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Eliminando solución...'});
                   lMaskk.show();
                   frm.getForm().submit ({
                       url: 'removesolution',
                       failure: function () {
                           lMaskk.hide();
                           cb.setValue ('');
                           st.reload ();
                       }
                   });
               }
            });
           
    }},/*{id: 'can54', text: 'Cancelar',icon:perfil.dirImg+'cancelar.png',iconCls:'btn', handler: function () {
       Ext.getCmp ('winSelectSolution').hide ();

    }},*/{id: 'ok', text: 'Aceptar', icon:perfil.dirImg+'aceptar.png',iconCls:'btn', handler: function () {
        if (frm.getForm().isValid ()) {
            opcion = frm.getForm ().getValues () ['opcion'];

            if (opcion == 'new') {
                Ext.getCmp ('winSelectSolution').hide ();
                winWizard.show ();
             } else {
                 lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Cargando solución...'});
		 lMaskk.show();
                 Ext.Ajax.request ({
                     url: 'setsolution',
                     params: {
                         idsolucion: cb.getValue ()
                     },
                     callback: function (resp) {
                         lMaskk.hide();
                         r = Ext.decode (resp.responseText);

                         if (!r || Ext.decode (resp.responseText).codMsg == 1)  {
                             Ext.getCmp ('winSelectSolution').hide ();
                             gridPaquetesServicios.getStore().reload();
                             gridProxies.getStore().reload();
                             arbolsolucion = arbolExplorador.getRootNode ();
                             arbolsolucion.setText (cb.getRawValue());
                             arbolsolucion.reload();
                         }
                     }
                 })
             }
          }
        }
    }]
}).show ();


winWizard = new Ext.Window ({
    title: 'Nueva solución',
    closable: false,
    frame: true,
    modal:true,
    resizable:false,
    width: 500,
    height: 400,
    items: nuevasolucion,
    buttons: [
     {text: 'Cancelar', icon:perfil.dirImg+'cancelar.png',iconCls:'btn',handler: function () {
                                                                             winWizard.hide ();
                                                                             treePath.collapseAll();
                                                                             Ext.getCmp ('winSelectSolution').show ();
                                                                             nuevasolucion.getForm().reset();
                                                                       }}
     ,{text: 'Aceptar',icon:perfil.dirImg+'aceptar.png',iconCls:'btn', handler: function () {
               if(nuevasolucion.getForm().isValid()){
                   if(treePath.getSelectionModel ().getSelectedNode ()){
                       lMaskk = new Ext.LoadMask(Ext.getBody(),{msg:'Creando solución...'});
                       lMaskk.show();
                       Ext.Ajax.request ({
                           url: 'addsolution',
                           callback: function (options,success,response) {
                                lMaskk.hide();
                                responsedata = Ext.decode (response.responseText);
                                if(responsedata.codigo == 1){
                                    gridPaquetesServicios.getStore().reload();
                                    gridProxies.getStore().reload();
                                    arbolsolucion = arbolExplorador.getRootNode ();
                                    arbolsolucion.setText (Ext.getCmp ('solucion').getValue ());
                                    arbolsolucion.reload();
                                    treePath.collapseAll();
                                    nuevasolucion.getForm().reset();
                                    winWizard.hide ();
                                    mostrarMensaje(1,responsedata.message);
                                }
                                else
                                    mostrarMensaje(3,responsedata.message);
                           },
                           params: {solucion: Ext.getCmp ('solucion').getValue (),
                                    path: treePath.getSelectionModel ().getSelectedNode ().attributes.path
                                    }

                       });
                   }
                   else{
                       mostrarMensaje(3,"Debe seleccionar la ruta de la solución.");
                   }
                }
        }}]
});
}
cargarInterfaz();

