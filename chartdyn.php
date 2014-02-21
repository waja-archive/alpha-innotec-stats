<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://code.highcharts.com/stock/highstock.js"></script>
<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>
</head>
<body>
<div id="container" style="width:100%; height:400px;"></div>
<script>
var chart; // global

Highcharts.setOptions({
    global: {
        useUTC: false
    }
});


$(function () {

    var seriesOptions = [],
	colors = Highcharts.getOptions().colors;
        names = ['avgtemp', 'actualtemp','boilertemp'];

    seriesCounter = 0;

    $.each(names, function (i, name) {
        $.getJSON("jsonchart.php?callback=?&context=" + name.toLowerCase(), function (data) {
            seriesOptions[i] = {
                name: name,
                data: data
            };
            seriesCounter++;
            if (seriesCounter == names.length) {
		createChart();
            }
        });
    });


    function createChart() {
        $('#container').highcharts('StockChart', {

            rangeSelector: {
		        buttons: [
			{
					type : 'hour',
					count : 1,
					text : '1h'
			},{
                            type: 'day',
                            count: 1,
                            text: '1d'
                        },{
		            type: 'day',
		            count: 3,
		            text: '3d'
		        }, {
		            type: 'week',
		            count: 1,
		            text: '1w'
		        }, {
		            type: 'month',
		            count: 1,
		            text: '1m'
		        }, {
		            type: 'month',
		            count: 6,
		            text: '6m'
		        }, {
		            type: 'year',
		            count: 1,
		            text: '1y'
		        }, {
		            type: 'all',
		            text: 'All'
		        }],
		        selected: 3
		    },

		yAxis: {
			title: {
				text: 'Temperature (°C)'
			}
		},


            title: {
                text: 'Temperature'
            },

			subtitle: {
				text: 'Built chart at...' // dummy text to reserve space for dynamic subtitle
			},

		plotOptions: {
		    	series: {
//		    		compare: 'percent'
		    	}
		    },


	tooltip: {
		    	pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>', //({point.change}%)
		    	valueDecimals: 1,
			valueSuffix: '°C'
	},
            series: seriesOptions
        });
    }

});
</script>


</body>
</html>
