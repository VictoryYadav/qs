var base_url = '';
var lunch = '';
var dinner = '';

var revenueAndDiscounts = '';
var ordersByHour = '';
var billsAndRatings = '';
var filterModes = '';

$(document).ready(function () {
  base_url = $('#base_url').val();

  lunch = $('#lunch').val();
  dinner = $('#dinner').val();
  revenueAndDiscounts = $('#revenueAndDiscounts').val();
  ordersByHour = $('#ordersByHour').val();
  billsAndRatings = $('#billsAndRatings').val();
  filterModes = $('#changeFilter').val();

  RevenueAndDiscounts();

  OrdersByHour();

  BillsAndRatings();

});



function RevenueAndDiscounts() {

  $.ajax({

    url: base_url+'restaurant/food_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "RevenueAndDiscounts",
      filterModes:filterModes

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

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.revenue_and_discounts_totitemdisc_value,

            },

            {

              label: dinner,

              backgroundColor: "#28a745",

              data: data.revenue_and_discounts_totamt_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: revenueAndDiscounts,

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

    url: base_url+'restaurant/food_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "OrdersByHour",
      filterModes:filterModes

    },

    success: function (data) {

      var ctx = document.getElementById("food02").getContext("2d");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.revenue_and_discounts_labal,

          datasets: [

            {

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.revenue_and_discounts_totitemdisc_value,

            },

            {

              label: dinner,

              backgroundColor: "#28a745",

              data: data.revenue_and_discounts_totamt_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: ordersByHour,

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

    url: base_url+'restaurant/food_graph',

    type: "POST",

    dataType: "json",

    data: {

      type: "BillsAndRatings",
      filterModes:filterModes

    },

    success: function (data) {

      var ctx = document.getElementById("food03").getContext("2d");

      var myChart = new Chart(ctx, {

        type: "bar",

        data: {

          labels: data.revenue_and_discounts_labal,

          datasets: [

            {

              label: lunch,

              backgroundColor: "#1091e8",

              data: data.revenue_and_discounts_totitemdisc_value,

            },

            {

              label: dinner,

              backgroundColor: "#28a745",

              data: data.revenue_and_discounts_totamt_value,

            },

          ],

        },

        options: {

          title: {

            display: true,

            text: billsAndRatings,

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



