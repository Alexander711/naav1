<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Message extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/message'] = 'Сообщения пользователям';
    }
    public function action_index()
    {
        $message = ORM::factory('message');
        $this->view = View::factory('backend/message/all')
                          ->set('message', $message);
        $this->template->title = 'Отправленные сообщения';
        $this->template->content = $this->view;
    }
    /**
     * Отправка сообщения на Email
     * @return void
     */
    public function action_send()
    {
        $user = ORM::factory('user', $this->request->param('id', NULL));
        if (!$user->loaded())
        {
            $this->request->redirect('admin');
        }
        $feedback_id = Arr::get($_GET, 'feedback', 0);
        $email_from = array(
            'no-reply' => 'no-reply@as-avtoservice.ru',
            'sekretar' => 'sekretar@as-avtoservice.ru'
        );
        if ($_POST)
        {
            $message = ORM::factory('message');
            $message->values($_POST, array('title', 'text', 'from'));
            $message->user_id = $user->id;
            $message->feedback_id = $feedback_id;
            $message->date = Date::formatted_time();
            try
            {
                $message->save();
                $this->add_to_email_queue($user->id, $message->id, $message->from);
                Message::set(Message::SUCCESS, 'Сообщения пользователю "'.$user->username.'" отправлено в очередь на отправку');
                $this->request->redirect('admin/message');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('backend/message/send')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('email_from', $email_from)
                          ->set('user', $user);
        $this->template->title = 'Отправка сообщения';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Отправка на очередь на отправку
     * @return void
     */
    private function add_to_email_queue($user_id, $message_id, $from)
    {
        DB::insert('message_subscribe', array('user_id', 'message_id', 'from'))->values(array($user_id, $message_id, $from))->execute();
    }
}