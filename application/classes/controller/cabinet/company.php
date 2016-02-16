<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Cabinet_Company extends Controller_Cabinet {

    private $_edit_urls = array(
        'edit' => array(
            'url' => 'cabinet/company/edit/:company_id',
            'text' => 'Данные компании'
        ),
        'gallery' => array(
            'url' => 'cab'
        )
    );

    function action_index() {
        $result = array();


        $company = $this->user->services->find_all();

        foreach ($company as $comp) {
            $groups = $comp->group->find_all();

            foreach ($groups as $group) {
                if ($group->parrent_id != 0) {
                    $par_group = ORM::factory('group', $group->parrent_id);

                    $result[] = array(
                        'group_name' => $par_group->name,
                        'sub_group_name' => $group->name,
                        'id_service' => $comp->id,
                        'service_name' => $comp->name,
                        'count_news' => count($comp->news->find_all()),
                        'count_stocks' => count($comp->stocks->find_all()),
                        'count_vacancies' => count($comp->vacancies->find_all()),
                        'count_reviews' => count($comp->reviews->find_all()),
                    );
                } else {
                    $result[] = array(
                        'group_name' => $group->name,
                        'id_service' => $comp->id,
                        'service_name' => $comp->name,
                        'count_news' => count($comp->news->find_all()),
                        'count_stocks' => count($comp->stocks->find_all()),
                        'count_vacancies' => count($comp->vacancies->find_all()),
                        'count_reviews' => count($comp->reviews->find_all()),
                    );
                }
            }
        }

        $this->view = View::factory('frontend/cabinet/companies')
                ->set('result', $result);
        $this->template->title = $this->site_name . 'Мои фирмы';
        $this->template->bc['#'] = 'Мои фирмы';
        $this->template->content = $this->view;
    }

    /**
     * Галерея
     */
    public function action_gallery() {
        $service = ORM::factory('service', $this->request->param('id'));
        $this->view = View::factory('frontend/cabinet/company/gallery')
                ->set('company', $service);
        $this->template->content = $this->view;
        $this->blueImpUploaderJs();
        $this->template->bc['#'] = 'Галерея ' . $service->name;
    }

    public function action_add() {

        $city_options = ORM::factory('city')->get_all_cities();
        $work_category = ORM::factory('workcategory');
        $cars = ORM::factory('car_brand')->get_cars_as_array();
        $discounts = ORM::factory('discount')->get_all_as_array();
        $org_types = ORM::factory('orgtype')->get_all_as_array();
        $groups = ORM::factory('group')->get_groups_as_array();
        $options_sub_group = array();
        $groups_post = array();
        $type = 0;

        $groups = array(0 => 'Выберите категорию') + $groups;

        if ($_POST) {
            $company = ORM::factory('service');

            try {

                $extra_validation = Validation::factory($_POST);

                if (isset($_POST['sub_group_id'])) {
                    $extra_validation->rule('sub_group_id', 'Model_Group::check_valid_group', array(':value'));

                    $groups_post = Arr::get($_POST, 'sub_group_id', NULL);
                } else {
                    $extra_validation->rule('group_id', 'Model_Group::check_valid_group', array(':value'));

                    $groups_post = Arr::get($_POST, 'group_id', NULL);
                }

                $extra_validation->rule('city_name', 'not_empty')
                        ->rule('city_name', 'Model_Service::check_valid_city', array(':value'));

                $company->values($_POST, array('name', 'org_type', 'inn', 'director_name', 'contact_person', 'address', 'phone', 'code', 'fax', 'site', 'about', 'work_times', 'discount_id', 'coupon_text', 'ymap_lng', 'ymap_lat'));
                $company->user_id = $this->user->id;
                $company->city_id = ORM::factory('service')->get_id_city(Arr::get($_POST, 'city_name', NULL));
                $company->metro_id = Arr::get($_POST, 'metro_id', NULL);
                $company->district_id = Arr::get($_POST, 'district_id', NULL);
                $company->active = 1;
                $company->date_create = DATE::formatted_time();
                $company->date_edited = Date::formatted_time();

                $company->save($extra_validation);

                $cars_input = Arr::get($_POST, 'model', array());
                foreach ($cars_input as $c) {
                    $company->add('cars', $c);
                }

                $company->add('group', $groups_post);

                $works_input = Arr::get($_POST, 'work', array());
                foreach ($works_input as $w) {
                    $company->add('works', $w);
                }
                
                /*if ($company->type == 1)
                {
                    $works_input = Arr::get($_POST, 'work', array());
                    foreach ($works_input as $w)
                    {
                        $company->add('works', $w);
                    }
                    $notice_type = 'service_create';
                }
                else
                {
                    $notice_type = 'shop_create';
                }

                $notice = ORM::factory('notice')->where('type', '=', $notice_type)->find();
                DB::insert('notices_services', array('service_id', 'notice_id', 'date'))->values(array($company->id, $notice->id, Date::formatted_time()))->execute();*/
                Logger::write(Logger::ADD, 'Пользователь добавил компанию ' . $company->name, $this->user);
                Message::set(Message::SUCCESS, 'Фирма успешно добавлена! ' . HTML::anchor('cabinet/company/gallery/' . $company->id, 'Загрузить изображения для галерии'));
                $this->request->redirect('cabinet/company');
            } catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('models');

                if (isset($errors['_external'])) {
                    foreach ($errors['_external'] as $key => $value) {
                        $errors[$key] = $value;
                    }
                    unset($errors['_external']);
                }

                if ($_POST['group_id'] != 0) {

                    $options_sub_group = ORM::factory('group')->get_sub_groups_for_one_group(Arr::get($_POST, 'group_id', NULL));

                    if (!empty($options_sub_group)) {
                        $options_sub_group = array(0 => "Выберите подкатегорию") + $options_sub_group;
                    }
                    
                    $type = ORM::factory('group')->get_type_group(Arr::get($_POST, 'group_id', NULL));
                }
                
                $this->errors = $errors;
                $this->values = $_POST;
                $group_type = empty($this->values['group']) ? 0 : $this->values['group'];
            }
        }

        $this->view = View::factory('frontend/cabinet/company/form')
                ->set('url', 'cabinet/company/add')
                ->set('groups', $groups)
                ->set('type', $type)
                ->set('org_types', $org_types)
                ->set('city', ORM::factory('city'))
                ->set('work_category', $work_category)
                ->set('auto_models', $cars)
                ->set('cities', $city_options)
                ->set('discounts', $discounts)
                ->set('options_sub_group', $options_sub_group)
                ->set('values', $this->values)
                ->set('errors', $this->errors);

        $this->add_js('http://api-maps.yandex.ru/1.1/index.xml?key=' . $this->settings['YMaps_key'] . '&onerror=map_alert');
        $this->template->title = 'Добавление компании';
        $this->template->bc['#'] = 'Добавление компании';
        $this->add_js('assets/js/script.js');
        $this->template->content = $this->view;
    }

    public function action_edit() {
        $company = ORM::factory('service', $this->request->param('id', NULL));
        if (!$company->loaded() OR $company->user_id != $this->user->id) {
            $this->request->redirect('cabinet/company');
        }

        $this->values = $company->as_array();

        $city = ORM::factory('city');
        foreach ($city->find_all() as $c) {
            $city_options[$c->id] = $c->name;
        }
        $work_category = ORM::factory('workcategory');
        $cars = ORM::factory('car_brand')->get_cars_as_array();
        $discounts = ORM::factory('discount')->get_all_as_array();
        $org_types = ORM::factory('orgtype')->get_all_as_array();
        if ($_POST) {
            try {
                $company->values($_POST, array('name', 'org_type', 'inn', 'director_name', 'contact_person', 'address', 'phone', 'type', 'code', 'fax', 'site', 'about', 'work_times', 'discount_id', 'coupon_text', 'ymap_lat', 'ymap_lng'));
                $company->user_id = $this->user->id;
                $company->city_id = Arr::get($_POST, 'city_id', NULL);
                $company->metro_id = Arr::get($_POST, 'metro_id', NULL);
                $company->district_id = Arr::get($_POST, 'district_id', NULL);
                $company->date_edited = DATE::formatted_time();
                $company->update();
                $company->remove('cars');
                $company->remove('works');
                $cars_input = Arr::get($_POST, 'model', array());
                foreach ($cars_input as $c) {
                    $company->add('cars', $c);
                }
                if ($company->type == 1) {
                    $works_input = Arr::get($_POST, 'work', array());
                    foreach ($works_input as $w) {
                        $company->add('works', $w);
                    }
                }
                Logger::write(Logger::EDIT, 'Пользователь отредактировал данные компании ' . $company->name, $this->user);
                Message::set(Message::SUCCESS, 'Фирма успешно отредактирована!');
                $this->request->redirect('cabinet/company');
            } catch (ORM_Validation_Exception $e) {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
                $type = $this->values['type'];
            }
        } else {
            foreach ($company->works->find_all() as $w) {
                $this->values['work'][$w->id] = $w->id;
            }
            foreach ($company->cars->find_all() as $c) {

                $this->values['model'][] = $c->id;
            }
            $type = $company->type;
        }



        $this->view = View::factory('frontend/cabinet/company/form')
                ->set('company', $company)
                ->set('url', 'cabinet/company/edit/' . $company->id)
                ->set('type', $type)
                ->set('company_types', $this->settings['company_types'])
                ->set('org_types', $org_types)
                ->set('city', $city)
                ->set('work_category', $work_category)
                ->set('auto_models', $cars)
                ->set('cities', $city_options)
                ->set('discounts', $discounts)
                ->set('values', $this->values)
                ->set('errors', $this->errors);
        $this->add_js('http://api-maps.yandex.ru/1.1/index.xml?key=' . $this->settings['YMaps_key'] . '&onerror=map_alert');
        $this->template->title = 'Редактирование компании';
        $this->template->bc['#'] = 'Редактирование компании';
        $this->template->content = $this->view;
    }

    public function action_delete() {
        $company = ORM::factory('service', $this->request->param('id', NULL));
        if (!$company->loaded() OR $company->user_id != $this->user->id) {
            $this->request->redirect('cabinet/company');
        }
        if ($_POST) {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel']) {
                $this->request->redirect('cabinet/company');
            }
            if ($action['submit']) {
                $title = $company->name;
                // Удалеие данных компании
                $company->delete_data()->delete();
                Logger::write(Logger::EDIT, 'Пользователь Удалил компанию ' . $title, $this->user);


                Message::set(Message::SUCCESS, 'Фирма "' . $title . '" удалена');
                $this->request->redirect('cabinet/company');
            }
        }
        $this->view = View::factory('frontend/cabinet/delete')
                ->set('url', 'cabinet/company/delete/' . $company->id)
                ->set('text', 'Вы действительно хотите удалить фирму ' . $company->name);
        $this->template->title = $this->site_name . 'Удаление фирмы';
        $this->template->bc['#'] = 'Удаление фирмы';
        $this->template->content = $this->view;
    }

    public function action_get_sub_groups() {
        $result['sub_groups'] = ORM::factory('group')->get_sub_groups_for_one_group($_POST['group_id']);

        $result['type_group'] = ORM::factory('group')->get_type_group($_POST['group_id']);

        print_r(json_encode($result));
        exit;
    }

    public function action_get_cities() {
        $cities = ORM::factory('service')->get_cities($_POST);

        print_r(json_encode($cities));
        exit;
    }

    public function action_get_groups_this_type() {
        $groups = ORM::factory('group')->get_groups_this_type($_POST['type']);

        print_r(json_encode($groups));
        exit;
    }

}
