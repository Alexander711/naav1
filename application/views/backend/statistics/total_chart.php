<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<script type="text/javascript">
    $(document).ready(function(){
      $.jsDate({
          locale: 'ru'
      });
      //var line1=[['23-May-08', 578.55], ['20-Jun-08', 566.5], ['25-Jul-08', 480.88], ['22-Aug-08', 509.84],
      //    ['26-Sep-08', 454.13], ['24-Oct-08', 379.75], ['21-Nov-08', 303], ['26-Dec-08', 308.56],
      //    ['23-Jan-09', 299.14], ['20-Feb-09', 346.51], ['20-Mar-09', 325.99], ['24-Apr-09', 386.15]];
      var line1 = <?= $company_line; ?>;
      $.jqplot('chart-all', [line1], {
         title:'Статистика просмотров страницы',
         axes:{
            xaxis:{
                renderer:$.jqplot.DateAxisRenderer,
                tickOptions:{showGridline: true, formatString:'%b %#d, %#I %p', locale: 'ru'}
            }
          },
        series:[
            {label: 'Просмотры страниц компаний'}
        ],
        legend: {
            show: true,
            placement: 'outsideGrid'
        },
        highlighter: {
            show: true
        },
        cursor: {
            show: true,
            zoom: true
        }
      });
    });
</script>
<div id="chart-all" style="width:100%; height: 300px;"></div>