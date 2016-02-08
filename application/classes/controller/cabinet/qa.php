<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Qa extends Controller_Cabinet
{
    public function before()
    {
        parent::before();
        $this->template->bc['cabinet/qa'] = __('cb_questions');
    }
    public function action_index()
    {
        $question = ORM::factory('question');
        $this->view = View::factory('frontend/cabinet/qa/all')
                          ->set('question', $question);
        $this->template->title = $this->site_name.'Запросы автолюбителей';
        $this->template->content = $this->view;

    }
    public function action_view()
    {
        $question = ORM::factory('question', $this->request->param('id', NULL));
        if (!$question->loaded())
        {
	        Message::set(Message::ERROR, 'Запрос не найден');
            $this->request->redirect('cabinet/qa');
        }
//		C::pr($question);
	    if ($question->active == 0) {
		    Message::set(Message::ERROR, 'Запрос не найден');
            $this->request->redirect('cabinet/qa');
        }
        $works = array();
        foreach ($question->works->find_all() as $work)
        {
            $works[] = $work->name;
        }
        $services = array();
        foreach ($this->user->services->find_all() as $service)
        {
            $services[$service->id] = $service->name;
        }
//		C::pr($this->user);
        if ($_POST)
        {
            $answer = ORM::factory('answer');
            try
            {
                $answer->values($_POST, array('text', 'service_id'));
                $answer->question_id = $question->id;
               
                $answer->date = Date::formatted_time();
                $answer->save();

	            $email_view = View::factory('email/qa_answer')
                                  ->set('text', $answer->text)
                                  ->set('username', $question->contact)
                                  ->set('message_id', $answer->id)
                                  ->set('service_id', $answer->service_id)
                                  ->set('service_name', $services[$answer->service_id])
                                  ->render();

                $message = ORM::factory('emailsender');
                $message->mail_to = $question->email;
                $message->mail_from = 'no-reply@as-avtoservice.ru';
                $message->title = 'Ответ на ваш вопрос на сайте as-avtoservice.ru';
                $message->text = $email_view;
                $message->status = 'queue';
                $message->date_create = Date::formatted_time();
                $message->save();


                Message::set(Message::SUCCESS, 'Ответ на запрос успешно добавлен');
                $this->request->redirect('cabinet/qa');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('frontend/cabinet/qa/form')
                          ->set('url', 'cabinet/qa/view/'.$question->id)
                          ->set('question', $question)
                          ->set('works', $works)
                          ->set('services', $services)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = $this->site_name.'Ответ на запрос';
        $this->template->bc['#'] = 'Ответ на запрос';
        $this->template->content = $this->view;
    }
    public function action_edit_response()
    {

    }
    public function action_delete_response()
    {

    }
}