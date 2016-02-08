<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Main extends Controller_Cabinet
{
    function action_index()
    {
        // Model initial

        $service = $this->user->services;
        $news = $this->user->news_service;
        $stock = $this->user->stocks;
        $vacancy = $this->user->vacancies;
        $question = ORM::factory('question');
        $statistics = array(
          'labels' => array(),
          'charts' => array()
        );
        $reviews = array();
        foreach ($service->find_all() as $s)
        {


          $chart = array();


          foreach ($s->visits->find_all() as $visit) 
          {
            $date = Date::formatted_time($visit->date, 'Y-m-d');
            if (array_key_exists($date, $chart) === false)
              $chart[$date] = array($date, 1);
            else
              $chart[$date][1] += 1;
          }
          if (count($chart) > 0 AND count($chart) < 2)
            $chart[] = array(Date::formatted_time('now', 'Y-m-d'), 0);  
          
          $chart = array_values($chart);
          $statistics['labels'][] = array('label' => $s->name);
          $statistics['charts'][] = $chart;
          foreach ($s->reviews->order_by('date', 'DESC')->find_all() as $r)
          {
              $reviews[] = array(
                  'service_name' => $s->name,
                  'date' => $r->date,
                  'text' => $r->text
              );
          }
        }
        $this->view = View::factory('frontend/cabinet/dashboard')
                          ->set('statistics', json_encode($statistics))
                          ->set('service', $this->user->services)
                          ->set('news', $news)
                          ->set('stock', $stock)
                          ->set('vacancy', $vacancy)
                          ->set('question', $question)
                          ->set('reviews', $reviews)
                          ->set('notices_unread_count', 0)
                          ->set('notices_all_count', 0);
        $this->template->title = $this->site_name.'Личный кабинет';
        $this->template->content = $this->view;


        $this->add_css('assets/js/jqplot/jquery.jqplot.min.css');
        $this->add_js('assets/js/jqplot/jquery.jqplot.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.highlighter.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.cursor.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.dateAxisRenderer.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.pointLabels.min.js');
        $this->add_js('assets/js/statistics_main.js');


    }
}