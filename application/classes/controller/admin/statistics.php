<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Statistics extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->add_css('assets/js/jqplot/jquery.jqplot.min.css');
        $this->add_js('assets/js/jqplot/jquery.jqplot.min.js');
    }
    public function action_index()
    {
        $company_pages_hits = array();
        $company = ORM::factory('service');
        foreach ($company->find_all() as $c)
        {
            foreach ($c->visits->find_all() as $visit)
            {
                if (!array_key_exists(Date::formatted_time($visit->date, 'Y-m-d H'), $company_pages_hits))
                {
                    $company_pages_hits[Date::formatted_time($visit->date, 'Y-m-d H')] = 1;
                }
                else
                {
                    $company_pages_hits[Date::formatted_time($visit->date, 'Y-m-d H')] += 1;
                }
            }
        }
        ksort($company_pages_hits);

        $company_pages_hits_line = array();

        foreach ($company_pages_hits as $date => $hints)
            $company_pages_hits_line[] = array($date.':00 PM', $hints);


        $this->add_js('assets/js/jqplot/plugins/jqplot.highlighter.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.cursor.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.dateAxisRenderer.min.js');
        $this->add_js('assets/js/jqplot/plugins/jqplot.pointLabels.min.js');

        $this->template->content = View::factory('backend/statistics/total_chart')
                                       ->set('company_line', json_encode($company_pages_hits_line));
        FirePHP::getInstance(TRUE)->log($company_pages_hits);
    }
}