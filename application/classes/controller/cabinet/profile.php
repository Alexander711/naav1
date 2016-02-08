<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Profile extends Controller_Cabinet
{
    function before()
    {
        parent::before();
        $this->template->bc['cabinet/profile'] = 'Настройки профиля';
    }
    function action_index()
    {
        if ($_POST)
        {
            try
            {
                $this->user->values($_POST, array('username', 'email'));
                $this->user->update();
                Message::set(Message::SUCCESS, 'Настройки профиля изменены');
                $this->request->redirect('cabinet/profile');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $this->user->as_array();
        }
        $this->view = View::factory('frontend/cabinet/profile/index')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = $this->site_name.'Настройки профиля';
        $this->template->content = $this->view;
    }
    function action_edit_password()
    {
        if ($_POST)
        {
            $this->validation = Validation::factory($_POST)
                                          ->rule('password', 'not_empty', array(':value'))
                                          ->rule('password', 'min_length', array(':value', 6))
                                          ->rule('password', 'alpha_dash', array(':value'));
            try
            {
                $this->user->password = $_POST['password'];
                $this->user->update($this->validation);
                Message::set(Message::SUCCESS, 'Пароль успешно изменен');
                $this->request->redirect('cabinet/profile');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
            }
        }
        $this->view = View::factory('frontend/cabinet/profile/edit_password')
                          ->set('errors', $this->errors);
        $this->template->title = $this->site_name.'Смена пароля';
        $this->template->content = $this->view;
    }
}