/*
 * Componente portal de aplicaciones
 *
 * Elemento base del portal donde se colocan funciones generales.
 *
 * @author Dionisdel Ponce Santana
 * @package Portal
 * @subpackage Portal
 * @copyright UCID-ERP Cuba
 * @version 1.0-0
 */

var UCID = new Object();
var winError;
var acciones_reportes = [];
UCID.portal = new Object();
if(Ext){
	Ext.BLANK_IMAGE_URL = '/lib/ExtJS/temas/default/images/s.gif';
						   

	//Ext.WindowMgr.zseed = 50000;
	
	if(window.parent.UCID.portal.dir_ext) {
		var existLink = false;
		var linkArr = document.getElementsByTagName("link");
		var port = '';
		if (window.location.port)
			port =  ':' + window.location.port;
		var dirHostIconCSS = window.location.protocol + '//' + window.location.host + port + window.parent.UCID.portal.perfil.dirIconCSS;
		var dirHostnameIconCSS = window.location.protocol + '//' + window.location.hostname + port + window.parent.UCID.portal.perfil.dirIconCSS;  
		for (var i=0;  i<linkArr.length; i++) {
			if (linkArr[i].href == dirHostIconCSS || linkArr[i].href == dirHostnameIconCSS) {
				existLink = true;
				break;
			}
		}
		if (!existLink)
			importarCSS(window.parent.UCID.portal.perfil.dirIconCSS);
	}
	
	// Control de excepciones (No se puede implementar hatsta que no haya gestion de excepciones no controladas)
	
	/*Ext.Ajax.on('requestcomplete', function(conn, response, options){
		var respText = response.responseText;
		//if ((/^{ }$^/).test(response.responseText)){
			var respObj = Ext.decode(response.responseText);
			if (respObj.mensaje && respObj.codMsg)
				mostrarMensaje(respObj.codMsg, respObj.mensaje, null, respObj.detalles);
		//}
	});*/
	
	Ext.Ajax.on('requestcomplete', function(conn, response, options){
		var respText = response.responseText;
		var respXML = response.responseXML;
		if (respText && !respXML){
			var respObj = Ext.decode(respText);			
			if (respObj.codMsg && respObj.codMsg >= 1 && respObj.codMsg <= 4){
				mostrarMensaje(respObj.codMsg, respObj.mensaje, null, respObj.detalles);
                           }
                       
		}
	});

	UCID.portal.cargarEtiquetas = function(vistaCU, fn){
		Ext.Ajax.request({
			url: 'cargaretiquetas',
			method:'POST',
			params:{vista:vistaCU},
			callback: function (options,success,response){
				if(success){
					perfil.etiquetas = Ext.decode(response.responseText);
					var codMsg = perfil.etiquetas.codMsg;
					if (!codMsg)
						fn();
				}
			}
		});
	}

	// Funcion para mostrar un mensaje

	mostrarMensaje = function (tipo, msg, fn, detalle){
	   var buttons = new Array(Ext.MessageBox.OK, Ext.MessageBox.OKCANCEL, Ext.MessageBox.OK);
	   var title   = new Array('Informaci&oacute;n', 'Confirmaci&oacute;n', 'Error');
	   var icons   = new Array(Ext.MessageBox.INFO, Ext.MessageBox.QUESTION , Ext.MessageBox.ERROR);
		
		if(tipo==4){
			if(!winError){
				winError = new Ext.Window({
					title:' ERROR ',
					width:400,
					minWidth :300,
					buttonAlign:'center',
					autoHeight: true,
					closeAction:'hide',
					bodyBorder :false,
					layout:'fit',
					defaults :{frame:true,border :false},
					items:new Ext.Panel({
						frame:true,monitorResize :true,
						autoHeight: true,layout:'column',
						items:[{columnWidth:.2,
							items: {style:'margin:2px 0px 0px 2px;',
								html:'<div id="iconoERROR" class="iconoERROR></div>'
							}
						},{ columnWidth:.7,
							items: {xtype:'panel',
								autoHeight: true,
								html:'<div id="pMSG">'+msg+'</div>'}
						},{ columnWidth:1,
							items: new Ext.form.FieldSet({
								autoScroll:true,
								layout:'form',title :' Detalles : ',
								autoWidth:true,html:'<div id="pDetalle">'+detalle+'</div>',
								autoHeight: true,collapsed:true,collapsible:true
							})
						}]
					}),
					buttons: [{text: 'OK',
						handler: function(){
						winError.hide();
						if (typeof(fn)=='function')fn();
						}
					}]
				});
			}
		winError.show(Ext.getBody());
		document.getElementById("pMSG").innerHTML = msg;
		document.getElementById("pDetalle").innerHTML = detalle;
		}else{
			Ext.MessageBox.show({
				title:title[tipo-1],
				msg:msg,
				//animEl: Ext.getBody(),
				buttons: buttons[tipo-1],
				icon:icons[tipo-1],
				fn:fn
			});
		}
	}
	
	UCID.portal.cargarAcciones = function(idFuncionalidad, fn){
		Ext.Ajax.request({
			url: 'cargaracciones',
			method:'POST',
			params:{idfuncionalidad: idFuncionalidad},
			callback: function (options,success,response){
				if(success){

					acciones_reportes = Ext.decode(response.responseText);
					var codMsg = acciones_reportes.codMsg;
					if (!codMsg) {
						for (i in acciones_reportes) {
							if (i != 'remove' && Ext.getCmp(acciones_reportes[i].abreviatura)) {
								if(Ext.getCmp(acciones_reportes[i].abreviatura).getXType() == 'datefield' || Ext.getCmp(acciones_reportes[i].abreviatura).getXType() == 'textfield')
									Ext.getCmp(acciones_reportes[i].abreviatura).enable();
								else
									Ext.getCmp(acciones_reportes[i].abreviatura).show();
							}
						}
						if(fn) 
							fn();
					}
				}
			}
		});
	}

	UCID.portal.getAccionesReportes = function(){
		return acciones_reportes;
	}
}//Endofif
