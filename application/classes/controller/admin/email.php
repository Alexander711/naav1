<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Email extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/email'] = 'Email';
    }
    public function action_index()
    {
       // получаем общее количество
        $count = ORM::factory('emailsender')->count_all();

        // передаем значение количества товаров в модуль pagination и формируем ссылки
        $pagination = Pagination::factory(
            array('total_items' => $count, 'items_per_page' => 50,)
        );

        $email = ORM::factory('emailsender')
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset);

        $this->view = View::factory('backend/email/all')
                          ->set('email', $email)
                          ->set('pagination', $pagination);

        $this->template->content = $this->view;
    }
    public function action_send()
    {
        $mail_from_addresses = array(
            'no-reply@as-avtoservice.ru' => 'no-reply@as-avtoservice.ru'
        );
        $this->values['mail_to'] = Arr::get($_GET, 'email');
        if ($_POST)
        {
            $email = ORM::factory('emailsender')->values($_POST, array('text', 'title', 'mail_to', 'mail_from'));
            $email->date_create = Date::formatted_time();
            try
            {
                $email->save();
                Message::set(Message::SUCCESS, 'Сообщение отправлено в очередь на отправку');
                $this->request->redirect('admin/email');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('backend/email/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('mail_from_addresses', $mail_from_addresses);
        $this->template->title = 'Отправка сообщения';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}