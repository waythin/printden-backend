
function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}
// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["Today", "-D1", "-D2", "-D3", "-D4", "-D5", "-D6", "-D7", "-D8", "-D9", "-D10", "-D11","-D12","-D13","-D14"],
    datasets: [{
      label: "Unverified",
      lineTension: 0.3,
      backgroundColor: "rgba(0, 196, 184, 0.05)",
      borderColor: "rgba(0, 196, 184, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(0, 196, 184, 1)",
      pointBorderColor: "rgba(0, 196, 184, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(0, 196, 184, 1)",
      pointHoverBorderColor: "rgba(0, 196, 184, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      //data: [40, 20, 30, 15, 10, 20, 15, 25, 20, 30, 25, 30,45,45,41],
      data: [inactive_analytics['d-0'], inactive_analytics['d-1'], inactive_analytics['d-2'], inactive_analytics['d-3'], inactive_analytics['d-4'], inactive_analytics['d-4'], inactive_analytics['d-6'], inactive_analytics['d-7'], inactive_analytics['d-8'], inactive_analytics['d-9'], inactive_analytics['d-10'], inactive_analytics['d-11'],inactive_analytics['d-12'],inactive_analytics['d-13'],inactive_analytics['d-14']],
    },{
      label: "Verified",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgba(77, 124, 255, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      //data: [10, 10, 30, 10, 30, 20, 15, 15, 25, 35, 29, 39,41,25,11],
      data: [active_analytics['d-0'], active_analytics['d-1'], active_analytics['d-2'], active_analytics['d-3'], active_analytics['d-4'], active_analytics['d-4'], active_analytics['d-6'], active_analytics['d-7'], active_analytics['d-8'], active_analytics['d-9'], active_analytics['d-10'], active_analytics['d-11'],active_analytics['d-12'],active_analytics['d-13'],active_analytics['d-14']],
    }
  ],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 15
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: total_merchent_count,
          maxTicksLimit: 8,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: true,
      position: 'right', // Set legend position to bottom
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
        }
      }
    }
  }
});
