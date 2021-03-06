/*
 * File: WaoX.egChart.ui.js
 * Date: Sat Jan 01 2011 00:01:54 GMT-0800 (Pacific Standard Time)
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

Ext.ns('WaoX');
WaoX.egChartUi = Ext.extend(Ext.grid.EditorGridPanel, {
    title: '',
    autoExpandColumn: 'chart',
    region: 'center',

    renderName : function (pValue) {
        result = this.query ('className', pValue)
        
        if (result.getCount () == 1) {
            return result.get (0).data.name
        }
    }, 
    initComponent: function() {
        this.btnAdd = new WaoX.btnAdd ({scope : this})
        this.btnRemove = new WaoX.btnRemove ({scope : this})
        this.stChart = new WaoX.stChart ({scope : this})
        this.cbChart = new WaoX.cbChart ({scope: this})
        this.cbView = new WaoX.cbView ({scope: this})

        Ext.applyIf(this, {
            store: this.stChart,
            columns: [
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'chart',
                    header: 'Chart',
                    id: 'chart',
                    sortable: true,
                    width: 100,
                    editor: this.cbChart,
                    renderer: {
                        fn : this.renderName,
                        scope: this.cbChart.getStore ()
                    }
                },
                {
                    xtype: 'gridcolumn',
                    align: '',
                    dataIndex: 'view',
                    header: 'View',
                    sortable: true,
                    width: 100,
                    editor: this.cbView,
                    renderer: {
                        fn : this.renderName,
                        scope: this.cbView.getStore ()
                    }
                }
            ],
            tbar: {
                xtype: 'toolbar',
                items: [
                    this.btnAdd,
                    this.btnRemove
                ]
            }
        });

        WaoX.egChartUi.superclass.initComponent.call(this);
    }
});