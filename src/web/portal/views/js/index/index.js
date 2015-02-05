/*
 * Componente portal de aplicaciones
 *
 * Interfaz inicial de autenticacion e iniciacion de ventanas.
 *
 * @author Dionisdel Ponce Santana
 * @package Portal
 * @subpackage Portal
 * @copyright UCID-ERP Cuba
 * @version 1.0-0
 */
var ventana = null, iddominio = null;
var FormIndex = null;
var newAccess = false;
// Formulario inicial para la seleccion de la entidad
FormIndex = new Ext.FormPanel({
	formId:'FormIndex',
	frame:true,
	title: 'Bienvenido al Sistema Integral de Gestión - CEDRUX',
	bodyStyle:'padding:5px 5px 0',
	width: 400,
	style:'text-align:left',
	autoHeight:300,
	items: [{
			xtype: 'treepanel',
			autoScroll:true,
			frame:false,
			animate: false,
			bodyStyle:'background-color:#FFFFFF;',
			height:200,
			listeners:{
				'click':function(nodo){
					if(nodo.id != 0){
						Ext.getCmp('btnAceptar').enable();
						iddominio = nodo.id;
					} else {
						if (!newAccess)
							Ext.getCmp('btnAceptar').disable();
						iddominio = null;
					}
			}},
			root:new Ext.tree.AsyncTreeNode({
				text: 'Dominio de entidades',
				id:'0'
			}),
			loader: new Ext.tree.TreeLoader({
				dataUrl:'index.php/index/cargardominio',
				preloadChildren :false,
				baseParams:{}
			})
		},{
			xtype:'fieldset',
			style:'margin-top:10px;',
			title:'Acceder al sistema',
			autoHeight:true,
			items:[{
				xtype: 'radio',
				hideLabel: true,
				boxLabel: 'Usuario autenticado',
				name: 'tipoacceso',
				inputValue :'ultimo',
				checked: true,
				listeners:{
					'check':function(obj, ischeck){
						if (ischeck) {
							if (iddominio == null)
								Ext.getCmp('btnAceptar').disable();
							newAccess = false;
						}
					}
				}
			},{
				xtype: 'radio',
				hideLabel: true,
				boxLabel: 'Usuario diferente',
				name: 'tipoacceso',
				inputValue :'nuevo',
				listeners:{
					'check':function(obj, ischeck){
						if (ischeck) {
							Ext.getCmp('btnAceptar').enable();
							newAccess = true;
						}
					}
				}
			}]
		}
	],
	buttons: [{
			text: 'Aceptar',
			disabled:true,
			id:'btnAceptar',
			handler: function(){entrarAlSistema();}
		}]
});
//Se renderiza el formulario inicial
FormIndex.render('dFormIndex');
//Configuración de la ventana de trabajo con la resolución de la pantalla
var wCfg = 'titlebar=yes,status=yes,resizable=yes,width='+screen.width+',height='+screen.height;
if (document.getElementById('accesodirecto').value != '0')
	ventana = window.open('index.php/portal/portal','ERP_CUBA',wCfg);
// Funcion para verificar la entrada al sistema
function entrarAlSistema(){
	FormIndex.getForm().submit({
		url:'index.php/index/entraralsistema',
		waitMsg:'Tramitando entrada ...',
		params:{dominio:iddominio},
		failure: function(form, action){
			if(ventana != null){
				ventana.close();
				ventana = null;
			}
			if(action.result.reload == false){
				window.location.reload();
			}
			else if(action.result.reload == true){
				ventana = window.open('index.php/portal/portal','ERP_CUBA',wCfg);
			}
			else if (action.result.codMsg) {
				mensaje = action.result;
				mostrarMensaje(mensaje.codMsg, mensaje.mensaje);
			}
		}
	});
}
