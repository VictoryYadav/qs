var base_url = '';
var lunch = '';
var dinner = '';
var takeAway = '';
var offers = '';

$(document).ready(function () {
base_url = $('#base_url').val();

  lunch = $('#lunch').val();
  dinner = $('#dinner').val();
  offers = $('#offers').val();
  takeAway = $('#takeAway').val();

  TakeAways();
  // TakeAwaysModel();

  Offers();
  // OffersModel();

  trends();
  // trends_model();

  tableWiseOccupencyLunch();
  tableWiseOccupencyDinner();

  TakeWays();
  TrendKitchenOrders();

  TrendByWeek();
  RatingsTrend();

});



function TakeAways() {

  $.ajax({

    url: base_url+'DashboardController/rest_graph',

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

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.takeaways_totitemdisc_array_value,

            },

            {

              label: dinner,

              backgroundColor: "#dc3545",

              data: data.takeaways_totamt_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: takeAway,

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

    url: base_url+'DashboardController/rest_graph',

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

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.takeaways_totitemdisc_array_value,

            },

            {

              label: dinner,

              backgroundColor: "#dc3545",

              data: data.takeaways_totamt_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: takeAway,

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

    url: base_url+'DashboardController/rest_graph',

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

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.offers_totitemdisc_array_value,

            },

            {

              label: dinner,

              backgroundColor: "#dc3545",

              data: data.offers_totamt_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: offers,

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

    url: base_url+'DashboardController/rest_graph',

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
                       'width':400,
                       'height':350
                      };

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

    url: base_url+'DashboardController/rest_graph',

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

    url: base_url+'DashboardController/rest_graph',

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

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.offers_totitemdisc_array_value,

            },

            {

              label: dinner,

              backgroundColor: "#dc3545",

              data: data.offers_totamt_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: offers,

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

    url: base_url+'DashboardController/rest_graph',

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
        var options = {'title':'Table Wise Occupency for Lunch',
                       'width':400,
                       'height':350
                      };

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

    url: base_url+'DashboardController/rest_graph',

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
        var options = {'title':'Table Wise Occupency for Dinner',
                       'width':400,
                       'height':350
                      };

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

    url: base_url+'DashboardController/rest_graph',

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
        var options = {'title':'Takeways',
                       'width':400,
                       'height':350
                      };

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

    url: base_url+'DashboardController/rest_graph',

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
        var options = {'title':'TrendKitchenOrders',
                       'width':400,
                       'height':350
                      };

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

    url: base_url+'DashboardController/rest_graph',

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
        var options = {'title':'TrendByWeek',
                       'width':400,
                       'height':350
                      };

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

    url: base_url+'DashboardController/rest_graph',

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

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'No of Orders');
        data.addColumn('number', 'Serv Rating');
        data.addColumn('number', 'Amb Rating');
        data.addColumn('number', 'VFM Rating');
        data.addColumn('number', 'Item Rating');
        data.addRows(dataa);

        // Set chart options
        var options = {'title':'Takeways',
                       'width':800,
                       'height':350,
                       vAxis: {
                          title: 'Y-Axis Label',
                          minValue: 1,
                          maxValue: 10,
                          gridlines: { count: 10 } // Customize gridlines
                        },
                      // legend: 'none'
                      };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('rest09'));
        chart.draw(data, options);
      }
    },

    error: function (error) {

      console.log(`Error ${error}`);

    },

  });

}
function tableWiseCount() {
  $.ajax({

    url: base_url+'DashboardController/rest_graph',

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

