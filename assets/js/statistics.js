$(document).ready(function(){
    $.jsDate({
        locale: 'ru'
    });



    companyChart = $.jqplot('company-page-chart', [stat.company], {
         title:'Статистика посещений страницы компании',
         axes:{
            xaxis:{
                renderer:$.jqplot.DateAxisRenderer,
                //tickInterval: "1 weeks",
                tickOptions:{showGridline: true, formatString:'%b %#d, %#I %p', locale: 'ru'}
            }
          },

        legend: {
            show: false
        },
        series: [
        	{color: '#3399ff'}
        ],
        highlighter: {
            show: true
        },
        cursor: {
            show: true, 
            zoom: true
        }
      });
      if (stat.total_news_visits.length > 0)
      {
      	$('#news-pages-chart').css('height', '300px');
      	newsChart = $.jqplot('news-pages-chart', [stat.total_news_visits], {
      	   title:'Статистика посещений страниц новостей компании',
      	   axes:{
      	      xaxis:{
      	          renderer:$.jqplot.DateAxisRenderer,
      	          //tickInterval: '4 months', 
      	          tickOptions:{showGridline: true, formatString:'%b %#d, %#I %p', locale: 'ru'}
      	      }
      	    },
      	  legend: {
      	      show: false
      	  },
      	  series: [
      	  	{color: '#98ba40'}
      	  ],
      	  highlighter: {
      	      show: true
      	  },
      	  cursor: {
      	      show: true, 
      	      zoom: true
      	  }
      	});      	
      }

      if (stat.total_stocks_visits.length > 0)
      {
      	$('#stocks-pages-chart').css('height', '300px');
      	stocksChart = $.jqplot('stocks-pages-chart', [stat.total_stocks_visits], {
      	   title:'Статистика посещений страниц акций компании',
      	   axes:{
      	      xaxis:{
      	          renderer:$.jqplot.DateAxisRenderer,
      	          //tickInterval: '4 months', 
      	          tickOptions:{showGridline: true, formatString:'%b %#d, %#I %p', locale: 'ru'}
      	      }
      	    },
      	  legend: {
      	      show: false
      	  },
      	  series: [
      	  	{color: '#cd5555'}
      	  ],
      	  highlighter: {
      	      show: true
      	  },
      	  cursor: {
      	      show: true, 
      	      zoom: true
      	  }
      	});      	
      }

      if (stat.total_vacancies_visits.length > 0)
      {
      	$('#vacancies-pages-chart').css('height', '300px');
      	vacanciesChart = $.jqplot('vacancies-pages-chart', [stat.total_vacancies_visits], {
      	   title:'Статистика посещений страниц вакансий компании',
      	   axes:{
      	      xaxis:{
      	          renderer:$.jqplot.DateAxisRenderer,
      	          //tickInterval: '4 months', 
      	          tickOptions:{showGridline: true, formatString:'%b %#d, %#I %p', locale: 'ru'}
      	      }
      	    },
      	  legend: {
      	      show: false
      	  },
      	  series: [
      	  	{color: '#777777'}
    ],
    highlighter: {
        show: true
        },
    cursor: {
        show: true,
        zoom: true
        }
    });
    }

    jQuery.jqplot ('pie-chart', [stat.visits_counts],
		{
		  title: 'Соотношение просмотров страниц',
		  seriesDefaults: {
		    // Make this a pie chart.
		    renderer: jQuery.jqplot.PieRenderer,
		    rendererOptions: {
		      // Put data labels on the pie slices.
		      // By default, labels show the percentage of the slice.
		      showDataLabels: true
		    }
		  },
		  legend: { show:true, location: 'e' },
		  seriesColors: ["#3399ff", "#98ba40", '#cd5555', '#777777']


    }
    );

    });