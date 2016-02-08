<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Stocks extends Controller_Frontend { 

	public $template = 'templates/frontend_news';
	
    function before()
    {
        parent::before();
		
		// breadcrumbs title
        $this->template->bc['stocks'] = 'Акции автосервисов';
		
		// add class for news pages
        $this->template->css_class = 'wrapper-news';
    }
	
    function action_index()
    {
        $service = ORM::factory('service', Arr::get($_GET, 'service', NULL));
        if ($service->loaded())
            $this->request->redirect('services/'.$service->id.'/stocks', 301);

        $this->template->title = 'Акции автосервисов';
        // company_page = FALSE - указывает что это не страница акций компании
        $this->view = View::factory('frontend/stock/all')
                          ->set('company_page', FALSE)
                          ->set('stock', ORM::factory('stock')->get_stocks())
                          ->set('h1_title', $this->template->title);
        $this->template->content = $this->view;

    }
	
    function action_view()
    {
        $stock = ORM::factory('stock', $this->request->param('id', NULL));
        if (!$stock->loaded())
            throw new HTTP_Exception_404('Акция не найдена');
        else
            $this->request->redirect('services/'.$stock->service->id.'/stocks/'.$stock->id, 301);

        $this->view = View::factory('frontend/stock/view')
                          ->set('stock', $stock);
        $this->template->bc['#'] = 'Акция сервиса '.$stock->service->name;
        $this->template->title = $this->site_name.'Акция сервиса '.$stock->service->name;
        $this->template->content = $this->view;
    }
}