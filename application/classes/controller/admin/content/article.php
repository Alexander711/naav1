<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Content_Article extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->title = 'Статьи';
        $this->_base_url = 'admin/content/article';
        $this->template->bc[$this->_base_url] = $this->template->title;
    }
    public function action_index()
    {
        $article = ORM::factory('content_article');
        $this->view = View::factory('backend/content/article/all')
                          ->set('article', $article);
        $this->template->content = $this->view;
    }
    public function action_add()
    {
        if ($_POST)
        {
            $article = ORM::factory('content_article')->values($_POST, array('title', 'text', 'meta_d', 'meta_k'));
            $article->date_create = Date::formatted_time();
            try
            {
                $article->save();
                Message::set(Message::SUCCESS, __(Kohana::message('admin', 'content_article.add_success'), array(':name' => $article->title)));
                $this->request->redirect($this->_base_url);
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }

        }
        $this->template->title = 'Добавление статьи';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/content/article/form')
                          ->set('title', $this->template->title)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->content = $this->view;
    }
    public function action_edit()
    {
        $article = $this->get_orm_model('content_article', $this->_base_url);
        $title = $article->title;
        $this->values = $article->as_array();
        if ($_POST)
        {
            $article->values($_POST, array('title', 'text', 'meta_d', 'meta_k'));
            $article->date_edited = Date::formatted_time();
            try
            {
                $article->update();
                Message::set(Message::SUCCESS, __(Kohana::message('admin', 'content_article.edit_success'), array(':name' => $title)));
                $this->request->redirect($this->_base_url);
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Редактирование статьи "'.$title.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/content/article/form')
                          ->set('title', $this->template->title)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->content = $this->view;
    }

    public function action_delete()
    {
        $article = $this->get_orm_model('content_article', $this->_base_url);
        if ($_POST)
        {
            if (Arr::get($_POST, 'submit'))
            {
                Message::set(Message::SUCCESS, __(Kohana::message('admin', 'content_article.delete_success'), array(':name' => $article->title)));
                $article->delete();
            }
            $this->request->redirect($this->_base_url);
        }
        $this->template->title = 'Удаление статьи "'.$article->title.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/delete')
                          ->set('from_url', $this->_base_url)
                          ->set('url', $this->request->url())
                          ->set('title', $this->template->title)
                          ->set('text', __(Kohana::message('admin', 'content_article.delete_question'), array(':name' => $article->title)));
        $this->template->content = $this->view;
    }
    public function action_activate()
    {
        $article = $this->get_orm_model('content_article', $this->_base_url);
        if ($article->active == 1)
        {
            Message::set(Message::SUCCESS, Kohana::message('admin', 'content_article.is_active'));
            $this->request->redirect($this->_base_url);
        }
        $article->active = 1;
        $article->update();
        Message::set(Message::SUCCESS, Kohana::message('admin', 'content_article.activate_success'));
        $this->request->redirect($this->_base_url);
    }

    public function action_deactivate()
    {
        $article = $this->get_orm_model('content_article', $this->_base_url);
        if ($article->active == 0)
        {
            Message::set(Message::SUCCESS, Kohana::message('admin', 'content_article.is_unactive'));
            $this->request->redirect($this->_base_url);
        }
        $article->active = 0;
        $article->update();
        Message::set(Message::SUCCESS, Kohana::message('admin', 'content_article.deactivate_success'));
        $this->request->redirect($this->_base_url);
    }
}