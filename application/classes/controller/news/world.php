<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_News_World extends Controller_Frontend {

	public $template = 'templates/frontend_news';
	
    public function before()
    {
        parent::before();
		
		// add class for news pages
        $this->template->css_class = 'wrapper-news';
    }
	
    /**
     * Обзор новостей автомира
     * @return void
     */
    public function action_index()
    {
        $news = ORM::factory('newsworld')->where('active', '=', 1)->order_by('date', 'DESC');

        $this->view = View::factory('frontend/news/world/all')
                          ->set('news', $news->find_all());
        $this->template->title = 'Новости автомира';
        $this->template->bc['#'] = 'Новости автомира';
        $this->template->content = $this->view;
    }
    /**
     * Просмотр новости автомира
     * @throws HTTP_Exception_404
     */
    public function action_view()
    {
        $news = ORM::factory('newsworld', $this->request->param('id', NULL));

        if ($news->id == 4)
            $this->request->redirect('articles/3', 301);
        elseif ($news->id == 2)
            $this->request->redirect('articles/1', 301);
        elseif ($news->id == 3)
            $this->request->redirect('articles/2', 301);
        elseif (!$news->loaded() OR $news->active < 1)
            throw new HTTP_Exception_404('Такая новость не найдена');

        $this->view = View::factory('frontend/news/view')
                          ->set('news', $news);
        $this->template->title = $news->title;
        $this->template->bc['news/world'] = 'Новости автомира';
        $this->template->bc['#'] = $news->title;
        $this->template->content = $this->view;
    }
}