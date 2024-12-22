// General analytics Pie Chart
if (window.merchant_analytics) {
  var counts = merchant_analytics[0]; // get data
  var ctx = document.getElementById("myPieChart");
  var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ["Unverified", "Verified", "Restricted","Incomplete"],
      datasets: [{
        data: [counts['inactiveCount'],counts['activeCount'], counts['restrictedCount'],counts['initiatedCount']],
        //data: [10,15,5,2],
        backgroundColor: ['#C2CCE1', '#4D7CFF', '#FF7784','#647087'],
        hoverBackgroundColor: ['#aeb4bf', '#3365ef', '#ed5a68','#535e72'],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
        datalabels: {
          color: '#FFCE56'
      }
      }],
    },
    options: {
      plugins: {
        // Change options for ALL labels of THIS CHART
        datalabels: {
            color: '#36A2EB'
        }
    },
      maintainAspectRatio: false,
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
      },
      legend: {
        display: false,
      },
      cutoutPercentage: 55,
      
    },
  });
}


// product analytics Pie Chart

if (window.total_products_count_all_industry && window.unique_merchant_products_count_all_industry) {
  var unfulfilled_counts = total_products_count_all_industry - unique_merchant_products_count_all_industry; // get data
  var fulfilled_counts = unique_merchant_products_count_all_industry; // get data
  var productCtx = document.getElementById("productPieChart");
  var productPieChart = new Chart(productCtx, {
    type: 'doughnut',
    data: {
      labels: ["Fulfilled","Unfulfilled"],
      datasets: [{
        //data: [counts['unfulfilled'],counts['fulfilled']],
        data: [fulfilled_counts,unfulfilled_counts],
        //data: [10,15,5,2],
        backgroundColor: ['#4D7CFF', '#FF7784'],
        hoverBackgroundColor: ['#3365ef', '#ed5a68'],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
        datalabels: {
          color: '#FFCE56'
      }
      }],
    },
    options: {
      plugins: {
        // Change options for ALL labels of THIS CHART
        datalabels: {
            color: '#36A2EB'
        }
    },
      maintainAspectRatio: false,
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
      },
      legend: {
        display: false,
      },
      cutoutPercentage: 55,
      
    },
  });
}

