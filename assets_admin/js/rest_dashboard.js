var base_url = '';
var lunch = '';
var dinner = '';
var takeAway = '';
var offers = '';
var filterModes = '';

$(document).ready(function () {
base_url = $('#base_url').val();

  lunch = $('#lunch').val();
  dinner = $('#dinner').val();
  offers = $('#offers').val();
  takeAway = $('#takeAway').val();
  filterModes = $('#changeFilter').val();

  TakeAways();

  Offers();

  tableWiseOccupencyLunch();

  TrendKitchenOrders();

  RatingsTrend();

});



function TakeAways() {

  $.ajax({

    url: base_url+'restaurant/rest_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "TakeAways",
      filterModes : filterModes

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

function Offers() {

  $.ajax({

    url: base_url+'restaurant/rest_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "offers",
      filterModes : filterModes

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


function tableWiseOccupencyLunch() {
  $.ajax({

    url: base_url+'restaurant/rest_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "tableWiseOccupencyLunch",
      filterModes : filterModes

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
        var options = {'title':'Table Wise Occupancy',
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

function TrendKitchenOrders() {
  $.ajax({

    url: base_url+'restaurant/rest_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "TrendKitchenOrders",
      filterModes : filterModes

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
        var options = {'title':'KitchenOrders',
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

function RatingsTrend() {
  $.ajax({

    url: base_url+'restaurant/rest_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "RatingsTrend",
      filterModes : filterModes

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
        var options = {'title':'Ratings',
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

    url: base_url+'restaurant/rest_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "tableWiseCount",
      filterModes : filterModes

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

