<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Services_News extends Controller_Frontend
{
    public function after()
    {
        parent::after();
        if ($this->request->action() == 'view')
            ORM::factory('visit')->save_visit();
    }
    /**
     * Обзор новостей компании
     * @throws HTTP_Exception_404
     */
    public function action_index()
    {
        $service = ORM::factory('service', $this->request->param('service_id'));
        if (!$service->loaded())
            throw new HTTP_Exception_404('Такая компания не найдена');

        $this->template->title = 'Новости '.__('company_type_'.$service->type.'_genitive').' '.$service->name;
        $this->template->bc['services/'.$service->id] = $service->get_name(2);
        $this->template->bc['#'] = 'Новости';

        $this->view = View::factory('frontend/news/services/all')
                                  ->set('news', $service->news->get_news())
                                  ->set('show_company_name', FALSE)
                                  ->set('h1_title', $this->template->title);
        $this->template->content = $this->view;
    }
    /**
     * Просмотр новости компании
     * @throws HTTP_Exception_404
     */
    public function action_view()
    {
        $service = ORM::factory('service', $this->request->param('service_id'));
        if (!$service->loaded())
            throw new HTTP_Exception_404('Такая компания не найдена');
        $news = $service->news->where('id', '=', $this->request->param('news_id'))->find();
        if (!$news->loaded())
            throw new HTTP_Exception_404('Новость для компании '.$service->name.' не найдена');

        $this->template->title = $news->title;;
        $this->view = View::factory('frontend/news/services/view')
                          ->set('news', $news);
        $this->template->bc['services/'.$service->id] = $service->get_name(2);
        $this->template->bc['services/'.$service->id.'/news'] = 'Новости';
        $this->template->bc['#'] = 'Новость от '.MyDate::show($news->date_create);
        //$this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}