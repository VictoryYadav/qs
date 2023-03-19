$(document).ready(function () {

  TakeAways();

  TakeAwaysModel();

  Offers();

  OffersModel();
  trends();
  trends_model();
  tableWiseOccupencyLunch();
  tableWiseOccupencyDinner();
  TakeWays();
  TrendKitchenOrders();
  TrendByWeek();
  RatingsTrend();

});



function TakeAways() {

  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "TakeAways",

    },

    success: function (data) {

      var ctx = document.getElementById("rest01").getContext("2d");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.takeaways_array_labal,

          datasets: [

            {

              label: "Lunch",

              backgroundColor: "#1091e8",

              data: data.takeaways_totitemdisc_array_value,

            },

            {

              label: "Dinner",

              backgroundColor: "#dc3545",

              data: data.takeaways_totamt_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: "TakeAways",

          },

          scales: {

            yAxes: [

              {

                ticks: {

                  beginAtZero: true,

                },

              },

            ],

          },

        },

      });

    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}



function TakeAwaysModel() {
 var range = $('#take_away_range').val();
  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "TakeAways",
      date: range

    },

    success: function (data) {

      var ctx = document.getElementById("rest01_model").getContext("2d");

      $("#rest01Title").html("TakeAways");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.takeaways_array_labal,

          datasets: [

            {

              label: "Lunch",

              backgroundColor: "#1091e8",

              data: data.takeaways_totitemdisc_array_value,

            },

            {

              label: "Dinner",

              backgroundColor: "#dc3545",

              data: data.takeaways_totamt_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: "TakeAways",

          },

          scales: {

            yAxes: [

              {

                ticks: {

                  beginAtZero: true,

                },

              },

            ],

          },

        },

      });

    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}



function Offers() {

  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "offers",

    },

    success: function (data) {

      var ctx = document.getElementById("rest02").getContext("2d");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.offers_array_labal,

          datasets: [

            {

              label: "Lunch",

              backgroundColor: "#1091e8",

              data: data.offers_totitemdisc_array_value,

            },

            {

              label: "Dinner",

              backgroundColor: "#dc3545",

              data: data.offers_totamt_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: "Offers",

          },

          scales: {

            yAxes: [

              {

                ticks: {

                  beginAtZero: true,

                },

              },

            ],

          },

        },

      });

    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}

function trends() {

  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "trend90Days",

    },

    success: function (dataa) {

       google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'Amount');
        console.log(dataa);
        data.addRows(dataa);

        // Set chart options
        var options = {'title':'Trends 90 Days',
                       'width':500,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('rest03'));
        chart.draw(data, options);
        // alert("Done");
      }
    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}
function trends_model() {
  var range = $('#trends_range').val();
  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "trend90Days",
      period: range

    },

    success: function (dataa) {

       google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'Amount');
        console.log(dataa);
        data.addRows(dataa);

        // Set chart options
        var options = {'title':'Trends 90 Days','width':700,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('rest03_model'));
        chart.draw(data, options);
        // alert("Done");
      }
    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}



function OffersModel() {
var range = $('#offer_range').val();
  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "offers",
      date: range

    },

    success: function (data) {

      var ctx = document.getElementById("rest02_model").getContext("2d");

      $("#rest02Title").html("Offers");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.offers_array_labal,

          datasets: [

            {

              label: "Lunch",

              backgroundColor: "#1091e8",

              data: data.offers_totitemdisc_array_value,

            },

            {

              label: "Dinner",

              backgroundColor: "#dc3545",

              data: data.offers_totamt_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: "Offers",

          },

          scales: {

            yAxes: [

              {

                ticks: {

                  beginAtZero: true,

                },

              },

            ],

          },

        },

      });

    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}

function tableWiseOccupencyLunch() {
  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "tableWiseOccupencyLunch",

    },

    success: function (dataa) {

       google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Table Number');
        data.addColumn('number', 'Amount');
        data.addRows(dataa);

        // Set chart options
        var options = {'title':'Table Wise Occupency for Lunch','width':700,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('rest04'));
        chart.draw(data, options);
        // alert("Done");
      }
    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}
function tableWiseOccupencyDinner() {
  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "tableWiseOccupencyDinner",

    },

    success: function (dataa) {

       google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Table Number');
        data.addColumn('number', 'Amount');
        data.addRows(dataa);

        // Set chart options
        var options = {'title':'Table Wise Occupency for Dinner','width':700,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('rest05'));
        chart.draw(data, options);
        // alert("Done");
      }
    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}
function TakeWays() {
  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "TakeWays",

    },

    success: function (dataa) {

       google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Table Number');
        data.addColumn('number', 'Amount');
        data.addRows(dataa);

        // Set chart options
        var options = {'title':'Takeways','width':700,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('rest06'));
        chart.draw(data, options);
        // alert("Done");
      }
    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}
function TrendKitchenOrders() {
  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "TrendKitchenOrders",

    },

    success: function (dataa) {

       google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'Quantity');
        data.addRows(dataa);

        // Set chart options
        var options = {'title':'TrendKitchenOrders','width':700,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('rest07'));
        chart.draw(data, options);
        // alert("Done");
      }
    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}
function TrendByWeek() {
  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "TrendByWeek",

    },

    success: function (dataa) {

       google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Week');
        data.addColumn('number', 'Quantity');
        data.addRows(dataa);

        // Set chart options
        var options = {'title':'TrendByWeek','width':700,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.BarChart(document.getElementById('rest08'));
        chart.draw(data, options);
        // alert("Done");
      }
    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}
function RatingsTrend() {
  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "RatingsTrend",

    },

    success: function (dataa) {

       google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        // var data = new google.visualization.DataTable();
        var data = google.visualization.arrayToDataTable(dataa);
        // data.addColumn('string', 'Number Of Orders');
        // data.addColumn('number', 'Quantity');
        // data.addRows(dataa);

        // Set chart options
        // var options = {'title':'RatingsTrend','width':700,
        //                'height':350};
        var options = {
          title: 'RatingsTrend',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('rest09'));
        chart.draw(data, options);
        // alert("Done");
      }
    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}
function tableWiseCount() {
  $.ajax({

    url: "ajax/graphs/rest_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "tableWiseCount",

    },

    success: function (dataa) {

       google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Table Number');
        data.addColumn('Customers', 'Number of Customers');
        data.addRows(dataa);

        // Set chart options
        var options = {'title':'Table Wise Occupency','width':700,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('rest05'));
        chart.draw(data, options);
        // alert("Done");
      }
    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}

