<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Visit extends ORM
{
    protected $_table_name = 'visits';
    protected $_table_columns = array(
        'id'         => NULL,
        'processed'  => NULL,
        'date'       => NULL,
        'uri'        => NULL,
        'directory'  => NULL,
        'controller' => NULL,
        'action'     => NULL,
        'params'     => NULL,
        'client_ip'  => NULL,
        'referrer'   => NULL
    );
    protected $_has_many = array(
        'services' => array(
            'model'       => 'service',
            'through'     => 'services_visits',
            'far_key'     => 'service_id',
            'foreign_key' => 'visit_id'
        ),
        'stocks' => array(
            'model'       => 'stock',
            'through'     => 'servicestocks_visits',
            'far_key'     => 'stock_id',
            'foreign_key' => 'visit_id'
        ),
        'service_news' => array(
            'model'       => 'newsservice',
            'through'     => 'servicenews_visits',
            'far_key'     => 'news_id',
            'foreign_key' => 'visit_id'
        ),
        'vacancies' => array(
            'model'       => 'vacancy',
            'through'     => 'servicevacancies_visits',
            'far_key'     => 'vacancy_id',
            'foreign_key' => 'visit_id'
        )
    );
    public static  $uri_dates = array();
    public static $services = array();

    public function save_visit($request_data = NULL)
    {
        $request = Request::current();

        $this->date = (isset($request_data['date'])) ? $request_data['date'] : Date::formatted_time();
        $this->uri = (isset($request_data['uri'])) ? $request_data['uri'] : $request->uri() ;
        $this->directory = (isset($request_data['directory'])) ? $request_data['directory'] : $request->directory();
        $this->controller = (isset($request_data['controller'])) ? $request_data['controller'] : $request->controller();
        $this->action = (isset($request_data['action'])) ? $request_data['action'] : $request->action();
        $this->params = (isset($request_data['params'])) ? $request_data['params'] : json_encode($request->get_params());
        $this->client_ip = (isset($request_data['client_ip'])) ? $request_data['client_ip'] : Request::$client_ip;

        if (isset($request_data['referrer']))
            $this->referrer = ($request_data['referrer'] == 'havent_referrer') ? NULL : $request_data['referrer'];
        else
            $this->referrer = (isset($_SERVER['HTTP_REFERER'])) ? $request->referrer() : NULL;



        $this->save();
    }
    public function filters()
    {
        return array(
            'uri' => array(
                array('preg_replace', array('/&[^\s]+/', '', ':value'))
            ),
            'params' => array(
                array(array($this, 'clean_params'))
            ),
        );
    }
    public function clean_params($value)
    {
        $params = (array) json_decode($value);
        $cleaned = array();
        foreach ($params as $key => $value)
        {
            $cleaned[$key] = preg_replace('/&[^\s]+/', '', $value);
        }
        return json_encode($cleaned);
    }
}