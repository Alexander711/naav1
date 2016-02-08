$(document).ready(function(){
    $.jsDate({
        locale: 'ru'
    });




    $.jqplot('chart-all', stat.charts, {
         title:'Статистика посещений',
         axes:{
            xaxis:{
                renderer:$.jqplot.DateAxisRenderer,
                tickOptions:{showGridline: true, formatString:'%b %#d, %#I %p', locale: 'ru'}
            }
          },
        series: stat.labels,
        legend: {
            show: true,
            placement: 'outsideGrid'
        },
        highlighter: {
            show: true
        },
        cursor: {
            show: true
        }
      });
});