<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_News_Association extends Controller_Frontend {

	public $template = 'templates/frontend_news';
	
	public function before()
	{
		parent::before();
		
		// add class for news pages
        $this->template->css_class = 'wrapper-news';
	}
    /**
     * Обзор новостей ассоциации
     * @return void
     */
    public function action_index()
    {
        $news = ORM::factory('newsportal')->where('active', '=', 1)->order_by('date', 'DESC');

        $this->view = View::factory('frontend/news/association/all')
                          ->set('news', $news->find_all());
        $this->template->title = 'Новости ассоциации';
        $this->template->bc['#'] = 'Новости ассоциации';
        $this->template->content = $this->view;

    }
    /**
     * Просмотр новости ассоциации
     * @throws HTTP_Exception_404
     */
    public function action_view()
    {
        $news = ORM::factory('newsportal', $this->request->param('id', NULL));
        if (!$news->loaded() OR $news->active < 1)
            throw new HTTP_Exception_404('Такая новость не найдена');

        $this->view = View::factory('frontend/news/view')
                          ->set('news', $news);
        $this->template->title = $news->title;
        $this->template->bc['news/association'] = 'Новости ассоциации';
        $this->template->bc['#'] = $news->title;
        $this->template->content = $this->view;
    }
}