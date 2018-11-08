<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="pragma" content="no-cache"/>
  <meta http-equiv="pragma" content="no-store"/>
  <meta http-equiv="cache-control" content="no-cache"/>
  <meta http-equiv="Expires" content="-1"/>
  <title></title>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="/js/dark-unica.js"></script>
</head>
<style media="screen">
  body{
    background-color: #2a2a2b;
  }
</style>
<body>
  <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</body>
<script type="text/javascript">

  Highcharts.setOptions({
    global: {
      useUTC: false
    }
  });
  
(function(){Math.clamp=function(a,b,c){return Math.max(b,Math.min(c,a));}})();

  var charts = Highcharts.chart('container', {
    chart: {
		rangeSelector: {
        buttons: [{
            count: 1,
            type: 'minute',
            text: '1M'
        }, {
            count: 5,
            type: 'minute',
            text: '5M'
        }, {
            type: 'all',
            text: 'All'
        }],
        inputEnabled: false,
        selected: 0
    },

      type: 'spline',
      animation: Highcharts.svg, // don't animate in old IE
      marginRight: 10,
      events: {
        load: function() {
          // set up the updating of the chart each second
          var series = this.series[0];
		  var gets,i=0;
		  var oldtime =0;
		  var x,y;
		  
		  
		  
		  
		  
          setInterval(function() {
			  
		
			  $.ajax({
            url:'./getvalue.php?mod=2',
			async:false,
            success:function(data){
				gets = JSON.parse(data);
				
            }
        })
		
		//console.log(gets);
		var freq = gets[0]['freq'];
		var time = new Date(gets[0]['date']);
			time.setHours(gets[0]['time'].split(":")[0]);
			time.setMinutes(gets[0]['time'].split(":")[1]);
			time.setSeconds(gets[0]['time'].split(":")[2]);
			
			i++;
			if(oldtime<time.getTime()){
				
				//console.log(freq);
              x = time.getTime(); // current time
              y = Math.log10(freq);
            series.addPoint([x, y], true, true);
			oldtime = x;
			}
			
			
          }, 1000);
        }
      }
    },
    title: {
      text: 'Live Earthquake Data'
    },
    xAxis: {
		
      type: 'datetime',
      tickPixelInterval: 150,
						
	 // min:new Date().getTime()-10000,
		  
    },
    yAxis: {
      title: {
        text: 'Value'
      },
      plotLines: [{
        value: 0,
        width: 1,
        color: '#808080'
      }]
    },
    tooltip: {
      formatter: function() {
        return '<b>' + this.series.name + '</b><br/>' +
          Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
          Highcharts.numberFormat(this.y, 2);
      }
    },
    legend: {
      enabled: false
    },
    exporting: {
      enabled: false
    },
    series: [{
      name: 'Scale',
      data: (function() {
        // generate an array of random data
        var data = [],i;
		var gets;
		  $.ajax({
            url:'./getvalue.php?mod=1',
			async:false,
            success:function(getData){
				
				gets = JSON.parse(getData);
				
            }
        })
		
		//console.log(gets);
		if(gets){
		
		var length = gets.length-1
        for (i = 0; i <= length; i ++) {
			var freq = gets[i]['freq'];
			var time = new Date(gets[length-i]['date']);
			time.setHours(gets[length-i]['time'].split(":")[0]);
			time.setMinutes(gets[length-i]['time'].split(":")[1]);
			time.setSeconds(gets[length-i]['time'].split(":")[2]);
		//console.log(freq)
          data.push({
            x: time.getTime(),
            y: Math.log10(freq*1)
          });
        }
        
		
		}
			else{
			for (i = -15; i <= 0; i += 1) {
			data.push({
            x: new Date().getTime()+i ,
            y: 1
			});
				
			}
			}
		return data;
      }())
    }]
  });
  

		  
</script>

</html>


