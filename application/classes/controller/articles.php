<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Articles extends Controller_Frontend {

	public $template = 'templates/frontend_news';
	
    public function before()
    {
        parent::before();
        $this->template->title = 'Статьи';
        $this->template->bc['articles'] = $this->template->title;
		
		// add class for news pages
        $this->template->css_class = 'wrapper-news';
    }
	
    public function action_index()
    {
        $article = ORM::factory('content_article')->where('active', '=', 1)->order_by('date_create', 'DESC');
        $this->view = View::factory('frontend/article/all')
                          ->set('article', $article);
        $this->template->content = $this->view;
    }
	
    public function action_view()
    {
        $article = ORM::factory('content_article', $this->request->param('id', NULL));
        if (!$article->loaded())
        {
            Message::set(Message::ERROR, 'Статья не найдена');
            $this->request->redirect('articles');
        }
        $this->template->title = $article->title;
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('frontend/article/view')
                          ->set('article', $article);
        $this->template->content = $this->view;
    }
}