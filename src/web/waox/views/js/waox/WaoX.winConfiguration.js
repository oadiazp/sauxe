/*
 * File: WaoX.winConfiguration.js
 * Date: Sat Jan 01 2011 00:00:55 GMT-0800 (Pacific Standard Time)
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be generated the first time you export.
 *
 * You should implement event handling and custom methods in this
 * class.
 */

WaoX.winConfiguration = Ext.extend(WaoX.winConfigurationUi, {
    initComponent: function() {
    	vp = this.scope.scope

    	this.width = vp.getWidth () - vp.getWidth () * 0.05;
    	this.height = vp.getHeight () - vp.getHeight () * 0.05;


        WaoX.winConfiguration.superclass.initComponent.call(this);
    }
});
Ext.reg('waox.winConfiguration', WaoX.winConfiguration);