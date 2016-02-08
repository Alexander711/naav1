<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Rest_Statistics extends Controller_Rest
{
    public function action_index()
    {

    }
    public function action_company()
    {
        $visitor_data = (Arr::get($_POST, 'data')) ? unserialize(Encrypt::instance('statistics')->decode($_POST['data'])) : NULL;

        $company = ORM::factory('service', $this->request->param('id'));
        if (!$company->loaded() OR !$visitor_data)
            return FALSE;

        $request = Request::factory(Route::get('company_info')->uri(array('id' => $company->id, 'company_type' => Model_Service::$type_urls[$company->type])));

        // Если URI не совпадает или истекло время
        if ($request->uri() != $visitor_data['uri'] OR (strtotime(Date::formatted_time()) - $visitor_data['time_created']) > 60)
            return FALSE;

        $visit_data = array(
            'date' => Date::formatted_time(),
            'uri' => $request->uri(),
            'directory' => $request->directory(),
            'controller' => $request->controller(),
            'action' => $request->action(),
            'params' => json_encode($request->get_params()),
            'client_ip' => $visitor_data['client_ip'],
            'referrer' => $visitor_data['referrer']
        );

        ORM::factory('visit')->save_visit($visit_data);

    }
    public function action_company_news()
    {

    }
    public function action_company_stocks()
    {

    }
    public function action_company_vacancies()
    {

    }

}