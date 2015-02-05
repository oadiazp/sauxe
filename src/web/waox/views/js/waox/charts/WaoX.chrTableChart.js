	Ext.ns ('WaoX.charts')

WaoX.charts.chrTableChart = Ext.extend (Ext.grid.GridPanel, {
	initComponent : function () {
		WaoX.charts.chrTableChart.superclass.initComponent.call (this);

		this.addEvents ('updateChart')
	}
})
Ext.reg ('waox.chrTableChart', WaoX.charts.chrTableChart);