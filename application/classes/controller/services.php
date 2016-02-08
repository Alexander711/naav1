<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Services extends Controller_Frontend
{
    function action_index()
    {
        $this->request->redirect('filter/auto/'.$this->_selected_city_ids['has_cars']);
    }
    function action_view()
    {

	    $open_coupon = Arr::get($_GET, 'print_coupon', FALSE);

        $service = ORM::factory('service', $this->request->param('id', NULL));

        if (!$service->loaded() || !$service->active)
        {
            Message::set(Message::ERROR, 'Такой сервис не найден');
            $this->request->redirect('/');
        }

        $this->validation = Validation::factory($_POST)
                                      ->rule('antibot', 'not_empty');
        if ($_POST)
        {
            $review = ORM::factory('review');
            try
            {
                $review->values($_POST, array('text', 'email'));
                $review->date = Date::formatted_time();
                $review->service_id = $service->id;
                $review->active = 0;
                //$review->user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $review->save($this->validation);
                Message::set(Message::SUCCESS, Kohana::message('success_msg', 'review_created'));
                $this->request->redirect('services/'.$service->id);
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }

        $this->view = View::factory('frontend/services/view_service')
                          ->set('service', $service)
                          ->set('open_coupon', $open_coupon)
                          ->set('coupon_frame', HTML::iframe('services/get_coupon/'.$service->id, 'coupon_frame'))
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->bc['/'] = 'Главная';
        $this->template->bc['#'] = $service->name;
        $this->template->title = 'Автосервис '.$service->name.' '.$service->about;
        $this->template->meta_description = strip_tags($service->about);
        $this->add_js('http://api-maps.yandex.ru/1.1/index.xml?key='.$this->settings['YMaps_key'].'&onerror=map_alert');
        $this->add_js('assets/js/maps_detail.js');
        $this->add_js('assets/share42/share42.js');

        $this->template->content = $this->view;
    }
    /**
     * Поиск по марке автомобиля services/search/car_x
     * @return void
     */
    public function action_search_by_car()
    {
        $car_id = $this->request->param('car');
        $city_id = $this->request->param('city');
        $district_id = $this->request->query('district');
        $metro_id = $this->request->query('metro');

        $this->_selected_city_ids = Geography::set_selected_city_id($this->_geo_params['cities'], $city_id);

        if (!array_key_exists($city_id, $this->_geo_params['cities']['total']))
            $this->request->redirect('filter/auto/city_'.$this->_selected_city_ids['has_cars']);



        $params['city'] = array(
            'field' => 'city_id',
            'op'    => '=',
            'value' => $city_id
        );
        $current_district = Arr::get($this->_geo_params['districts'], $district_id);
        $current_metro_station = Arr::get($this->_geo_params['metro_stations'], $metro_id);

        if ($current_district AND $current_district['city_id'] == $city_id AND !empty($current_district['cars']) AND in_array($car_id, $current_district['cars']))
        {
            $params['district'] = array(
                'field' => 'district_id',
                'op'    => '=',
                'value' => $district_id
            );
        }
        else
        {
            $district_id = 0;
        }
        if ($current_metro_station AND $current_metro_station['city_id'] == $city_id OR ($current_district AND $current_metro_station['district_id'] == $district_id) AND !empty($current_metro_station['cars']) AND in_array($car_id, $current_metro_station['cars']))
        {
            $params['metro'] = array(
                'field' => 'metro_id',
                'op'    => '=',
                'value' => $metro_id
            );
        }
        else
        {
            $metro_id = 0;
        }


        $items['cars'] = array(
            'name' => 'cars',
            'ids' => array($car_id)
        );

        $url = 'services/search/car_'.$car_id.'/city_'.$params['city']['value'];
        $district = ORM::factory('district')->set_has_items($items)->get_districts($this->_geo_params, $city_id);
        $metro = ORM::factory('metro')->set_has_items($items)->get_metro($this->_geo_params, $city_id, $district_id);
        $discount = ORM::factory('discount')->set_has_items($items)->set_params($params)->get_discounts();

        $car_brand = ORM::factory('car_brand')->set_params($params)->get_cars($car_id);
        $work = ORM::factory('work')->set_params($params)->get_works(TRUE, NULL, $items['cars']['ids']);


        $service = ORM::factory('service')->set_params($params)->set_has_items($items)->get_services_2();

        $service->reset(FALSE);
        $services_count = count($service->find_all());

        if ($services_count == 0)
        {
            Message::set(Message::ERROR, 'Неправильные параметры поиска, сервисы не найдены.');
            $this->request->redirect('filter/auto/city_'.$this->_selected_city_ids['has_cars']);
        }


        $current_car = $car_brand[$car_id];
        $content = $current_car['current_object']->content;
        $i18n_params = array(
            ':car_full_name' => $current_car['full_name'],
            ':car_name_ru' => $current_car['name_ru'],
            ':car_name' => $current_car['name'],
            ':dativus_city_name' => $this->_geo_params['cities']['total'][$this->_selected_city_ids['has_cars']]->dativus_name
        );
        $this->template->meta_keywords = __('search_carbrand_keywords_car_name_ru', $i18n_params);
        if (trim($current_car['name']))
            $this->template->meta_keywords .= __('search_carbrand_keywords_car_name', $i18n_params);
        $this->template->meta_description = __('search_carbrand_description', $i18n_params);
        $h1_title = __('search_carbrand_h1', $i18n_params);
        $this->template->title = __('search_carbrand_title', $i18n_params);
        $this->template->bc['filter/auto/city_'.$this->_selected_city_ids['has_cars']] = 'Подбор автосервиса по марке автомобиля';
        $this->template->bc['#'] = $h1_title;
        /* Default text */
        if ($params['city']['value'] != 1 OR !trim(strip_tags($content->text)))
            $content->text = __(Kohana::$config->load('default_search_content.search_by_car'), array(':full_name' => $current_car['full_name']));

        $this->view = View::factory('frontend/services/search')
                          ->set('city', $this->_geo_params['cities']['total'][$this->_selected_city_ids['has_cars']])
                          ->set('metro_stations', $metro)
                          ->set('districts', $district)
                          ->set('discounts', $discount)
                          ->set('cars', $car_brand)
                          ->set('works', $work)
                          ->set('services_count', $services_count)
                          ->set('services', $service)
                          ->set('type', 'auto')
                          ->set('work_id', NULL)
                          ->set('car_id', $car_id)
                          ->set('city_id', $city_id)
                          ->set('metro_id', $metro_id)
                          ->set('district_id', $district_id)
                          ->set('url', $url)
                          ->set('content', $content)
                          ->set('h1_title', $h1_title);
        $this->template->content = $this->view;
    }
    /**
     * Поиск по услуге services/search/work_:id
     * @return void
     */
    public function action_search_by_work()
    {
        $work_id = $this->request->param('work');
        $city_id = $this->request->param('city');
        $district_id = $this->request->query('district');
        $metro_id = $this->request->query('metro');
        $this->_selected_city_ids = Geography::set_selected_city_id($this->_geo_params['cities'], $city_id);
        
        if (!array_key_exists($city_id, $this->_geo_params['cities']['total']))
            $this->request->redirect('filter/auto/city_'.$this->_selected_city_ids['has_works']);

        $params['city'] = array(
            'field' => 'city_id',
            'op'    => '=',
            'value' => $city_id
        );

        $current_district = Arr::get($this->_geo_params['districts'], $district_id);
        $current_metro_station = Arr::get($this->_geo_params['metro_stations'], $metro_id);

        if ($current_district AND $current_district['city_id'] == $city_id AND !empty($current_district['works']) AND in_array($work_id, $current_district['works']))
        {
            $params['district'] = array(
                'field' => 'district_id',
                'op'    => '=',
                'value' => $district_id
            );
        }
        else
        {
            $district_id = 0;
        }
        if ($current_metro_station AND $current_metro_station['city_id'] == $city_id OR ($current_district AND $current_metro_station['district_id'] == $district_id) AND !empty($current_metro_station['works']) AND in_array($work_id, $current_metro_station['works']))
        {
            $params['metro'] = array(
                'field' => 'metro_id',
                'op'    => '=',
                'value' => $metro_id
            );
        }
        else
        {
            $metro_id = 0;
        }

        $url = 'services/search/work_'.$work_id.'/city_'.$city_id;

        $items['works'] = array(
            'name' => 'works',
            'ids' => array($work_id)
        );



        $district = ORM::factory('district')->set_has_items($items)->get_districts($this->_geo_params, $city_id);
        $metro = ORM::factory('metro')->set_has_items($items)->get_metro($this->_geo_params, $city_id, $district_id);
        $discount = ORM::factory('discount')->set_params($params)->get_discounts();

        $car_brand = ORM::factory('car_brand')->set_params($params)->get_cars(NULL, $items['works']['ids']);
        $work = ORM::factory('work')->set_params($params)->get_works(TRUE);


        $service = ORM::factory('service')->set_params($params)->set_has_items($items)->get_services_2();

        $service->reset(FALSE);
        $services_count = count($service->find_all());

        if ($services_count == 0)
        {
            Message::set(Message::ERROR, 'Неправильные параметры поиска, сервисы не найдены.');
            $this->request->redirect('filter/work/'.$this->_selected_city_ids['has_works']);
        }


        $current_work = ORM::factory('work')->extract_current_work_from_categories($work, $work_id);
        $content = $current_work['current_object']->content;
        $i18n_params = array(
            ':work_name' => $current_work['name'],
            ':dativus_city_name' => $this->_geo_params['cities']['total'][$city_id]->dativus_name
        );
        $this->template->meta_keywords = __('search_work_keywords', $i18n_params);
        $this->template->meta_description = __('search_work_description', $i18n_params);
        $h1_title = __('search_work_h1', $i18n_params);
        $this->template->title = __('search_work_title', $i18n_params);
        $this->template->bc['filter/work/'.$this->_selected_city_ids['has_works']] = 'Подбор автосервиса по предоставляемым услугам';
        $this->template->bc['#'] = Text::limit_chars($h1_title, 75);
        /* Default text */
        if ($params['city']['value'] != 1 OR !trim(strip_tags($content->text)))
            $content->text = __(Kohana::$config->load('default_search_content.search_by_work'), array(':name' => $current_work['name']));

        $this->view = View::factory('frontend/services/search')
                          ->set('city', $this->_geo_params['cities']['total'][$city_id])
                          ->set('metro_stations', $metro)
                          ->set('districts', $district)
                          ->set('discounts', $discount)
                          ->set('cars', $car_brand)
                          ->set('works', $work)
                          ->set('services', $service)
                          ->set('type', 'work')
                          ->set('work_id', $work_id)
                          ->set('car_id', NULL)
                          ->set('city_id', $city_id)
                          ->set('metro_id', $metro_id)
                          ->set('district_id', $district_id)
                          ->set('url', $url)
                          ->set('services_count', $services_count)
                          ->set('content', $content)
                          ->set('h1_title', $h1_title);
        $this->template->content = $this->view;
    }
    /**
     * Поиск по метро по адресу services/search/metro_x
     * @return void
     */
    function action_search_by_metro()
    {
        if (!array_key_exists($this->request->param('metro'), $this->_geo_params['metro_stations']))
            $this->request->redirect('filter/metro/'.$this->_selected_city_ids['has_metro']);

        $params = array(
            'metro' => array(
                'field' => 'metro_id',
                'op'    => '=',
                'value' => $this->request->param('metro')
            )
        );

        $discount = ORM::factory('discount')->set_params($params)->get_discounts();
        $car_brand = ORM::factory('car_brand')->set_params($params)->get_cars();
        $work = ORM::factory('work')->set_params($params)->get_works(TRUE);

        $service = ORM::factory('service')->set_params($params)->get_services_2();
        $service->reset(FALSE);
        $services_count = $service->count_all();

        if ($services_count == 0)
        {
            Message::set(Message::ERROR, 'Неправильные параметры поиска, сервисы не найдены.');
            $this->request->redirect('filter/metro');
        }

        $items_on_page = Kohana::$config->load('settings.pagination.items_on_page');
        $pages_count = ceil($services_count / $items_on_page);
        $service->limit($items_on_page);

        $content = $this->_geo_params['metro_stations'][$params['metro']['value']]['obj']->content;
        $this->template->title = 'Автосервисы у метро '.$this->_geo_params['metro_stations'][$params['metro']['value']]['obj']->name;
        $this->template->meta_description = 'Поиск автосервиса рядом с метро '.$this->_geo_params['metro_stations'][$params['metro']['value']]['obj']->name;
        $this->template->meta_keywords = 'автосервисы метро '.$this->_geo_params['metro_stations'][$params['metro']['value']]['obj']->name.', автосервисы '.$this->_geo_params['metro_stations'][$params['metro']['value']]['obj']->city->genitive_name.' метро '.$this->_geo_params['metro_stations'][$params['metro']['value']]['obj']->name;
        $h1 = 'Автосервисы рядом с метро '.$this->_geo_params['metro_stations'][$params['metro']['value']]['obj']->name;
        if (!trim(strip_tags($content->text)))
            $content->text = __(Kohana::$config->load('default_search_content.search_by_metro'), array(':name' => $this->_geo_params['metro_stations'][$params['metro']['value']]['obj']->name));

        $this->view = View::factory('frontend/services/search_by_metro')
                          ->set('discounts', $discount)
                          ->set('cars', $car_brand)
                          ->set('works', $work)
                          ->set('services', $service)
                          ->set('metro_id', $params['metro']['value'])
                          ->set('h1', $h1)
                          ->set('content', $content)
                          ->set('services_count', $services_count)
                          ->set('pages_count', $pages_count);


        $this->template->bc['filter/metro/city_'.$this->_selected_city_ids['has_metro']] = 'Подбор автосервиса по станции метро';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Поиск по округу по адресу services/search/district_x
     * @return void
     */
    public function action_search_by_district()
    {
        $district_id = $this->request->param('district');

        if (!array_key_exists($district_id, $this->_geo_params['districts']))
            $this->request->redirect('filter/district/city_'.$this->_selected_city_ids['has_district']);

        $params = array(
            'district' => array(
                'field' => 'district_id',
                'op'    => '=',
                'value' => $district_id
            )
        );

        $discount = ORM::factory('discount')->set_params($params)->get_discounts();
        $car_brand = ORM::factory('car_brand')->set_params($params)->get_cars();
        $work = ORM::factory('work')->set_params($params)->get_works(TRUE);

        $service = ORM::factory('service')->set_params($params)->get_services_2();
        $service->reset(FALSE);
        $services_count = $service->count_all();

        if ($services_count == 0)
        {
            Message::set(Message::ERROR, 'Неправильные параметры поиска, сервисы не найдены.');
            $this->request->redirect('filter/district');
        }

        $items_on_page = Kohana::$config->load('settings.pagination.items_on_page');
        $pages_count = ceil($services_count / $items_on_page);
        $service->limit($items_on_page);

        $current_district = $this->_geo_params['districts'][$district_id]['obj'];

        $content = $current_district->content;

        if (trim($content->district->abbreviation))
        {
            $this->template->meta_description = 'Автосервисы '.$current_district->abbreviation.' ('.$current_district->full_name.')';
            $this->template->meta_keywords = 'автосервисы '.$current_district->abbreviation;
            $this->template->title = 'Автосервисы '.$current_district->abbreviation.' ('.$current_district->full_name.')';
            $h1 = 'Автосервисы '.$current_district->abbreviation.' ('.$current_district->full_name.')';
        }
        else
        {
            $this->template->meta_description = 'Автосервисы &mdash; '.$current_district->full_name;
            $this->template->meta_keywords = 'автосервисы &mdash; '.$current_district->full_name;
            $this->template->title = 'Автосервисы &mdash; '.$current_district->full_name;
            $h1 = 'Автосервисы &mdash; '.$current_district->full_name;
        }

        if (!trim(strip_tags($content->text)))
            $content->text = __('search_district_default_content', array(':name' => $current_district->name));

        $this->view = View::factory('frontend/services/search_by_district')
                          ->set('discounts', $discount)
                          ->set('cars', $car_brand)
                          ->set('works', $work)
                          ->set('services', $service)
                          ->set('district_id', $district_id)
                          ->set('h1', $h1)
                          ->set('content', $content)
                          ->set('services_count', $services_count)
                          ->set('pages_count', $pages_count);
        $this->template->bc['filter/district/city_'.$this->_selected_city_ids['has_district']] = 'Подбор автосервиса по округу';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    private function get_work($categories, $work_id)
    {
        foreach ($categories as $works)
        {
            foreach ($works as $id => $name)
            {
                if ($id == $work_id)
                    return $name;
            }
        }
        return FALSE;
    }
}