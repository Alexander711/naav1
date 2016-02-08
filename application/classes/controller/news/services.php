<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_News_Services extends Controller_Frontend { 

	public $template = 'templates/frontend_news';
	
    function before()
    {
        parent::before();
		
		// add class for news pages
        $this->template->css_class = 'wrapper-news';
    }
	
    public function action_index()
    {
        $service = ORM::factory('service', Arr::get($_GET, 'service', NULL));
        // Если перешли по старому url вида news?service=[id компании]
        if ($service->loaded())
            $this->request->redirect('services/'.$service->id.'/news', 301);


        $news = ORM::factory('newsservice');

        $this->template->title = 'Новости автосервисов';
        $this->template->bc['#'] = $this->template->title;

        $this->view = View::factory('frontend/news/services/all')
                          ->set('news', $news->get_news())
                           ->set('show_company_name', TRUE)
                          ->set('h1_title', $this->template->title);
        $this->template->content = $this->view;
    }
	
    public function action_view()
    {
        $news = ORM::factory('newsservice', $this->request->param('id', NULL));
        if (!$news->loaded())
            throw new HTTP_Exception_404('Новость от компании не найдена');
        else
            $this->request->redirect('services/'.$news->service->id.'/news/'.$news->id, 301);
    }
}