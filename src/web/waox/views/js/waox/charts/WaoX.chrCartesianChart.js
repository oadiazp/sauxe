Ext.ns ('WaoX.charts')

WaoX.charts.chrCartesianChart = Ext.extend (Ext.chart.CartesianChart, {
	initComponent : function () {
		WaoX.charts.chrCartesianChart.superclass.initComponent.call (this);

		this.addEvents ('updateChart')
	}
})

Ext.reg ('waox.chrCartesianChart', WaoX.charts.chrCartesianChart);