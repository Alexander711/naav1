<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Service_Qa extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/service/qa'] = 'Запросы';
    }
    /**
     * Обзор запросов
     * @return void
     */
    public function action_index()
    {
        $qa = ORM::factory('question');


        $this->view = View::factory('backend/qa/all')
                          ->set('qa', $qa);
        $this->template->title = 'Запросы пользователей';
        $this->template->content = $this->view;
    }
    /**
     * Редактирование запроса
     * @return void
     */
    public function action_edit()
    {
        $qa = ORM::factory('question', $this->request->param('id', NULL));
        if (!$qa->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'qa_not_found'));
            $this->request->redirect('admin/service/qa');
        }
        $car_brands = array('0' => 'Выбрать марку автомобиля') + ORM::factory('car_brand')->get_cars_as_array();
        $car_brand_id = Arr::get($_POST, 'car_id', 0);
        $car_models = array('0' => 'Выбрать модель автомобиля') + ORM::factory('car_model')->get_models($car_brand_id);
        $gearboxes = ORM::factory('gearbox')->get_gearboxes();
        $work_category = ORM::factory('workcategory');
        $cities = ORM::factory('city')->get_all_cities();
        if ($_POST)
        {
            try
            {
                $qa->values($_POST, array('contact', 'text', 'email', 'car_id', 'model_id', 'city_id', 'gearbox_id', 'year', 'vin', 'volume', 'phone'));
                $qa->for_service_has_car = Arr::get($_POST, 'for_service_has_car', 0);
                $qa->for_service_address = Arr::get($_POST, 'for_service_address', 0);
                $qa->active = Arr::get($_POST, 'active', 0);
                $qa->update();
                $qa->remove('works');
                $works_input = Arr::get($_POST, 'work', array());
                foreach ($works_input as $w)
                {
                    $qa->add('works', $w);
                }
                Message::set(Message::SUCCESS, 'Запрос успешно отредактирован');
                $this->request->redirect('admin/service/qa');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $works = array();
            foreach ($qa->works->find_all() as $work)
            {
                $works[] = $work->id;
            }
            $this->values = $qa->as_array();
            $this->values['work'] = $works;
        }
        $this->view = View::factory('backend/qa/form')
                          ->set('url', 'admin/service/qa/edit/'.$qa->id)
                          ->set('gearboxes', $gearboxes)
                          ->set('car_models', $car_models)
                          ->set('car_brands', $car_brands)
                          ->set('work_category', $work_category)
                          ->set('cities', $cities)
                          ->set('selected_works', Arr::get($this->values, 'work', array()))
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->add_js('assets/js/gallery/jquery.lightbox-0.5.js');
        $this->add_js('assets/js/main.js');
        $this->template->title = 'Редактирование запроса '.$qa->carbrand->name.' '.$qa->model->name;
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Активация запроса
     * @return void
     */
    public function action_activate()
    {
        $qa = ORM::factory('question', $this->request->param('id', NULL));
        if (!$qa->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'qa_not_found'));
            $this->request->redirect('admin/service/qa');
        }
        if ($qa->active == 1)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'qa_is_active'));
            $this->request->redirect('admin/service/qa');
        }
	    foreach ($qa->services->find_all() as $id => $service){
		    $service_email = empty($service->email) ? $service->user->email:$service->email;
		    if(!empty($service_email)) {
			    $email_view = View::factory('email/qa_activate')
                                  ->set('text', $qa->text)
                                  ->set('contact', $qa->contact)
                                  ->set('id', $qa->id)
                                  ->render();

			    $message = ORM::factory('emailsender');
                $message->mail_to = $service_email;
                $message->mail_from = 'no-reply@as-avtoservice.ru';
                $message->title = 'Уведомление о новом запросе от ассоциации автосервисов';
                $message->text = $email_view;
                $message->status = 'queue';
                $message->date_create = Date::formatted_time();
                $message->save();
		    }
	    }


        DB::update('questions')->set(array('active' => 1))->where('id', '=', $qa->id)->execute();

        Message::set(Message::SUCCESS, 'Запрос активирован');
        $this->request->redirect($this->request->referrer());
    }
    /**
     * Деактивация запроса
     * @return void
     */
    public function action_deactivate()
    {
        $qa = ORM::factory('question', $this->request->param('id', NULL));
        if (!$qa->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'qa_not_found'));
            $this->request->redirect('admin/service/qa');
        }
        if ($qa->active == 0)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'qa_is_unactive'));
            $this->request->redirect('admin/service/qa');
        }
        DB::update('questions')->set(array('active' => 0))->where('id', '=', $qa->id)->execute();

        Message::set(Message::SUCCESS, 'Запрос деактивирован');
        $this->request->redirect($this->request->referrer());
    }
    /**
     * Удаление запроса
     * @return void
     */
    public function action_delete()
    {
        $qa = ORM::factory('question', $this->request->param('id', NULL));
        if (!$qa->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'qa_not_found'));
            $this->request->redirect('admin/service/qa');
        }
        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('admin/service/qa');
            }
            if ($action['submit'])
            {
                $qa->remove('works');
                $qa->remove('services');

                foreach ($qa->answers->find_all() as $answer)
                    $answer->delete();

                $qa->delete();
                Message::set(Message::SUCCESS, 'Запрос удален');
                $this->request->redirect('admin/service/qa');
            }
        }
        $this->view = View::factory('backend/delete')
                          ->set('url', 'admin/service/qa/delete/'.$qa->id)
                          ->set('text', 'Вы действительно хотите удалить запрос?');
        $this->template->title = 'Удаление запроса';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}