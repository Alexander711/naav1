<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_News_World extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/news/world'] = 'Новости автомира';
    }
    public function action_index()
    {
        $news = ORM::factory('newsworld');

        $this->view = View::factory('backend/news/world/all')
                          ->set('news', $news);
        $this->template->title = 'Новости автомира';
        $this->template->content = $this->view;
    }
    /**
     * Добавление новости автомира
     * @return void
     */
    public function action_add()
    {
        $this->values['active'] = 1;
        if ($_POST)
        {
            $active = Arr::get($_POST, 'active', 0);
            $news = ORM::factory('newsworld');
            try
            {
                $news->values($_POST, array('title', 'text', 'date'));
                $news->active = $active;
                $news->date = Date::formatted_time();
                $news->save();
                Message::set(Message::SUCCESS, 'Новость автомира "'.$news->title.'" добавлена');
                $this->request->redirect('admin/news/world');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->values = $_POST;
                $this->errors = $e->errors('models');
            }
        }
        $this->view = View::factory('backend/news/form')
                          ->set('errors', $this->errors)
                          ->set('values', $this->values)
                          ->set('url', 'admin/news/world/add');
        $this->template->title = 'Добавление новости ассоциации';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Редактирование новости автомира
     * @return void
     */
    public function action_edit()
    {
        $news = ORM::factory('newsworld', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'news_not_found'));
            $this->request->redirect('admin/news/world');
        }

        if ($_POST)
        {
            $active = Arr::get($_POST, 'active', 0);
            try
            {
                $news->values($_POST, array('title', 'text'));
                $news->active = $active;
                $news->date = Date::formatted_time();
                $news->update();
                Message::set(Message::SUCCESS, 'Новость автомира "'.$news->title.'" отредактирована');
                $this->request->redirect('admin/news/world');
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

        $this->view = View::factory('backend/news/form')
                          ->set('errors', $this->errors)
                          ->set('values', $this->values)
                          ->set('url', 'admin/news/world/edit/'.$news->id);
        $this->template->title = 'Редактирование новости автомира "'.$news->title.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Деактивация новости автомира
     * @return void
     */
    public function action_deactivate()
    {
        $news = ORM::factory('newsworld', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'news_not_found'));
            $this->request->redirect('admin/news/world');
        }
        if ($news->active == 0)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'news_is_unactive'));
            $this->request->redirect('admin/news/world');
        }
        $news->active = 0;
        $news->update();
        //$this->route_update();
        Message::set(Message::SUCCESS, 'Новость автомира "'.$news->title.'" деактивирована');
        $this->request->redirect('admin/news/world');
    }
    /**
     * Активация новости автомира
     * @return void
     */
    public function action_activate()
    {
        $news = ORM::factory('newsworld', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'news_not_found'));
            $this->request->redirect('admin/news/world');
        }
        if ($news->active == 1)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_is_active'));
            $this->request->redirect('admin/news/worldl');
        }
        $news->active = 1;
        $news->update();
        //$this->route_update();
        Message::set(Message::SUCCESS, 'Новость автомира "'.$news->title.'" активирована');
        $this->request->redirect('admin/news/world');
    }
    public function action_delete()
    {
        $news = ORM::factory('newsworld', $this->request->param('id', NULL));
        if (!$news->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'news_not_found'));
            $this->request->redirect('admin/news/world');
        }

        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('admin/news/world');
            }
            if ($action['submit'])
            {
                $title = $news->title;
                $news->delete();
                Message::set(Message::SUCCESS, 'Новость автомира "'.$title.'" удалена');
                $this->request->redirect('admin/news/world');
            }
        }

        $this->view = View::factory('backend/delete')
                          ->set('url', 'admin/news/world/delete/'.$news->id)
                          ->set('from_url', 'admin/news/world')
                          ->set('title', 'Удаление новости автомира')
                          ->set('text', 'Вы действительно хотите удалить новость автомира "'.$news->title.'"?');
        $this->template->title = 'Удаление новости "'.$news->title.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}