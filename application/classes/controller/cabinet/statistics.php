<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Statistics extends Controller_Cabinet
{
	public function action_index()
	{
		if ( !$this->user->has('roles', 2) AND count($this->user->services->find_all()) < 1)
		{
			Message::set(Message::ERROR, 'У вас нет компаний для просмотра статистики');
			$this->request->redirect('cabinet');
		}
		$service = ORM::factory('service', $this->request->param('id'));
		
		if (!$service->loaded())
		{
			if (!$this->user->has('roles', 2) AND $service->user_id != $this->user->id)
				Message::set(Message::ERROR, 'Вы выбрали чужую компанию, показана статистики первой вашей компании');
			$service = $this->user->services->find();
		}
			


		$company_visits = array();
		$news_visits = array();
		$vacancies_visits = array();
		$stocks_visits = array();

		$total_news_visits = array();
		$total_stocks_visits = array();
		$total_vacancies_visits = array();

		$count_company_visits = 0;
		$count_news_visits = 0;
		$count_stocks_visits = 0;
		$count_vacancies_visits = 0;

		$company_visits_weeks = array();
		$company_visits_months = array();

		foreach ($service->visits->find_all() as $visit) 
		{
			$date = Date::formatted_time($visit->date, 'Y-m-d');
			if (array_key_exists($date, $company_visits) === FALSE)
				$company_visits[$date] = array($date, 1);
			else
				$company_visits[$date][1] += 1;

			$count_company_visits += 1;
		}

		
		$company_visits_weeks[] = 0;
		$company_visits_months[Date::formatted_time(key($company_visits), 'n')] = 0;

		foreach ($company_visits as $date => $value) 
		{
			if (Date::formatted_time($date, 'w') == 0)
			{
				$company_visits_weeks[] = $value[1];
			}
			else
			{
				end($company_visits_weeks);
				$key = key($company_visits_weeks);
				$company_visits_weeks[$key] += $value[1];
			}
			end($company_visits_months);
			$month = key($company_visits_months);
			if (Date::formatted_time($date, 'n') == $month)
			{
				$company_visits_months[$month] += $value[1];
			}
			else
			{
				$company_visits_months[Date::formatted_time($date, 'n')] = $value[1];
			}
		}
		$company_visits = array_values($company_visits);

		$total_visits = count($service->visits->find_all());

		$company_average_on_day = (empty($total_visits)) ? 0 : ceil($total_visits / count($company_visits));
		$company_average_on_week = (empty($total_visits)) ? 0 : ceil($total_visits / count($company_visits_weeks));
		$company_average_on_month = (empty($total_visits)) ? 0 : ceil($total_visits / count($company_visits_months));

	

		// Если кол-во визитов меньше 2 и больше 0, добавляем еще один пункт диаграммы, во избежание ошибки отрисовки диаграммы
		if (count($company_visits) > 0 AND count($company_visits) < 2)
			$company_visits[] = array(Date::formatted_time('now', 'Y-m-d'), 0);  


		foreach ($service->news->find_all() as $news) 
		{
			$news_visits[$news->id] = array();

			foreach ($news->visits->find_all() as $visit) 
			{
				$date = Date::formatted_time($visit->date, 'Y-m-d');
				if (array_key_exists($date, $news_visits[$news->id]) === FALSE)
					$news_visits[$news->id][$date] = array($date, 1);
				else
					$news_visits[$news->id][$date][1] += 1;	
			}

			$news_visits[$news->id] = array_values($news_visits[$news->id]);
			
			// Если кол-во визитов меньше 2 и больше 0, добавляем еще один пункт диаграммы, во избежание ошибки отрисовки диаграммы
			$count = count($news_visits[$news->id]);
			if ($count > 0 AND $count < 2)
				$news_visits[$news->id][] = array(Date::formatted_time('now', 'Y-m-d'), 0);  
		}

		foreach ($news_visits as $key => $value) 
		{
			foreach ($value as $visit) 
			{
				$count_news_visits += $visit[1]; 
				if (!array_key_exists($visit[0], $total_news_visits))
					$total_news_visits[$visit[0]] = $visit;
				else
					$total_news_visits[$visit[0]][1] += $visit[1];
			}
		}

		$news_visits_weeks = array();
		$news_visits_months = array();
		$news_visits_weeks[] = 0;
		$news_visits_months[Date::formatted_time(key($total_news_visits), 'n')] = 0;

		foreach ($total_news_visits as $date => $value) 
		{
			if (Date::formatted_time($date, 'w') == 0)
			{
				$news_visits_weeks[] = $value[1];
			}
			else
			{
				end($news_visits_weeks);
				$key = key($news_visits_weeks);
				$news_visits_weeks[$key] += $value[1];
			}
			end($news_visits_months);
			$month = key($news_visits_months);
			if (Date::formatted_time($date, 'n') == $month)
			{
				$news_visits_months[$month] += $value[1];
			}
			else
			{
				$news_visits_months[Date::formatted_time($date, 'n')] = $value[1];
			}
		}
		$total_news_visits = array_values($total_news_visits);

		$news_average_on_day = (empty($total_news_visits)) ? 0 : ceil($count_news_visits / count($total_news_visits));
		$news_average_on_week = (empty($total_news_visits)) ? 0 : ceil($count_news_visits / count($news_visits_weeks));
		$news_average_on_month = (empty($total_news_visits)) ? 0 : ceil($count_news_visits / count($news_visits_months));

		foreach ($service->vacancies->find_all() as $vacancy) 
		{
			$vacancies_visits[$vacancy->id] = array();

			foreach ($vacancy->visits->find_all() as $visit) 
			{
				$date = Date::formatted_time($visit->date, 'Y-m-d');
				if (array_key_exists($date, $vacancies_visits[$vacancy->id]) === FALSE)
					$vacancies_visits[$vacancy->id][$date] = array($date, 1);
				else
					$vacancies_visits[$vacancy->id][$date][1] += 1;						
			}		

			$vacancies_visits[$vacancy->id] = array_values($vacancies_visits[$vacancy->id]);

			// Если кол-во визитов меньше 2 и больше 0, добавляем еще один пункт диаграммы, во избежание ошибки отрисовки диаграммы
			$count = count($vacancies_visits[$vacancy->id]);
			if ($count > 0 AND $count < 2)
				$vacancies_visits[$vacancy->id][] = array(Date::formatted_time('now', 'Y-m-d'), 0);  
		}

		foreach ($vacancies_visits as $key => $value) 
		{
			foreach ($value as $visit) 
			{
				$count_vacancies_visits += $visit[1];
				if (!array_key_exists($visit[0], $total_vacancies_visits))
					$total_vacancies_visits[$visit[0]] = $visit;
				else
					$total_vacancies_visits[$visit[0]][1] += $visit[1];
			}
		}

		$vacancies_visits_weeks = array();
		$vacancies_visits_months = array();
		$vacancies_visits_weeks[] = 0;
		$vacancies_visits_months[Date::formatted_time(key($total_vacancies_visits), 'n')] = 0;

		foreach ($total_vacancies_visits as $date => $value) 
		{
			if (Date::formatted_time($date, 'w') == 0)
			{
				$vacancies_visits_weeks[] = $value[1];
			}
			else
			{
				end($vacancies_visits_weeks);
				$key = key($vacancies_visits_weeks);
				$vacancies_visits_weeks[$key] += $value[1];
			}
			end($vacancies_visits_months);
			$month = key($vacancies_visits_months);
			if (Date::formatted_time($date, 'n') == $month)
			{
				$vacancies_visits_months[$month] += $value[1];
			}
			else
			{
				$vacancies_visits_months[Date::formatted_time($date, 'n')] = $value[1];
			}
		}

		$total_vacancies_visits = array_values($total_vacancies_visits);

		$vacancies_average_on_day = (empty($total_vacancies_visits)) ? 0 : ceil($count_vacancies_visits / count($total_vacancies_visits));
		$vacancies_average_on_week = (empty($total_vacancies_visits)) ? 0 : ceil($count_vacancies_visits / count($vacancies_visits_weeks));
		$vacancies_average_on_month = (empty($total_vacancies_visits)) ? 0 : ceil($count_vacancies_visits / count($vacancies_visits_months));

		foreach ($service->stocks->find_all() as $stock) 
		{
			$stocks_visits[$stock->id] = array();

			foreach ($stock->visits->find_all() as $visit) 
			{
				$date = Date::formatted_time($visit->date, 'Y-m-d');
				if (array_key_exists($date, $stocks_visits[$stock->id]) === FALSE)
					$stocks_visits[$stock->id][$date] = array($date, 1);
				else
					$stocks_visits[$stock->id][$date][1] += 1;							
			}		

			$stocks_visits[$stock->id] = array_values($stocks_visits[$stock->id]);		

			// Если кол-во визитов меньше 2 и больше 0, добавляем еще один пункт диаграммы, во избежание ошибки отрисовки диаграммы
			$count = count($stocks_visits[$stock->id]);
			if ($count > 0 AND $count < 2)
				$stocks_visits[$stock->id][] = array(Date::formatted_time('now', 'Y-m-d'), 0);  
		}	

		foreach ($stocks_visits as $key => $value) 
		{
			foreach ($value as $visit) 
			{
				$count_stocks_visits += $visit[1];
				if (!array_key_exists($visit[0], $total_stocks_visits))
					$total_stocks_visits[$visit[0]] = $visit;
				else
					$total_stocks_visits[$visit[0]][1] += $visit[1];
			}
		}

		$stocks_visits_weeks = array();
		$stocks_visits_months = array();
		$stocks_visits_weeks[] = 0;
		$stocks_visits_months[Date::formatted_time(key($total_stocks_visits), 'n')] = 0;

		foreach ($total_stocks_visits as $date => $value) 
		{
			if (Date::formatted_time($date, 'w') == 0)
			{
				$stocks_visits_weeks[] = $value[1];
			}
			else
			{
				end($stocks_visits_weeks);
				$key = key($stocks_visits_weeks);
				$stocks_visits_weeks[$key] += $value[1];
			}
			end($stocks_visits_months);
			$month = key($stocks_visits_months);
			if (Date::formatted_time($date, 'n') == $month)
			{
				$stocks_visits_months[$month] += $value[1];
			}
			else
			{
				$stocks_visits_months[Date::formatted_time($date, 'n')] = $value[1];
			}
		}
		$total_stocks_visits = array_values($total_stocks_visits);

		$stocks_average_on_day = (empty($total_stocks_visits)) ? 0 : ceil($count_stocks_visits / count($total_stocks_visits));
		$stocks_average_on_week = (empty($total_stocks_visits)) ? 0 : ceil($count_stocks_visits / count($stocks_visits_weeks));
		$stocks_average_on_month = (empty($total_stocks_visits)) ? 0 : ceil($count_stocks_visits / count($stocks_visits_months));

		$data = (object) array(
			'company' => $company_visits,
			'news_visits' => $news_visits,
			'stocks_visits' => $stocks_visits,
			'vacancies_visits' => $vacancies_visits,
			'total_news_visits' => $total_news_visits,
			'total_stocks_visits' => $total_stocks_visits,
			'total_vacancies_visits' => $total_vacancies_visits,
			'visits_counts' => array(
				array('Просмотры страницы компании', $count_company_visits),
				array('Просмотров страниц новостей', $count_news_visits),
				array('Просмотров страниц акций', $count_stocks_visits),
				array('Просмотров страниц вакансий', $count_vacancies_visits)
			)
		);



		// JS
        $this->add_css('assets/js/jqplot/jquery.jqplot.min.css');
        $this->add_js('assets/js/jqplot/jquery.jqplot.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.pieRenderer.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.donutRenderer.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.highlighter.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.cursor.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.dateAxisRenderer.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.pointLabels.min.js');
        $this->add_js('assets/js/statistics.js');
		$this->view = View::factory('frontend/cabinet/statistics/main')
					      ->set('data', $data)
					      ->set('user', $this->user)
				      	  ->set('service', $service)
				      	  ->set('company_average_on_day', $company_average_on_day)
			      	  	  ->set('company_average_on_week', $company_average_on_week)
			      	  	  ->set('company_average_on_month', $company_average_on_month)
			      	  	  ->set('news_average_on_day', $news_average_on_day)
			      	  	  ->set('news_average_on_week', $news_average_on_week)
			      	  	  ->set('news_average_on_month', $news_average_on_month)
			      	  	  ->set('vacancies_average_on_day', $vacancies_average_on_day)
			      	  	  ->set('vacancies_average_on_week', $vacancies_average_on_week)
			      	  	  ->set('vacancies_average_on_month', $vacancies_average_on_month)
			      	  	  ->set('stocks_average_on_day', $stocks_average_on_day)
			      	  	  ->set('stocks_average_on_week', $stocks_average_on_week)
			      	  	  ->set('stocks_average_on_month', $stocks_average_on_month);
      	$this->template->content = $this->view;
      	$this->template->title = 'Статистика компании '.$service->name;
      	$this->template->bc['#'] = $this->template->title;
	}
}
