<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Adv extends Controller_Cabinet
{
    function action_index()
    {
        $services[0] = 'Выбрать компанию';
        foreach ($this->user->services->find_all() as $service)
        {
            $services[$service->id] = $service->name;
        }
        if ($_POST)
        {
            $feedback = ORM::factory('feedback');
            try
            {
                $feedback->values($_POST, array('title', 'text'));
                $feedback->type = 2;
                $feedback->user_id = $this->user->id;
                $feedback->service_id = Arr::get($_POST, 'service_id', 0);
                $feedback->date = Date::formatted_time();
                $feedback->save();

                $email_view = View::factory('email/adv')
                                  ->set('username', $this->user->username)
                                  ->set('title', $feedback->title)
                                  ->set('text', $feedback->text);
                if ($feedback->service_id != 0)
                {
                    $email_view->set('service', $this->user->services->where('id', '=', $feedback->service_id)->find());
                }
                $email_view->render();
                
                Email::send('sekretar@as-avtoservice.ru', array('no-reply@as-avtoservice.ru', 'Ассоциация автосервисов'), $feedback->title, $email_view, TRUE);
                Message::set(Message::SUCCESS, 'Спасибо! Ваше заявка принята на рассмотрение администрацией сайта');
                $this->request->redirect('cabinet');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('frontend/cabinet/adv/create_blank')
                          ->set('services', $services)
                          ->set('errors', $this->errors)
                          ->set('values', $this->values);
        $this->template->title = 'Реклама на сайте';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}