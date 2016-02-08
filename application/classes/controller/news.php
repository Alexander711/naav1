<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_News extends Controller_Frontend
{
    function action_index()
    {
        $service_id = Arr::get($_GET, 'service', NULL);
        $service = ORM::factory('service', $service_id);
        $this->template->bc['news'] = 'Новости автосервисов';
        if ($service->loaded())
        {
            $h1_title = 'Новости '.$service->orgtype->name.' &laquo;'.$service->name.'&raquo;';
            $this->template->bc['#'] = $h1_title;
            $news = $service->news;
        }
        else
        {
            $h1_title = 'Новости автосервисов';
            $news = ORM::factory('newsservice');
        }
        $news->reset(FALSE);
        $this->view = View::factory('frontend/news/all_service')
                          ->set('news', $news)
                          ->set('h1_title', $h1_title);
        $this->template->title = $this->site_name.$h1_title;

        $this->template->content = $this->view;
    }
	
    function action_service_news_view()
    {
        $news = ORM::factory('newsservice', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            $this->request->redirect('news');
        }
        $this->view = View::factory('frontend/news/view_service')
                          ->set('news', $news);
        $this->template->title = $this->site_name.$news->title;
        $this->template->bc['news'] = 'Новости автосервисов';
        $this->template->bc['#'] = $news->title;
        $this->template->content = $this->view;
    }
    function action_association()
    {
        $news = ORM::factory('newsportal');
        $this->view = View::factory('frontend/news/all')
                          ->set('news', $news)
                          ->set('url', 'news/association/');
        $this->template->title = $this->site_name.'Новости ассоциации';
        $this->template->content = $this->view;
        $this->template->bc['#'] = 'Новости ассоциации';
    }
    function action_portal_news_view()
    {
        $news = ORM::factory('newsportal', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            $this->request->redirect('news/association');
        }
        $this->view = View::factory('frontend/news/view')
                          ->set('news', $news);
        $this->template->title = $this->site_name.$news->title;
        $this->template->bc['news/association'] = 'Новости ассоциации';
        $this->template->bc['#'] = $news->title;
        $this->template->content = $this->view;
    }

    function action_world()
    {
        $news = ORM::factory('newsworld');
        $this->view = View::factory('frontend/news/all_world')
                          ->set('news', $news)
                          ->set('url', 'news/world/');
        $this->template->title = $this->site_name.'Новости автомира';
        $this->template->bc['#'] = 'Новости автомира';
        $this->template->content = $this->view;
    }
    /**
     * Просмотр одной новости автомира
     * @return void
     */
    function action_world_news_view()
    {
        $news = ORM::factory('newsworld', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            $this->request->redirect('news/association');
        }

        if ($news->id == 4)
            $this->request->redirect('articles/3', 301);
        if ($news->id == 2)
            $this->request->redirect('articles/1', 301);
        if ($news->id == 3)
            $this->request->redirect('articles/2', 301);

        $this->view = View::factory('frontend/news/view')
                          ->set('news', $news);
        $this->template->title = $this->site_name.$news->title;
        $this->template->bc['news/world'] = 'Новости автомира';
        $this->template->bc['#'] = $news->title;
        $this->template->content = $this->view;
    }
}