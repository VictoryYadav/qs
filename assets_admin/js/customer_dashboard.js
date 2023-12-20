var base_url = '';
var lunch = '';
var dinner = '';

var customerFootfalls = '';
var customerOrder = '';
var paymentModes = '';


$(document).ready(function () {
  base_url = $('#base_url').val();
  
  lunch = $('#lunch').val();
  dinner = $('#dinner').val();
  customerFootfalls = $('#customerFootfalls').val();
  customerOrder = $('#customerOrder').val();
  paymentModes = $('#paymentModes').val();
  
  $("#customer").click(function (e) {

    $("#restaurant-parent-div").css("display", "none");

    $("#food-parent-div").css("display", "none");

    $("#customer-parent-div").css("display", "block");

  });

  $("#food").click(function (e) {

    $("#restaurant-parent-div").css("display", "none");

    $("#food-parent-div").css("display", "block");

    $("#customer-parent-div").css("display", "none");

  });

  $("#restaurant").click(function (e) {

    $("#restaurant-parent-div").css("display", "block");

    $("#food-parent-div").css("display", "none");

    $("#customer-parent-div").css("display", "none");

  });



  // call customer function

  customersFootfalls();

  customersFootfallsModel();

  customersOrderValue();

  customersOrderValueModel();
  paymentMode();
  paymentModeModel();


});



function customersFootfalls() {

  $.ajax({

    url: base_url+'DashboardController/customer_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "footfall",

    },

    success: function (data) {

      var ctx = document.getElementById("cust01").getContext("2d");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.footfalls_lunch_array_labal,

          datasets: [

            {

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.footfalls_lunch_array_value,

            },

            {

              label: dinner,

              backgroundColor: "orange",

              data: data.footfalls_dinner_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: customerFootfalls,

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



function customersFootfallsModel() {
  var range = $('#date_range').val();
  // alert(range);
  $.ajax({

    url: base_url+'DashboardController/customer_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "footfall",
      date: range

    },

    success: function (data) {

      var ctx = document.getElementById("cust01_model").getContext("2d");

      $("#cust01Title").html(customerFootfalls);

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.footfalls_lunch_array_labal,

          datasets: [

            {

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.footfalls_lunch_array_value,

            },

            {

              label: dinner,

              backgroundColor: "orange",

              data: data.footfalls_dinner_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: customerFootfalls,

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



function customersOrderValue() {
  // var range = $('#order_value_range').val();
  $.ajax({

    url: base_url+'DashboardController/customer_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "orderValue",
      // date: range

    },

    success: function (data) {

      var ctx = document.getElementById("cust02").getContext("2d");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.order_value_array_labal,

          datasets: [

            {

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.order_value_lunch_array_value,

            },

            {

              label: dinner,

              backgroundColor: "orange",

              data: data.order_value_dinner_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: customerOrder,

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



function customersOrderValueModel() {
  var range = $('#order_value_range').val();
  // alert(range);
  $.ajax({

    url: base_url+'DashboardController/customer_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "orderValue",
      date: range

    },

    success: function (data) {

      var ctx = document.getElementById("cust02_model").getContext("2d");

      $("#cust02Title").html(customerOrder);

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.order_value_array_labal,

          datasets: [

            {

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.order_value_lunch_array_value,

            },

            {

              label: dinner,

              backgroundColor: "orange",

              data: data.order_value_dinner_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: customerOrder,

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
function paymentModeModel() {
  var range = $('#payment_mode_range').val();
  // alert(range);
  $.ajax({

    url: base_url+'DashboardController/customer_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "paymentMode",
      date: range

    },

    success: function (data) {

      var ctx = document.getElementById("cust03_model").getContext("2d");

      $("#cust03Title").html(paymentModes);

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.payment_mode_array_label,

          datasets: [

            {

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.payment_mode_lunch_array_value,

            },

            {

              label: dinner,

              backgroundColor: "orange",

              data: data.payment_mode_dinner_array_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: paymentModes,

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
function paymentMode(){
   $.ajax({

      url: base_url+'DashboardController/customer_graph',

      type: "POST",

      dataType: "json",

      data: {

        type: "paymentMode",

      },

      success: function (data) {
        var ctx = document.getElementById("cust03").getContext("2d");

        var myChart = new Chart(ctx, {

          type: "bar",

          data: {

            labels: data.payment_mode_array_label,

            datasets: [

              {

                label: lunch,

                backgroundColor: "#1091e8",

                data: data.payment_mode_lunch_array_value,

              },

              {

                label: dinner,

                backgroundColor: "orange",

                data: data.payment_mode_dinner_array_value,

              },

            ],

          },

          options: {

            title: {

              display: true,

              text: paymentModes,

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
      }
  });
}

