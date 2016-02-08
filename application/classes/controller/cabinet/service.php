<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Service extends Controller_Cabinet
{
    function action_index()
    {
        $this->view = View::factory('frontend/cabinet/service/all')
                          ->set('service', $this->user->services);
        $this->template->title = $this->site_name.'Мои сервисы';
        $this->template->content = $this->view;
    }
    function action_add()
    {
        if ($_POST)
        {
            $service = ORM::factory('service');
            try
            {
                $service->values($_POST, array('name'));
                $service->user_id = $this->user->id;
                $service->date = Date::formatted_time();
                $service->save();
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('frontend/cabinet/service/add')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = $this->site_name.'Добавление сервиса';
        $this->template->content = $this->view;
    }
    public function action_edit_info()
    {
        $this->view = View::factory('frontend/cabinet/service/edit');
        $this->template->title = $this->site_name.'Редактирование сервиса';
        $this->template->content = $this->view;
    }
    public function action_edit_items()
    {

    }
    function action_delete()
    {
        $this->view = View::factory('frontend/cabinet/service/delete');
        $this->template->title = $this->site_name.'Удаление сервиса';
        $this->template->content = $this->view;
    }
    public function action_activate()
    {

    }
    public function action_deactivate()
    {

    }
}