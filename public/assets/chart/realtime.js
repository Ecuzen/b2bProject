// Sample initial data for the chart
var initialData1 = [65, 59, 80, 81, 56, 55];
var initialData2 = [28, 48, 40, 19, 86, 27];
var initialData3 = [20, 30, 45, 12, 72, 80];
var initialData4 = [45, 50, 62, 74, 36, 29];
var initialData5 = [10, 20, 30, 40, 50, 60];
var initialData6 = [75, 85, 70, 90, 65, 55];
var initialData7 = [45, 30, 60, 80, 20, 40];


var labels = [];
var currentDate = new Date();
for (var i = 5; i >= 0; i--) {
  var monthIndex = currentDate.getMonth() - i;
  var monthDate = new Date(currentDate.getFullYear(), monthIndex, 1);
  var monthLabel = monthDate.toLocaleString('en-us', { month: 'long' });
  labels.push(monthLabel);
}
var data = {
  labels: labels,
  datasets: [
    {
      label: 'AEPS',
      data: [],
      backgroundColor: 'rgba(54, 162, 235, 0.2)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1
    },
    {
      label: 'DMT',
      data: [],
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1
    },
    {
      label: 'Q-Transfer',
      data: [],
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      borderColor: 'rgba(75, 192, 192, 1)',
      borderWidth: 1
    },
    {
      label: 'Payout',
      data: [],
      backgroundColor: 'rgba(255, 206, 86, 0.2)',
      borderColor: 'rgba(255, 206, 86, 1)',
      borderWidth: 1
    },
    {
      label: 'Recharge',
      data: [],
      backgroundColor: 'rgba(153, 102, 255, 0.2)',
      borderColor: 'rgba(153, 102, 255, 1)',
      borderWidth: 1
    },
    {
      label: 'BBPS',
      data: [],
      backgroundColor: 'rgba(255, 159, 64, 0.2)',
      borderColor: 'rgba(255, 159, 64, 1)',
      borderWidth: 1
    },
    {
      label: 'UTI',
      data: [],
      backgroundColor: 'rgba(0, 255, 0, 0.2)',
      borderColor: 'rgba(0, 255, 0, 1)',
      borderWidth: 1
    }
  ]
};

// Chart configuration
var config = {
  type: 'line',
  data: data,
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true
      }
    },
    /*animation: {
      duration: 0 // Disable animations for smooth progressive line
    }*/
  }
};

// Create the chart
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, config);

// Function to add a new data point and update the chart
function addDataPoint() {
  if (data.datasets[0].data.length < initialData1.length) {
    // Add new data points from the initial datasets
    data.datasets[0].data.push(initialData1[data.datasets[0].data.length]);
    data.datasets[1].data.push(initialData2[data.datasets[0].data.length]);
    data.datasets[2].data.push(initialData3[data.datasets[0].data.length]);
    data.datasets[3].data.push(initialData4[data.datasets[0].data.length]);
    data.datasets[4].data.push(initialData5[data.datasets[0].data.length]);
    data.datasets[5].data.push(initialData6[data.datasets[0].data.length]);
    data.datasets[6].data.push(initialData7[data.datasets[0].data.length]);

    // Update the chart
    myChart.update();
  }
}

// Add a new data point every 1 second
setInterval(addDataPoint, 1000);
