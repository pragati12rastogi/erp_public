<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Profit'],
          ['Total Stock Price (Rs.{{$stock_added["sum_stock_spend"]}})',     {{$stock_added['sum_stock_spend']}}],
          ['Total Expense (Rs. {{$expense["sum_expense"]}})',      {{$expense["sum_expense"]}}],
          ['Total Recieve (Rs. {{$recieve["sum_recieve"]}})',  {{$recieve["sum_recieve"]}}],
       
        ]);

        var options = {
          title: 'Profit Chart'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
      <div style="margin: auto 20%;">
          <div id="piechart" style="width: 1000px; height: 1000px;"></div>
      </div>
    
  </body>
</html>
