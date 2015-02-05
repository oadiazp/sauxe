Ext.ns ('WaoX.charts')

WaoX.charts.chrLineChart = Ext.extend (Ext.chart.LineChart, {
	initComponent : function () {
		WaoX.charts.chrLineChart.superclass.initComponent.call (this);

		this.addEvents ('updateChart')
	}
})

Ext.reg ('waox.chrLineChart', WaoX.charts.chrLineChart);