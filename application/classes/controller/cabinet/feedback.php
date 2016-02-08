<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Feedback extends Controller_Cabinet
{
    function before()
    {
        parent::before();
        $this->template->bc['cabinet/feedback'] = 'Обратная связь';
    }
    function action_index()
    {


	    if ($_POST)
        {
            $feedback = ORM::factory('feedback');
            try
            {
                $feedback->values($_POST, array('title', 'text'));
                $feedback->type = 1;
                $feedback->user_id = $this->user->id;
                $feedback->date = Date::formatted_time();
                $feedback->save();
                $email_view = View::factory('email/feedback')
                                  ->set('username', $this->user->username)
                                  ->set('title', $feedback->title)
                                  ->set('text', $feedback->text)
                                  ->render();

                Email::send('sekretar@as-avtoservice.ru', array('no-reply@as-avtoservice.ru', 'Ассоциация автосервисов'), $feedback->title, $email_view, TRUE);

	            Message::clear();
                Message::set(Message::SUCCESS, 'Спасибо! Ваше сообщение отправлено администрации сайта');
                $this->request->redirect('cabinet');
            }
            catch (ORM_Validation_Exception $e)
            {
	            Message::set(Message::ERROR, 'Произошла ошибка при отправке сообщения');
	            $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('frontend/cabinet/feedback/create_feedback')
                          ->set('errors', $this->errors)
                          ->set('values', $this->values);
        $this->template->title = 'Обратная связь';
        $this->template->content = $this->view;
    }


}