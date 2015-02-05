Ext.ns ('WaoX.charts')

WaoX.charts.chrBarChart = Ext.extend (Ext.chart.BarChart, {
	initComponent : function () {
		WaoX.charts.chrBarChart.superclass.initComponent.call (this);

		this.addEvents ('updateChart')
	}
})

Ext.reg ('waox.chrBarChart', WaoX.charts.chrBarChart);