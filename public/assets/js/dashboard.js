$(function() {
  'use strict'

  var gridLineColor = 'rgba(77, 138, 240, .1)';

  var colors = {
    primary:         "#727cf5",
    secondary:       "#7987a1",
    success:         "#42b72a",
    info:            "#68afff",
    warning:         "#fbbc06",
    danger:          "#ff3366",
    light:           "#ececec",
    dark:            "#282f3a",
    muted:           "#686868"
  }


  // Monthly sales chart start
  if($('#monthly-sales-chart').length) {
    var monthlySalesChart = document.getElementById('monthly-sales-chart').getContext('2d');
      new Chart(monthlySalesChart, {
        type: 'bar',
        data: {
          labels: [
              '01 Jan','02 Jan','03 Jan','04 Jan','05 Jan','06 Jan','07 Jan',
          ],
          datasets: [{
            label: 'Sales',
            data: [150,110,90,115,125,160,190],
            backgroundColor: colors.dark
          },{
            label: 'Purchase',
            data: [190,140,100,110,120,120,150],
            backgroundColor: colors.secondary
          }]
        },
        options: {
          maintainAspectRatio: false,
          legend: {
            display: false,
              labels: {
                display: false
              }
          },
          scales: {
            xAxes: [{
              display: true,
              barPercentage: .3,
              categoryPercentage: .6,
              gridLines: {
                display: false
              },
              ticks: {
                fontColor: '#8392a5',
                fontSize: 10
              }
            }],
            yAxes: [{
              gridLines: {
                color: gridLineColor
              },
              ticks: {
                fontColor: '#8392a5',
                fontSize: 10,
                min: 80,
                max: 200
              }
            }]
          }
        }
      }
    );
  }
  // Monthly sales chart end

});
