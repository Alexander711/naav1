<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Services extends Controller_Backend
{
    /**
     * Содержит все возможные подсказаки, правая сторона значения не имеет
     * @var array
     */
    private $_tips = array(
        'service_all_tip' => 'Подсказка на странице сервисов'
    );
    public function before()
    {
        parent::before();
        $this->_base_url = 'admin/services';
        $this->template->bc[$this->_base_url] = 'Автосервисы';
    }
    /**
     * Обзор сервисов
     * @return void
     */
    public function action_index()
    {
        $type = $this->request->param('type', 1);
        $current_city = $this->request->param('city', 0);
        $types = array(
            '1' => array ('name' => 'Автосервисы', 'css_class' => ''),
            '2' => array('name' => 'Магазины автозапчастей', 'css_class' => '')
        );
        $types[$type]['css_class'] = 'active';
        
        $service = ORM::factory('service')->where('type', '=', $type);
        $cities = ORM::factory('city')->get_cities();
        if ($current_city != 0 AND !array_key_exists($current_city, $cities))
        {
            $current_city = 0;
        }

        if (Request::current()->method() == Request::POST)
        {
            $this->validation = Validation::factory($_POST)
                                          ->rule('str', 'not_empty');
            if ($this->validation->check())
            {
                $service->where('name', 'LIKE', '%'.$this->validation['str'].'%');
                $service->reset(FALSE);
                if (count($service->find_all()) == 0)
                {
                    Message::set(Message::ERROR, 'Не найдено, показаны все');
                    $service->find_all();
                }

            }
            else
            {
                $this->errors = $this->validation->errors('search_on_site');
                $this->values = $_POST;
            }
            $this->values['str'] = $this->validation['str'];
        }
        else
        {
            if ($current_city != 0)
            {
                $service->where('city_id', '=', $current_city);
            }
        }
        $cities = array(0 => 'Все') + $cities;

        $tip = Cookie::get('service_all_tip', NULL);

        $this->view = View::factory('backend/service/all')
                          ->set('values', $this->values)
                          ->set('cities', $cities)
                          ->set('types', $types)
                          ->set('type', $type)
                          ->set('current_city', $current_city)
                          ->set('tip', $tip);
        $services_view = View::factory('backend/service/services_table')
                             ->set('service', $service);
        $this->template->title = ($type == 1) ? 'Автосервисы' : 'Магазины автозапчастей';
        $this->template->bc['admin/services'] = $this->template->title;
        $this->template->content = $this->view.$services_view;
    }
    /**
     * Редактирование сервиса
     * @return void
     */
    public function action_edit()
    {
        $service = ORM::factory('service', $this->request->param('id', NULL));
        if (!$service->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'service_not_found'));
            $this->request->redirect('admin/services');
        }
        $settings = Kohana::$config->load('settings');
        $work_category = ORM::factory('workcategory');
        $org_types = ORM::factory('orgtype')->get_all_as_Array();
        $cities = ORM::factory('city')->get_all_cities();
        $districts = ORM::factory('district')->get_all_by_city($service->city_id);
        $metro_stations = ORM::factory('metro')->get_all_by_city($service->city_id);
        $car_brands = ORM::factory('car_brand')->get_cars_as_array();
        $discounts = ORM::factory('discount')->get_all_as_array();
        if ($_POST)
        {
            try
            {
                $name = $service->name;
                $service->values($_POST, array('city_id',
                                               'name',
                                               'inn',
                                               'org_type',
                                               'type',
                                               'director_name',
                                               'contact_person',
                                               'address',
                                               'code',
                                               'fax',
                                               'phone',
                                               'site',
                                               'about',
                                               'work_times',
                                               'discount_id',
                                               'coupon_text'));
                $service->metro_id = Arr::get($_POST, 'metro_id', NULL);
                $service->district_id = Arr::get($_POST, 'district_id', NULL);
                //$service->date_edited = Date::formatted_time();
                $service->active = Arr::get($_POST, 'active', 0);
                $service->update();
                
                $service->remove('cars');
                $service->remove('works');

                $cars_input = Arr::get($_POST, 'model', array());
                foreach ($cars_input as $c)
                {
                    $service->add('cars', $c);
                }

                if ($service->type == 1)
                {
                    $works_input = Arr::get($_POST, 'work', array());
                    foreach ($works_input as $w)
                    {
                        $service->add('works', $w);
                    }
                }

                Message::set(Message::SUCCESS, 'Компания '.$name.' успешно отредактирована');
                $this->request->redirect('admin/services');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $service->as_array();
            foreach ($service->works->find_all() as $w)
            {
                $this->values['work'][$w->id] = $w->id;
            }
            foreach ($service->cars->find_all() as $c)
            {

                $this->values['model'][] = $c->id;
            }

        }

        $this->values['id'] = $service->id;

        $this->view = View::factory('backend/service/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('service', $service)
                          ->set('user', $service->user)
                          ->set('company_types', $settings['company_types'])
                          ->set('org_types', $org_types)
                          ->set('cities', $cities)
                          ->set('districts', $districts)
                          ->set('stations', $metro_stations)
                          ->set('auto_models', $car_brands)
                          ->set('work_category', $work_category)
                          ->set('discounts', $discounts);

        $this->template->title = 'Редактирование "'.$service->name.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->add_js('assets/js/gallery/jquery.lightbox-0.5.js');
        $this->add_js('assets/js/main.js');
        $this->template->content = $this->view;

    }
    public function action_gallery()
    {
        $service = ORM::factory('service', $this->request->param('id'));
        $this->view = View::factory('system/blueimpuploader_gallery')
                          ->set('service', $service);
        $this->template->content = $this->view;
        $this->blueImpUploaderJs();
        $this->template->bc['#'] = 'Галерея '.$service->name;
    }
    /**
     * Удаление сервиса
     * @return void
     */
    public function action_delete()
    {

        $service = ORM::factory('service', $this->request->param('id'));
        if (!$service->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'service_not_found'));
            $this->request->redirect('admin/services');
        }

        $default_cause_text = Kohana::$config->load('notice.company_deleting_cause');

        if (Request::current()->method() == Request::POST)
        {
            // Отправка уведомления
            if (Arr::get($_POST, 'send_notice'))
            {
                // Причина удаления
                $cause_text = (trim(Arr::get($_POST, 'delete_cause'))) ? Arr::get($_POST, 'delete_cause') : $default_cause_text;

                // Шаблон уведомления
                $notice_template = ORM::factory('NoticeTemplate')
                                      ->where('name', '=', 'company_has_been_deleted')
                                      ->find();

                $notice = ORM::factory('notice');
                $notice->title = $notice_template->title;
                $notice->date = Date::formatted_time();
                $notice->type = 'user';
                $notice->for = 'optional';
                $notice->text = __($notice_template->text, array(':cause' => $cause_text, ':login' => $service->user->username));

                $notice->save()
                       ->add('users', $service->user->id);




            }

            Message::set(Message::SUCCESS, __(Kohana::message('admin', 'company_has_been_deleted'), array(':company_name' => $service->get_full_name())));

            // Удаление всего, что связано с компанией
            $service->delete_data()
                    ->delete();

            $this->request->redirect('admin/services');
        }
        $this->template->title = 'Удаление компании';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/service/delete')
                          ->set('full_name', $service->get_full_name())
                          ->set('company_deleting_cause', $default_cause_text);
        $this->template->content = $this->view;
    }
    /**
     * Активация сервиса
     * @return void
     */
    public function action_activate()
    {
        $service = ORM::factory('service', $this->request->param('id', NULL));
        if (!$service->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'service_not_found'));
            $this->request->redirect('admin/services');
        }
        if ($service->active != 0)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'service_is_active'));
            $this->request->redirect('admin/services');
        }
        DB::update('services')->set(array('active' => 1))->where('id', '=', $service->id)->execute();

        Message::set(Message::SUCCESS, 'Автосервис '.$service->name.' активирован');
        $this->request->redirect($this->request->referrer());
    }
    /**
     * Деактивация сервиса
     * @return void
     */
    public function action_deactivate()
    {
        $service = ORM::factory('service', $this->request->param('id', NULL));
        if (!$service->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'service_not_found'));
            $this->request->redirect('admin/services');
        }
        if ($service->active == 0)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'service_is_unactive'));
            $this->request->redirect('admin/services');
        }
        DB::update('services')->set(array('active' => 0))->where('id', '=', $service->id)->execute();

        Message::set(Message::SUCCESS, 'Автосервис '.$service->name.' деактивирован');
        $this->request->redirect($this->request->referrer());
    }
    /**
     * Просмотр новостей сервиса
     * @return void
     */
    public function action_news()
    {
        $service = ORM::factory('service', $this->request->param('id', NULL));
        if (!$service->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'service_not_found'));
            $this->request->redirect('admin/services');
        }
        //$service->news->reset(FALSE);
        if (count($service->news->find_all()) == 0)
        {
            $this->view = 'У данной компании нет ни одной новости';
        }
        else
        {
            $this->view = View::factory('backend/news/service/all')
                              ->set('news', $service->news);
        }
        $this->template->title = 'Новости автосервиса';
        $this->template->bc['#'] = 'Новости '.$service->name;
        $this->template->content = $this->view;
    }
    /**
     * Просмотр акций сервиса
     * @return void
     */
    public function action_stocks()
    {
        $service = ORM::factory('service', $this->request->param('id', NULL));
        if (!$service->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'service_not_found'));
            $this->request->redirect('admin/services');
        }
        if (count($service->stocks->find_all()) == 0)
        {
            $this->view = 'У данной компании нет ни одной акции';
        }
        else
        {
            $this->view = View::factory('backend/stock/all')
                              ->set('stock', $service->stocks);
        }
        $this->template->title = 'Акции автосервиса';
        $this->template->bc['#'] = 'Акции '.$service->name;
        $this->template->content = $this->view;

    }
    /**
     * Просмотр вакансий сервиса
     * @return void
     */
    public function action_vacancies()
    {
        $service = ORM::factory('service', $this->request->param('id', NULL));
        if (!$service->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'service_not_found'));
            $this->request->redirect('admin/services');
        }
        if (count($service->vacancies->find_all()) == 0)
        {
            $this->view = 'У данной компании нет ни одной вакансии';
        }
        else
        {
            $this->view = View::factory('backend/vacancy/all')
                              ->set('vacancy', $service->vacancies);
        }
        $this->template->title = 'Вакансии автосервиса';
        $this->template->bc['#'] = 'Вакансии '.$service->name;
        $this->template->content = $this->view;
    }
    /**
     * Просмотр отзывов сервиса
     * @return void
     */
    public function action_reviews()
    {
        $service = ORM::factory('service', $this->request->param('id', NULL));
        if (!$service->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'service_not_found'));
            $this->request->redirect('admin/services');
        }
        if (count($service->reviews->find_all()) == 0)
        {
            $this->view = 'К данной компании не ни одного отзыва';
        }
        else
        {
            $this->view = View::factory('backend/review/all')
                              ->set('review', $service->reviews);
        }
        $this->template->title = 'Отзывы к компании';
        $this->template->bc['#'] = 'Отзывы к '.$service->name;
        $this->template->content = $this->view;
    }
    /**
     * Закрыть подсказки
     * @return void
     */
    public function action_close_tip()
    {
        $tip_id = $this->request->param('id', NULL);
        if (!array_key_exists($tip_id, $this->_tips))
        {
            $this->request->redirect('admin/services');
        }
        Cookie::set($tip_id, 1, 1314000);
        $this->request->redirect('admin/services');
    }
    /**
     * Показать подсказки
     * @return void
     */
    public function action_open_tip()
    {
        $tip_id = $this->request->param('id', NULL);
        if (!array_key_exists($tip_id, $this->_tips))
        {
            $this->request->redirect('admin/services');
        }
        Cookie::delete($tip_id);
        $this->request->redirect('admin/services');
    }
}