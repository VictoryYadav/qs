$(document).ready(function () {

  RevenueAndDiscounts();

  RevenueAndDiscountsModel();

  OrdersByHour();

  OrdersByHourModel();

  BillsAndRatings();

  BillsAndRatingsModel();

});



function RevenueAndDiscounts() {

  $.ajax({

    url: "/ajax/graphs/food_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "RevenueAndDiscounts",

    },

    success: function (data) {

      console.log(data.revenue_and_discounts_labal);

      console.log(data.revenue_and_discounts_totitemdisc_value);

      console.log(data.revenue_and_discounts_totamt_value);

      var ctx = document.getElementById("food01").getContext("2d");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.revenue_and_discounts_labal,

          datasets: [

            {

              label: "Lunch",

              backgroundColor: "#1091e8",

              data: data.revenue_and_discounts_totitemdisc_value,

            },

            {

              label: "Dinner",

              backgroundColor: "#28a745",

              data: data.revenue_and_discounts_totamt_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: "Revenue And Discounts",

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



function RevenueAndDiscountsModel() {
var range = $('#rev_dis_range').val();
  $.ajax({

    url: "/ajax/graphs/food_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "RevenueAndDiscounts",
      date: range

    },

    success: function (data) {

      console.log(data.revenue_and_discounts_labal);

      console.log(data.revenue_and_discounts_totitemdisc_value);

      console.log(data.revenue_and_discounts_totamt_value);

      var ctx = document.getElementById("food01_model").getContext("2d");

      $("#food01Title").html("Revenue And Discounts");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.revenue_and_discounts_labal,

          datasets: [

            {

              label: "Lunch",

              backgroundColor: "#1091e8",

              data: data.revenue_and_discounts_totitemdisc_value,

            },

            {

              label: "Dinner",

              backgroundColor: "#28a745",

              data: data.revenue_and_discounts_totamt_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: "Revenue And Discounts",

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



function OrdersByHour() {

  $.ajax({

    url: "/ajax/graphs/food_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "OrdersByHour",

    },

    success: function (data) {

      var ctx = document.getElementById("food02").getContext("2d");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.revenue_and_discounts_labal,

          datasets: [

            {

              label: "Lunch",

              backgroundColor: "#1091e8",

              data: data.revenue_and_discounts_totitemdisc_value,

            },

            {

              label: "Dinner",

              backgroundColor: "#28a745",

              data: data.revenue_and_discounts_totamt_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: "Orders by Hour",

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



function OrdersByHourModel() {
var range = $('#orders_by_hour_range').val();
  $.ajax({

    url: "/ajax/graphs/food_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "OrdersByHour",
      date: range

    },

    success: function (data) {

      var ctx = document.getElementById("food02_model").getContext("2d");

      $("#food02Title").html("Orders by Hour");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.revenue_and_discounts_labal,

          datasets: [

            {

              label: "Lunch",

              backgroundColor: "#1091e8",

              data: data.revenue_and_discounts_totitemdisc_value,

            },

            {

              label: "Dinner",

              backgroundColor: "#28a745",

              data: data.revenue_and_discounts_totamt_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: "Orders by Hour",

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



function BillsAndRatings() {

  $.ajax({

    url: "/ajax/graphs/food_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "BillsAndRatings",

    },

    success: function (data) {

      var ctx = document.getElementById("food03").getContext("2d");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.revenue_and_discounts_labal,

          datasets: [

            {

              label: "Lunch",

              backgroundColor: "#1091e8",

              data: data.revenue_and_discounts_totitemdisc_value,

            },

            {

              label: "Dinner",

              backgroundColor: "#28a745",

              data: data.revenue_and_discounts_totamt_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: "Bills and Ratings",

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



function BillsAndRatingsModel() {
  var range = $('#bills_rating_range').val();
  $.ajax({

    url: "/ajax/graphs/food_ajax.php",

    type: "POST",

    dataType: "json",

    data: {

      type: "BillsAndRatings",
      date: range

    },

    success: function (data) {

      var ctx = document.getElementById("food03_model").getContext("2d");

      $("#food03Title").html("Bills and Ratings");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.revenue_and_discounts_labal,

          datasets: [

            {

              label: "Lunch",

              backgroundColor: "#1091e8",

              data: data.revenue_and_discounts_totitemdisc_value,

            },

            {

              label: "Dinner",

              backgroundColor: "#28a745",

              data: data.revenue_and_discounts_totamt_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: "Bills and Ratings",

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

