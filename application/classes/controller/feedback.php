<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Feedback extends Controller_Frontend
{
    private $_config = array();
    public function before()
    {
        parent::before();
        $this->_config = Kohana::$config->load('pages_titles.feedback');
        $this->template->bc['feedback'] = $this->_config['title'];
    }
    public function action_index()
    {
        $this->request->redirect('/');
    }

    public function action_add_city()
    {
        if ($_POST)
        {
            $feedback = ORM::factory('feedback');
            $feedback->type = 1;
            $feedback->title = 'Запрос на добавление города';
            $feedback->text = Arr::get($_POST, 'text');
            try
            {
                $feedback->user_id = $this->user->id;
                $feedback->date = Date::formatted_time();
                $feedback->save();
                Message::set(Message::SUCCESS, 'Ваш запрос успешно отправлен');
                $this->request->redirect('/');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('frontend/feedback/add_city')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = 'Запрос на добавление города';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}