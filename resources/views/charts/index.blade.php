@extends('layouts.master')
@section('title', 'Profit Chart')

@push('style')

@endpush

@push('custom-scripts')
<script>
  if ($("#pieChart").length) {
      var pieChartCanvas = $("#pieChart")
          .get(0)
          .getContext("2d");
      var pieChart = new Chart(pieChartCanvas, {
          type: "pie",
          data: {
              datasets: [
                  {
                      data: ["{{$stock_added['sum_stock_spend']}}", "{{$expense['sum_expense']}}", "{{$recieve['sum_recieve']}}"],
                      backgroundColor: [
                        "rgba(255, 99, 132, 0.5)",
                        "rgba(54, 162, 235, 0.5)",
                        'rgba(255, 206, 86, 0.5)'
                      ],
                      borderColor: [
                        "rgba(255, 99, 132, 0.5)",
                        "rgba(54, 162, 235, 0.5)",
                        'rgba(255, 206, 86, 0.5)'
                      ]
                  }
              ],
              labels: ["Total Stock Price", "Total Expense", "Total Recieve"]
          },
          options: {
              responsive: true,
              animation: {
                  animateScale: true,
                  animateRotate: true
              },
              legend: {
                  display: false
              },
              legendCallback: function(chart) {
                  var text = [];
                  text.push('<div class="chartjs-legend"><ul>');
                  for (
                      var i = 0;
                      i < chart.data.datasets[0].data.length;
                      i++
                  ) {
                      text.push(
                          '<li><span style="background-color:' +
                              chart.data.datasets[0].backgroundColor[i] +
                              '">'
                      );
                      text.push("</span>");
                      if (chart.data.labels[i]) {
                          text.push(chart.data.labels[i]);
                      }
                      text.push("</li>");
                  }
                  text.push("</div></ul>");
                  return text.join("");
              }
          }
      });
      document.getElementById(
          "pie-chart-legend"
      ).innerHTML = pieChart.generateLegend();
  }
</script>
@endpush

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="p-4 pr-5 border-bottom bg-light d-sm-flex justify-content-between">
        <iframe src="{{route('profit-chart.pdf.download')}}" style="display:none;" name="frame"></iframe>

        <h4 class="card-title mb-0">Profit chart</h4> 
        <span>
            <button title="Print Order" onclick="frames['frame'].print()" class="btn btn-dark btn-block">
                Download PDF
            </button>
        </span>

        <div id="pie-chart-legend" class="mr-4"></div>
      </div>
      <div class="card-body d-flex">
        <canvas class="my-auto" id="pieChart" height="130"></canvas>
      </div>
    </div>
</div>
@endsection