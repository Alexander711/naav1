<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Content_Works extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->_base_url = 'admin/content/works';
        $this->template->bc[$this->_base_url] = 'Страницы поиска по услугам';
    }
    /**
     * Обзор страниц для поиска по услуге
     * @return void
     */
    public function action_index()
    {
        $cities = ORM::factory('city')
                    ->distinct('id')
                    ->join(array('content_works', 'works'), 'RIGHT')
                    ->on('works.city_id', '=', 'city.id');

        $city = ORM::factory('city')
                    ->join(array('content_works', 'works'))
                    ->on('works.city_id', '=', 'city.id')
                    ->where('city.id', '=', $this->request->param('id', 1))
                    ->find();

        $content = $city->content_works;
        // Если производится поиск
        if ($_POST)
        {
            $this->validation = Validation::factory($_POST)
                                          ->rule('str', 'not_empty');
            if ($this->validation->check())
            {
                $content->join(array('works', 'works'))
                        ->on('works.id', '=', 'content_works.work_id')
                        ->where('name', 'LIKE', '%'.$this->validation['str'].'%');
                $content->reset(FALSE);
                if (count($content->find_all()) == 0)
                {
                    $this->errors['str'] = 'Поиск не дал результатов. Показаны все страницы';
                    $content->find_all();
                }
            }
            else
            {
                $this->errors = $this->validation->errors('search_on_site');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('backend/content/works/all')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('content', $content)
                          ->set('cities', $cities)
                          ->set('city_id', $this->request->param('id', 1));
        $this->template->title = 'Обзор страниц услуг';
        $this->template->content = $this->view;
    }
    /**
     * Редактирование страницы
     * @return void
     */
    public function action_edit()
    {
        $content = ORM::factory('content_works', $this->request->param('id', NULL));
        if (!$content->loaded())
        {
            Message::set(Message::ERROR, 'Нет такой услуги');
            $this->request->redirect('admin/content/works');
        }
        if ($_POST)
        {
            $content->text = Arr::get($_POST, 'text');
            $content->date_edited = Date::formatted_time();
            $content->update();
            Message::set(Message::SUCCESS, 'Страница для услуги "'.$content->work->name.'" отредактирована');
            $this->request->redirect('admin/content/works/index/'.$content->city->id);
        }
        else
        {
            $this->values = $content->as_array();
        }
        $this->view = View::factory('backend/content/cars_works_edit')
                          ->set('content', $content)
                          ->set('url', 'admin/content/works/edit/'.$content->id)
                          ->set('values', $this->values);
        $this->template->bc['#'] = 'Редактирование страницы для '.$content->work->name;
        $this->template->title = 'Редактирование страницы услуги';
        $this->template->content = $this->view;
    }
    /**
     * Указания текста по умолчанию
     * @return void
     */
    public function action_default_text()
    {
        $this->values['text'] = Kohana::$config->load('default_search_content.search_by_work');
        if ($_POST)
        {
            $this->validation = Validation::factory($_POST)
                                          ->rule('text', 'not_empty');
            if ($this->validation->check())
            {
                Kohana::$config->load('default_search_content')->set('search_by_work', $this->validation['text']);
                Message::set(Message::SUCCESS, 'Текст по умолчанию для поиска по услуге изменен');
                $this->request->redirect($this->_base_url);
            }
            else
            {
                $this->values = $_POST;
                $this->errors['text'] = 'Введите текст';
            }
        }
        $this->template->title = 'Текст по умолчанию для поиска по услуге';
        $this->view = View::factory('backend/content/form_default_text')
                          ->set('errors', $this->errors)
                          ->set('values', $this->values)
                          ->set('title', $this->template->title);

        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}