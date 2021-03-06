/*
 * File: WaoX.egChart.js
 * Date: Sat Jan 01 2011 00:01:54 GMT-0800 (Pacific Standard Time)
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be generated the first time you export.
 *
 * You should implement event handling and custom methods in this
 * class.
 */

WaoX.egChart = Ext.extend(WaoX.egChartUi, {
    initComponent: function() {
        WaoX.egChart.superclass.initComponent.call(this);

         this.btnAdd.setHandler (function () {
        	this.stChart.insert (this.stChart.getCount (), new Ext.data.Record ({
        		chart: '',
        		view: ''
        	}))
        	this.startEditing(this.stChart.getCount () - 1,0);
        }, this)

        this.cbChart.on ('select', function (pCB, pRecord, pIndex) {
        	this.cbView.getStore ().load ({
        		params: {'chart' : pRecord.data.className}
        	})
        }, this)

        this.on ('afteredit', function (pEdit) {
        	pEdit.record.commit ()
        }, this)
    }
});
Ext.reg('waox.egChart', WaoX.egChart);