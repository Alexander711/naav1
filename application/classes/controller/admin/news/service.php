<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_News_Service extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/news/service'] = 'Новости автосервисов';
    }
    function action_index()
    {
        $news = ORM::factory('newsservice');

        $this->view = View::factory('backend/news/service/all')
                          ->set('news', $news);

        $this->template->title = 'Новости автосервисов';
        $this->template->content = $this->view;
    }
    /**
     * Редактирование новости автосервиса
     * @return void
     */
    function action_edit()
    {
        $news = ORM::factory('newsservice', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'news_not_found'));
            $this->request->redirect('admin/news/service');
        }

        if ($_POST)
        {
            $active = Arr::get($_POST, 'active', 0);
            try
            {
                $news->values($_POST, array('title', 'text'));
                $news->active = $active;
                $news->update();
                Message::set(Message::SUCCESS, 'Новость "'.$news->title.'" автосервиса "'.$news->service->name.'" отредактирована');
                $this->request->redirect('admin/news/service');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $news->as_array();
        }
        $this->values['company_name'] = $news->service->name;

        $this->view = View::factory('backend/news/form')
                          ->set('errors', $this->errors)
                          ->set('values', $this->values)
                          ->set('url', 'admin/news/service/edit/'.$news->id);
        $this->template->title = 'Редактирование новости "'.$news->title.'" автосервиса "'.$news->service->name.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;


    }
    /**
     * Удаление новости автосервиса
     * @return void
     */
    function action_delete()
    {
        $news = ORM::factory('newsservice', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'news_not_found'));
            $this->request->redirect('admin/news/service');
        }

        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('admin/news/service');
            }
            if ($action['submit'])
            {
                $title = $news->title;
                $service_name = $news->service->name;
                $news->delete();
                Message::set(Message::SUCCESS, 'Новость автосервиса <strong>'.$service_name.'</strong> "'.$title.'" удалена');
                $this->request->redirect('admin/news/service');
            }
        }
        $this->view = View::factory('backend/delete')
                          ->set('url', 'admin/news/service/delete/'.$news->id)
                          ->set('from_url', 'admin/news/service')
                          ->set('title', 'Удаление новости компании '.$news->service->name)
                          ->set('text', 'Вы действительно хотите удалить новость "'.$news->title.'" сервиса "'.$news->service->name.'"?');
        $this->template->title = 'Удаление новости "'.$news->title.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Деактивация новости автосервиса
     * @return void
     */
    public function action_deactivate()
    {
        $news = ORM::factory('newsservice', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'news_not_found'));
            $this->request->redirect('admin/news/service');
        }
        if ($news->active == 0)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'news_is_unactive'));
            $this->request->redirect('admin/news/service');
        }
        $news->active = 0;
        $news->update();
        //$this->route_update();
        Message::set(Message::SUCCESS, 'Новость "'.$news->title.'" сервиса "'.$news->service->name.'" деактивирована');
        $this->request->redirect('admin/news/service');
    }
    /**
     * Активация новости автосервиса
     * @return void
     */
    public function action_activate()
    {
        $news = ORM::factory('newsservice', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'news_not_found'));
            $this->request->redirect('admin/news/service');
        }
        if ($news->active == 1)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_is_active'));
            $this->request->redirect('admin/news/service');
        }
        $news->active = 1;
        $news->update();
        //$this->route_update();
        Message::set(Message::SUCCESS, 'Новость "'.$news->title.'" сервиса "'.$news->service->name.'" активирована');
        $this->request->redirect('admin/news/service');
    }
}