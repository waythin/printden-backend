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

if (window.inactive_merchants_for_industry || window.active_merchants_for_industry || window.restricted_merchants_for_industry || window.initiated_merchants_for_industry){
  var imfi = inactive_merchants_for_industry;  
  var amfi = active_merchants_for_industry;  
  var rmfi = restricted_merchants_for_industry;  
  var intmfi = initiated_merchants_for_industry;  
  // Bar Chart Example
  var ctx = document.getElementById("myBarChart");
  var myBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Garments", "Spinning", "Knitting", "Dyeing", "Printing", "Embroidery","Washing","Accessories"],
      datasets: [{
        label: "Unverified",
        backgroundColor: "#C2CCE1",
        hoverBackgroundColor: "#aeb4bf",
        borderColor: "#4e73df",
        data: [imfi['Garments'], imfi['Spinning'], imfi['Knitting'], imfi['Dyeing'],imfi['Printing'],imfi['Embroidery'], imfi['Washing'], imfi['Accessories']],
      },
      {
        label: "Verified",
        backgroundColor: "#4D7CFF",
        hoverBackgroundColor: "#3365ef",
        borderColor: "#4e73df",
        data: [amfi['Garments'], amfi['Spinning'], amfi['Knitting'], amfi['Dyeing'],amfi['Printing'],amfi['Embroidery'], amfi['Washing'], amfi['Accessories']],
      },
      {
        label: "Restricted",
        backgroundColor: "#FF7784",
        hoverBackgroundColor: "#ed5a68",
        borderColor: "#4e73df",
        data: [rmfi['Garments'], rmfi['Spinning'], rmfi['Knitting'], rmfi['Dyeing'],rmfi['Printing'],rmfi['Embroidery'], rmfi['Washing'], rmfi['Accessories']],
      },
      {
        label: "Incomplete",
        backgroundColor: "#647087",
        hoverBackgroundColor: "#535e72",
        borderColor: "#4e73df",
        data: [intmfi['Garments'], intmfi['Spinning'], intmfi['Knitting'], intmfi['Dyeing'],intmfi['Printing'],intmfi['Embroidery'], intmfi['Washing'], intmfi['Accessories']],
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
            unit: 'month'
          },
          gridLines: {
            display: false,
            drawBorder: false
          },
          ticks: {
            maxTicksLimit: 8
          },
          maxBarThickness: 20,
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
        display: false,
      },
      tooltips: {
        titleMarginBottom: 10,
        titleFontColor: '#6e707e',
        titleFontSize: 14,
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
        callbacks: {
          label: function(tooltipItem, chart) {
            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
          }
        }
      },
      onClick: function(e) {
        var activePoints = myBarChart.getElementAtEvent(e);
        if (activePoints.length > 0) {
            var clickedElementIndex = activePoints[0]._index;
            var datasetIndex = activePoints[0]._datasetIndex;
           
            var value = myBarChart.data.datasets[datasetIndex].data[clickedElementIndex];

            var industry_name = myBarChart.data.labels[clickedElementIndex];
            var status = myBarChart.data.datasets[datasetIndex].label;
            // console.log(industry_name);
            // console.log(status);
            console.log(`Category: ${industry_name}, ${status}: ${value}`);

            $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              type: 'post',
              url: "/admin/analytics/insudtry/merchant-list-by-status",
              //dataType: "JSON",
              data: {
                industry_name: industry_name,
                status: status
              },

              success: function(response) {
                console.log(response.data);
                if (response.success == true) {
                    console.log(response.data);
  
                   $('#analytics_merchant_modal .modal-content').html(response.html);

                   // Show the modal
                   $('#analytics_merchant_modal').modal('show');

                    // Toast.fire({
                    //     icon: 'success',
                    //     title: response.message
                    // })
                } 
              },
              error: function(xhr) {
                  console.log(xhr);
                  // Toast.fire({
                  //     icon: 'error',
                  //     title: "Something went wrong"
                  // })
              }
            });
        }
      }

    }
  });
}

// product analytics Bar Chart
if (window.industriesData){
// Extracting labels
var labels = industriesData.map(function(industry) {
  return industry.name;
});

// Extracting data for "Total"
var totalData = industriesData.map(function(industry) {
  return industry.totalProductsCount;
});

// Extracting data for "Fulfilled"
var fulfilledData = industriesData.map(function(industry) {
  return industry.uniqueMerchantProductsCount;
});

// Extracting data for "Unfulfilled"
var unfulfilledData = industriesData.map(function(industry) {
  return industry.totalProductsCount - industry.uniqueMerchantProductsCount;
});

  // Bar Chart Example
  var productCtx = document.getElementById("productBarChart");
  var productBarChart = new Chart(productCtx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: "Total",
        backgroundColor: "#C2CCE1",
        hoverBackgroundColor: "#aeb4bf",
        borderColor: "#4e73df",
        data: totalData,
      },
      {
        label: "Fulfilled",
        backgroundColor: "#4D7CFF",
        hoverBackgroundColor: "#3365ef",
        borderColor: "#4e73df",
        data: fulfilledData,
      },
      {
        label: "Unfulfilled",
        backgroundColor: "#FF7784",
        hoverBackgroundColor: "#ed5a68",
        borderColor: "#4e73df",
        data: unfulfilledData,
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
            unit: 'month'
          },
          gridLines: {
            display: false,
            drawBorder: false
          },
          ticks: {
            maxTicksLimit: 8
          },
          maxBarThickness: 20,
        }],
        yAxes: [{
          ticks: {
            min: 0,
            // max: total_products_count_all_industry,
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
        display: false,
      },
      tooltips: {
        titleMarginBottom: 10,
        titleFontColor: '#6e707e',
        titleFontSize: 14,
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
        callbacks: {
          label: function(tooltipItem, chart) {
            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
          }
        }
      },
    }
  });
}
