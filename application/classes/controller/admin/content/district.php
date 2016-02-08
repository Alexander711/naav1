<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Content_District extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/content/district'] = 'Страницы поиска по округу';
    }
    /**
     * Обзор страниц поика округу
     * @return void
     */
    public function action_index()
    {
        $city_id = $this->request->param('id', 1);
        $cities = ORM::factory('city')
                     ->distinct('city.id')
                     ->join(array('okrug', 'district'))
                     ->on('district.city_id', '=', 'city.id');
        $content = ORM::factory('content_district')
                      ->join(array('okrug', 'district'))
                      ->on('district.id', '=',  'content_district.district_id')
                      ->where('district.city_id', '=', $city_id)
                      ->order_by('district.name', 'ASC');
        $this->view = View::factory('backend/content/district/all')
                          ->set('city_id', $city_id)
                          ->set('cities', $cities)
                          ->set('content', $content);
        $this->template->title =$this->template->bc['admin/content/district'];
        $this->template->content = $this->view;
    }
    /**
     * Редактирование страницы поиска по округу
     * @return void
     */
    public function action_edit()
    {
        $content = ORM::factory('content_district', $this->request->param('id', NULL));
        if (!$content->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_not_found'));
            $this->request->redirect('admin/content/district');
        }
        $this->values = $content->as_array();
        if ($_POST)
        {
            $content->values($_POST, array('text'));
            $content->date_edited = Date::formatted_time();
            $content->update();
            Message::set(Message::SUCCESS, 'Страница для поиска по округу "'.$content->district->name.'" отредактирована');
            $this->request->redirect('admin/content/district');
        }
        $this->view = View::factory('backend/content/cars_works_edit')
                          ->set('content', $content)
                          ->set('url', 'admin/content/district/edit/'.$content->id)
                          ->set('values', $this->values);
        $this->template->bc['#'] = 'Редактирование страницы поиска по округу '.$content->district->name;
        $this->template->content = $this->view;

    }
}