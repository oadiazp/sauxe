Ext.ns ('WaoX.charts')

WaoX.charts.chrPieChart = Ext.extend (Ext.chart.PieChart, {
	initComponent : function () {
		WaoX.charts.chrPieChart.superclass.initComponent.call (this);

		this.addEvents ('updateChart')
	}
})

Ext.reg ('waox.chrPieChart', WaoX.charts.chrPieChart);