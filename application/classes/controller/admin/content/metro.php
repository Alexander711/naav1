<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Content_Metro extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/content/metro'] = 'Страницы поиска по станции метро';
    }
    /**
     * Обзор страниц поика метро
     * @return void
     */
    public function action_index()
    {
        $city_id = $this->request->param('id', 1);
        $cities = ORM::factory('city')
                     ->distinct('city.id')
                     ->join(array('metro', 'metro'))
                     ->on('metro.city_id', '=', 'city.id');

        $content = ORM::factory('content_metro')
                      ->join(array('metro', 'metro'))
                      ->on('metro.id', '=',  'content_metro.metro_id')
                      ->where('metro.city_id', '=', $city_id)
                      ->order_by('metro.name', 'ASC');
        $this->view = View::factory('backend/content/metro/all')
                          ->set('content', $content)
                          ->set('cities', $cities)
                          ->set('city_id', $city_id);
        $this->template->title =$this->template->bc['admin/content/metro'];
        $this->template->content = $this->view;
    }
    /**
     * Редактирование страницы поиска по станции метро
     * @return void
     */
    public function action_edit()
    {
        $content = ORM::factory('content_metro', $this->request->param('id', NULL));
        if (!$content->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_not_found'));
            $this->request->redirect('admin/content/metro');
        }
        $this->values = $content->as_array();
        if ($_POST)
        {
            $content->values($_POST, array('text'));
            $content->date_edited = Date::formatted_time();
            $content->update();
            Message::set(Message::SUCCESS, 'Страница для поиска по станции метро "'.$content->metro->name.'" отредактирована');
            $this->request->redirect('admin/content/metro');
        }
        $this->view = View::factory('backend/content/cars_works_edit')
                          ->set('content', $content)
                          ->set('url', 'admin/content/metro/edit/'.$content->id)
                          ->set('values', $this->values);
        $this->template->bc['#'] = 'Редактирование страницы поиска по станции метро '.$content->metro->name;
        $this->template->content = $this->view;

    }
    /**
     * Указания текста по умолчанию
     * @return void
     */
    public function action_default_text()
    {
        $this->values['text'] = Kohana::$config->load('default_search_content.search_by_metro');
        if ($_POST)
        {
            $this->validation = Validation::factory($_POST)
                                          ->rule('text', 'not_empty');
            if ($this->validation->check())
            {
                Kohana::$config->load('default_search_content')->set('search_by_metro', $this->validation['text']);
                Message::set(Message::SUCCESS, 'Текст по умолчанию для поиска по станции метро изменен');
                $this->request->redirect('admin/content/metro');
            }
            else
            {
                $this->values = $_POST;
                $this->errors['text'] = 'Введите текст';
            }
        }
        $this->template->title = 'Текст по умолчанию для поиска по станции метро';
        $this->view = View::factory('backend/content/form_default_text')
                          ->set('errors', $this->errors)
                          ->set('values', $this->values)
                          ->set('title', $this->template->title);
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}