Ext.ns ('WaoX.charts')

WaoX.charts.chrColumnChart = Ext.extend (Ext.chart.ColumnChart, {
	initComponent : function () {
		WaoX.charts.chrColumnChart.superclass.initComponent.call (this);

		this.addEventss ('updateChart')
	}
})

Ext.reg ('waox.chrColumnChart', WaoX.charts.chrColumnChart);