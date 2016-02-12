<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Ajax extends Controller
{
    function action_get_ymaps_companies()
    {
        if ($this->request->is_ajax())
        {
            $result = array();
            $service = ORM::factory('service');
            foreach ($service->find_all() as $s)
            {
                $result[] = array(
                    'name' => $s->orgtype->name." &laquo;".$s->name."&raquo;",
                    'site' => $s->site,
                    'description' => $s->city->name.", ".$s->address.' '.HTML::anchor($s->site, $s->site).'<br />'.HTML::anchor('services/'.$s->id, 'Подробнее'),
                    'lat' => $s->ymap_lat,
                    'lng' => $s->ymap_lng
                );
            }
            echo json_encode($result);
        }
    }
    public function action_test()
    {
        if ($this->request->is_initial())
        {
            $this->response->body('ok');
        }

    }

    // Сортировка тегов по округу
    public function action_filter_district_sort()
    {
        if ($this->request->is_ajax())
        {
            if ($_POST)
            {
                $result = array();
                $errors = array();
                $debug = array();
                $config = array(
                    'filter_auto' => array(
                        'items_key'        => 'cars',
                        'cities_has_index' => 'has_cars',
                        'model'            => 'car_brand',
                        'view_items_name'  => 'car_brands'
                    ),
                    'filter_work' => array(
                        'items_key'        => 'works',
                        'cities_has_index' => 'has_works',
                        'model'            => 'work',
                        'view_items_name'  => 'category'
                    )
                );

                $params = Arr::extract($_POST, array('type', 'city_id', 'district_id'));
                if (!array_key_exists($params['type'], $config))
                    $errors[] = 'Неправильный тип фильтра';



                $geo_params = Geography::get_geography_params();

                $cities_index = Arr::get($config[$params['type']], 'cities_has_index');

                if (!array_key_exists($params['city_id'], $geo_params['cities'][$cities_index]))
                    $errors[] = 'Такой город не найден';

                $has_items_key = Arr::get($config[$params['type']], 'items_key');
                $model_name = Arr::get($config[$params['type']], 'model');
                $view_items_name = Arr::get($config[$params['type']], 'view_items_name');
                $district = Arr::get($geo_params['districts'], $params['district_id']);


                $tags_params = array(
                    array(
                        'field' => 'city_id',
                        'op'    => '=',
                        'value' => $params['city_id']
                    )
                );
                $url = '/city_'.$params['city_id'];
                $url_query = array();
                if ($params['district_id'] != 0)
                {
                    if (!$district OR $district['city_id'] != $params['city_id'] OR empty($district[$has_items_key]))
                    {
                        $errors[] = 'Не правильный округ';
                    }
                    else
                    {
                        $tags_params[] = array(
                            'field' => 'district_id',
                            'op'    => '=',
                            'value' => $params['district_id']
                        );
                        $url_query['district'] = $params['district_id'];
                    }

                }


                if (empty($errors))
                {

                    $items[$has_items_key] = array(
                        'ids' => NULL
                    );

                    $metro = ORM::factory('metro')->set_has_items($items)->get_metro($geo_params, $params['city_id'], $params['district_id']);

                    $getter_method = 'get_'.$has_items_key;

                    $tag = ORM::factory($model_name)->set_params($tags_params);

                    if ($model_name == 'work')
                        $tag = $tag->$getter_method(TRUE);
                    else
                        $tag = $tag->$getter_method();

                    $metro_select_view = View::factory('frontend/navigation/metro_stations_form_select')
                                             ->set('metro_stations', $metro)
                                             ->set('metro_id', NULL)
                                             ->render();

                    $items_tags_view = View::factory('frontend/filter/'.$has_items_key.'_tags')
                                            ->set($view_items_name, $tag)
                                            ->set('url', $url.URL::query($url_query))
                                            ->render();
                }


                if (!empty($errors))
                    foreach ($errors as $error)
                        $result['errors'][] = $error;
                else
                    $result = array(
                        'metro_select_options' => $metro_select_view,
                        'metro_stations_count' => 0,
                        'items_tags' => $items_tags_view
                    );

                echo json_encode($result);
            }
        }
    }
    // Сортировка тегов по станции метро
    public function action_filter_metro_sort()
    {
        if ($this->request->is_ajax())
        {
            if ($_POST)
            {
                $result = array();
                $config = array(
                    'filter_auto' => array(
                        'items_key'        => 'cars',
                        'cities_has_index' => 'has_cars',
                        'model'            => 'car_brand',
                        'view_items_name'  => 'car_brands'
                    ),
                    'filter_work' => array(
                        'items_key'        => 'works',
                        'cities_has_index' => 'has_works',
                        'model'            => 'work',
                        'view_items_name'  => 'category'
                    )
                );

                $params = Arr::extract($_POST, array('type', 'city_id', 'district_id', 'metro_id'));
                if (!array_key_exists($params['type'], $config))
                    $errors[] = 'Неправильный тип фильтра';

                $geo_params = Geography::get_geography_params();

                $cities_index = Arr::get($config[$params['type']], 'cities_has_index');

                if (!array_key_exists($params['city_id'], $geo_params['cities'][$cities_index]))
                    $errors[] = 'Такой город не найден';

                $has_items_key = Arr::get($config[$params['type']], 'items_key');
                $model_name = Arr::get($config[$params['type']], 'model');
                $view_items_name = Arr::get($config[$params['type']], 'view_items_name');
                $district = Arr::get($geo_params['districts'], $params['district_id']);
                $metro = Arr::get($geo_params['metro_stations'], $params['metro_id']);
                $getter_method = 'get_'.$has_items_key;
                $tags_params[] =array(
                    'field' => 'city_id',
                    'op'    => '=',
                    'value' => $params['city_id']
                );

                $url = '/city_'.$params['city_id'];
                $url_query = array();
                if ($params['district_id'] != 0)
                {
                    if (!$district OR $district['city_id'] != $params['city_id'] OR empty($district[$has_items_key]))
                    {
                        $errors[] = 'Не правильный округ';
                    }
                    else
                    {
                        $tags_params[] = array(
                            'field' => 'district_id',
                            'op'    => '=',
                            'value' => $params['district_id']
                        );
                        $url_query['district'] = $params['district_id'];
                    }
                }
                if ($params['metro_id'] != 0)
                {
                    if (!$metro OR $metro['city_id'] != $params['city_id'] OR ($params['district_id'] != 0 AND $metro['district_id'] != $params['district_id']) OR empty($metro[$has_items_key]))
                    {
                        $errors[] = 'Не правильная станция метро';
                    }
                    else
                    {
                        $tags_params[] = array(
                            'field' => 'metro_id',
                            'op'    => '=',
                            'value' => $params['metro_id']
                        );
                        $url_query['metro'] = $params['metro_id'];
                    }
                }

                $tag = ORM::factory($model_name)->set_params($tags_params);
                if ($model_name == 'work')
                    $tag = $tag->$getter_method(TRUE);
                else
                    $tag = $tag->$getter_method();

                $items_tags_view = View::factory('frontend/filter/'.$has_items_key.'_tags')
                                        ->set($view_items_name, $tag)
                                        ->set('url', $url.URL::query($url_query))
                                        ->render();

                if (!empty($errors))
                    foreach ($errors as $error)
                        $result['errors'][] = $error;
                else
                    $result = array(
                        'items_tags' => $items_tags_view
                    );

                echo json_encode($result);
            }
        }
    }
    // Сортировка автосервисов по округу
    public function action_search_district_sort()
    {
        if ($this->request->is_ajax())
        {
            if ($_POST)
            {
                $result = array();
                $errors = array();

                $params = Arr::extract($_POST, array('type', 'city_id', 'district_id', 'item_id'));
                $config = array(
                    'search_by_auto' => array(
                        'items_key' => 'cars',
                        'cities_has_index' => 'has_cars'
                    ),
                    'search_by_work' => array(
                        'items_key' => 'works',
                        'cities_has_index' => 'has_works'
                    )
                );

                if (!array_key_exists($params['type'], $config))
                    $errors[] = 'Ошибка поиска';

                $geo_params = Geography::get_geography_params();
                $has_items_key = $config[$params['type']]['items_key'];
                $cities_has_index = $config[$params['type']]['cities_has_index'];
                $district = Arr::get($geo_params['districts'], $params['district_id']);

                if (!array_key_exists($params['city_id'], $geo_params['cities'][$cities_has_index]))
                    $errors[] = 'Неправильный город';
                else
                    $services_params[] = array(
                        'field' => 'city_id',
                        'op'    => '=',
                        'value' => $params['city_id']
                    );

                if ($params['district_id'] != 0)
                {
                    if (!$district OR $district['city_id'] != $params['city_id'] OR empty($district[$has_items_key]) OR !in_array($params['item_id'], $district[$has_items_key]))
                    {
                        $errors[] = 'Не правильный округ';
                    }
                    else
                    {
                        $services_params[] = array(
                            'field' => 'district_id',
                            'op'    => '=',
                            'value' => $params['district_id']
                        );
                    }
                }


                if (empty($errors))
                {
                    $services_items[$config[$params['type']]['items_key']] = array(
                        'ids' => array($params['item_id'])
                    );
                    $metro = ORM::factory('metro')->set_has_items($services_items)->get_metro($geo_params, $params['city_id'], $params['district_id']);
                    $discount = ORM::factory('discount')->set_has_items($services_items)->set_params($services_params)->get_discounts();
                    $service = ORM::factory('service')->set_has_items($services_items)->set_params($services_params)->get_services_2();
                    $service->reset(FALSE);
                    $services_count = count($service->find_all());
                    $services_view = View::factory('frontend/services/services_list')
                                         ->set('services', $service)
                                         ->render();
                    $metro_select_view = View::factory('frontend/navigation/metro_stations_form_select')
                                      ->set('metro_stations', $metro)
                                      ->set('metro_id', NULL)
                                      ->render();
                    $discounts_view = View::factory('frontend/navigation/discounts')
                                          ->set('discounts', $discount)
                                          ->set('selected_discounts', NULL)
                                          ->render();
                    $result = array(
                        'metro_select_options' => $metro_select_view,
                        'metro_stations_count' => 0,
                        'services' => $services_view,
                        'services_count' => $services_count,
                        'discounts' => $discounts_view,
                        'district_id' => $params['district_id'],
                        'params' => $services_params
                    );
                    if ($params['type'] == 'search_by_auto')
                    {
                        $work = ORM::factory('work')->set_params($services_params)->get_works(TRUE, NULL, $services_items['cars']['ids']);
                        $works_view = View::factory('frontend/services/works_list')
                                          ->set('works', $work)
                                          ->render();
                        $result['works'] = $works_view;
                    }
                    elseif ($params['type'] == 'search_by_work')
                    {
                        $car_brand = ORM::factory('car_brand')->set_params($services_params)->get_cars(NULL, $services_items['works']['ids']);
                        $car_brands_view = View::factory('frontend/services/cars_list')
                                               ->set('cars', $car_brand)
                                               ->render();
                        $result['cars'] = $car_brands_view;
                    }

                }
                else
                {
                    foreach ($errors as $error)
                        $result['errors'][] = $error;
                }

                echo json_encode($result);
            }
        }
    }
    // Сортировка автосервисов по станции метро
    public function action_search_metro_sort()
    {
        if ($this->request->is_ajax())
        {
            if ($_POST)
            {
                $result = array();
                $errors = array();

                $params = Arr::extract($_POST, array('type', 'city_id', 'district_id', 'metro_id', 'item_id'));
                $config = array(
                    'search_by_auto' => array(
                        'items_key' => 'cars',
                        'cities_has_index' => 'has_cars'
                    ),
                    'search_by_work' => array(
                        'items_key' => 'works',
                        'cities_has_index' => 'has_works'
                    )
                );

                if (!array_key_exists($params['type'], $config))
                    $errors[] = 'Ошибка поиска';

                $geo_params = Geography::get_geography_params();
                $has_items_key = $config[$params['type']]['items_key'];
                $cities_has_index = $config[$params['type']]['cities_has_index'];
                $district = Arr::get($geo_params['districts'], $params['district_id']);
                $metro = Arr::get($geo_params['metro_stations'], $params['metro_id']);

                if (!array_key_exists($params['city_id'], $geo_params['cities'][$cities_has_index]))
                    $errors[] = 'Неправильный город';
                else
                    $services_params[] = array(
                        'field' => 'city_id',
                        'op'    => '=',
                        'value' => $params['city_id']
                    );

                if ($params['district_id'] != 0)
                {
                    if (!$district OR $district['city_id'] != $params['city_id'] OR empty($district[$has_items_key]) OR !in_array($params['item_id'], $district[$has_items_key]))
                    {
                        $errors[] = 'Не правильный округ';
                    }
                    else
                    {
                        $services_params[] = array(
                            'field' => 'district_id',
                            'op'    => '=',
                            'value' => $params['district_id']
                        );
                    }
                }
                if ($params['metro_id'] != 0)
                {
                    if (!$metro OR $metro['city_id'] != $params['city_id'] OR ($params['district_id'] != 0 AND $metro['district_id'] != $params['district_id']) OR empty($metro[$has_items_key]) OR !in_array($params['item_id'], $metro[$has_items_key]))
                    {
                        $errors[] = 'Не правильная станция метро';
                    }
                    else
                    {
                        $services_params[] = array(
                            'field' => 'metro_id',
                            'op'    => '=',
                            'value' => $params['metro_id']
                        );
                    }
                }


                if (empty($errors))
                {
                    $services_items[$config[$params['type']]['items_key']] = array(
                        'ids' => array($params['item_id'])
                    );
                    $discount = ORM::factory('discount')->set_has_items($services_items)->set_params($services_params)->get_discounts();
                    $service = ORM::factory('service')->set_has_items($services_items)->set_params($services_params)->get_services_2();
                    $service->reset(FALSE);
                    $services_count = count($service->find_all());
                    $services_view = View::factory('frontend/services/services_list')
                                         ->set('services', $service)
                                         ->render();
                    $discounts_view = View::factory('frontend/navigation/discounts')
                                          ->set('discounts', $discount)
                                          ->set('selected_discounts', NULL)
                                          ->render();
                    $result = array(
                        'services' => $services_view,
                        'services_count' => $services_count,
                        'discounts' => $discounts_view,
                        'district_id' => $params['district_id']
                    );
                    if ($params['type'] == 'search_by_auto')
                    {
                        $work = ORM::factory('work')->set_params($services_params)->get_works(TRUE, NULL, $services_items['cars']['ids']);
                        $works_view = View::factory('frontend/services/works_list')
                                          ->set('works', $work)
                                          ->render();
                        $result['works'] = $works_view;
                    }
                    elseif ($params['type'] == 'search_by_work')
                    {
                        $car_brand = ORM::factory('car_brand')->set_params($services_params)->get_cars(NULL, $services_items['works']['ids']);
                        $car_brands_view = View::factory('frontend/services/cars_list')
                                               ->set('cars', $car_brand)
                                               ->render();
                        $result['cars'] = $car_brands_view;
                    }
                }
                else
                {
                    foreach ($errors as $error)
                        $result['errors'][] = $error;
                }
                echo json_encode($result);
            }
        }
    }
    /**
     * Фильтр услуг и прочего
     * @return mixed
     */
    public function action_fast_filter()
    {
        $geo_params = Geography::get_geography_params();
        // Типы быстрого поиска на главной
        $types = array('has_cars', 'has_works', 'has_metro', 'has_district');

        if ($this->request->query('type') AND in_array($this->request->query('type'), $types))
        {
            Cookie::set('fast_filter_type', $this->request->query('type'));
            $filter_type = $this->request->query('type');
        }
        else
        {
            $filter_type = Cookie::get('fast_filter_type', 'has_cars');
            if (!in_array($filter_type, $types))
                $filter_type = 'has_cars';
        }

        if ($this->request->query('city') AND array_key_exists($this->request->query('city'), $geo_params['cities']['total']))
        {
            Cookie::set('filter_city', $this->request->query('city'));
            $city_id = $this->request->query('city');
        }
        else
        {
            $city_id = Cookie::get('filter_city');
        }

        $selected_city_ids = Geography::set_selected_city_id($geo_params['cities'], $city_id);

        $params = array(
            'city' => array(
                'field' => 'city_id',
                'op'    => '=',
                'value' => $selected_city_ids[$filter_type]
            )
        );

        $header_cities_view = View::factory('frontend/navigation/header_cities')
                                  ->set('cities', $geo_params['cities']['total'])
                                  ->set('current_city', $selected_city_ids['total'])
                                  ->render();


        if ($this->request->query('mode') == 'without_filter')
        {
            $result = array(
                'header_cities' => $header_cities_view,
                'auto_filter_city_id' => $selected_city_ids['has_cars']
            );
            echo json_encode($result);
            return;
        }

        $config = Kohana::$config->load('settings');





        $current_city = array(
            'name' => $geo_params['cities']['total'][$selected_city_ids[$filter_type]]->name,
            'genitive_name' => $geo_params['cities']['total'][$selected_city_ids[$filter_type]]->genitive_name,
        );

        $primary_cities = array_intersect_key($geo_params['cities']['total'], $geo_params['cities'][$filter_type]);
        $other_cities = NULL;
        // Получение городов, основных и "других"

        if (count($primary_cities) > $config['max_fast_filter_cities'])
        {
            $primary_cities = array_intersect_key($primary_cities, array_flip($config['fast_filter_cities']));
            if (!array_key_exists($selected_city_ids[$filter_type], $primary_cities))
            {
                array_pop($primary_cities);
                $primary_cities = array($selected_city_ids[$filter_type] => $geo_params['cities']['total'][$selected_city_ids[$filter_type]]) + $primary_cities;
            }
            $other_cities = array_diff_key(array_intersect_key($geo_params['cities']['total'], $geo_params['cities'][$filter_type]), $primary_cities);
        }


        $filter_type_view = View::factory('frontend/navigation/fast_filter_types')
                                ->set('types', $types)
                                ->set('current_type', $filter_type)
                                ->render();

        $cities_view = View::factory('frontend/navigation/fast_filter_cities')
                         ->set('cities', $primary_cities)
                         ->set('other_cities', $other_cities)
                         ->set('city_id', $selected_city_ids[$filter_type])
                         ->render();

        switch ($filter_type)
        {
            case 'has_cars':

                $car_brand = ORM::factory('car_brand')->set_params($params)->get_cars();
                if (count($car_brand) > $config['max_fast_filter_models'])
                {
                    $primary_cars = array_intersect_key($car_brand, array_flip($config['fast_filter_models']));
                    if (count($primary_cars) < $config['max_fast_filter_models'])
                    {
                        $primary_cars = $primary_cars + array_slice(array_diff_key($car_brand, $primary_cars), 0, $config['max_fast_filter_models'] - count($primary_cars), TRUE);
                    }
                    $car_brand = $primary_cars;
                }

                $items_view = View::factory('frontend/fast_filter/car_brands')
                                  ->set('city_id', $params['city']['value'])
                                  ->set('car_brands', $car_brand)
                                  ->render();

                break;
            case 'has_works':

                $work = ORM::factory('work')->set_params($params)->get_works();
                if (count($work) > $config['max_fast_filter_works'])
                {

                    $primary_works = array_intersect_key($work, array_flip($config['fast_filter_works']));

                    if (count($primary_works) < $config['max_fast_filter_works'])
                        $primary_works = $primary_works + array_slice(array_diff_key($work, $primary_works), 0, $config['max_fast_filter_works'] - count($primary_works), TRUE);
                    $work = $primary_works;
                }
                $items_view = View::factory('frontend/fast_filter/works')
                                  ->set('works', $work)
                                  ->set('city_id', $params['city']['value'])
                                  ->render();

                break;
            case 'has_metro':
                $metro = ORM::factory('metro')->get_metro($geo_params, $params['city']['value']);
                if (count($metro) > $config['max_fast_filter_metro'])
                {
                    $metro = array_slice($metro, 0, $config['max_fast_filter_metro'], TRUE);
                }
                $items_view = View::factory('frontend/fast_filter/metro')
                                  ->set('metro_stations', $metro)
                                  ->set('city_id', $params['city']['value'])
                                  ->render();

                break;
            case 'has_district':
                $district = ORM::factory('district')->get_districts($geo_params, $params['city']['value']);
                if (count($district) > $config['max_fast_filter_districts'])
                {
                    $district = array_slice($district, 0, $config['max_fast_filter_districts'], TRUE);
                }
                $items_view = View::factory('frontend/fast_filter/districts')
                                  ->set('districts', $district)
                                  ->set('city_id', $params['city']['value'])
                                  ->render();

                break;
        }
        $result = array(
            'filter_type'   => $filter_type_view,
            'cities'        => $cities_view,
            'items'         => $items_view,
            'header_cities' => $header_cities_view,
            'current_city' => $current_city,
            'auto_filter_url' => URL::site('filter/auto/city_'.$selected_city_ids['has_cars'])
        );

        if ($this->request->is_ajax())
        {
            echo json_encode($result);
        }
        else
        {
            $this->response->body($result['filter_type'].$result['cities'].$result['items']);
        }
    }
    function action_test_sort()
    {
        if ($this->request->is_ajax())
        {
            $pagination_view = View::factory('frontend/blocks/search/pagination')
                                   ->set('pages_count', 10)
                                   ->set('current', 1)
                                   ->set('css_class', 'test')
                                   ->render();
            $result['pagination'] = $pagination_view;
            echo json_encode($result);
        }

    }
    /**
     * Универсальный экшн получения сервисов
     * @return void
     */
    function action_get_services()
    {
        if ($this->request->is_ajax())
        {
            $page = Arr::get($_POST, 'page', 1);
            $cars = Arr::get($_POST, 'cars');
            $works = Arr::get($_POST, 'works');
            $params = array(
                array(
                    'field' => 'city_id',
                    'op'    => '=',
                    'value' => Arr::get($_POST, 'city_id')
                ),
                array(
                    'field' => 'metro_id',
                    'op'    => '=',
                    'value' => Arr::get($_POST, 'metro_id')
                ),
                array(
                    'field' => 'district_id',
                    'op'    => '=',
                    'value' => Arr::get($_POST, 'district_id')
                ),
                'discount' => array(
                    'field' => 'discount_id',
                    'op'    => 'in',
                    'value' => Arr::get($_POST, 'discounts')
                )
            );
            
            $items = array();

            if ($cars AND is_array($cars))
                $items['cars'] = array(
                    'ids'  => $cars
                );
            
            if ($works AND is_array($works))
                $items['works'] = array(
                    'ids'  => $works
                );

            $discount = ORM::factory('discount')->set_params($params)->set_has_items($items)->get_discounts();
            $service = ORM::factory('service')->set_params($params)->set_has_items($items)->get_services_2();
            $service->reset(FALSE);

            $services_count = count($service->find_all());
            $items_on_page = Kohana::$config->load('settings.pagination.items_on_page');
            $pages_count = ceil($services_count / $items_on_page);
            if ($page >= 1 AND $pages_count >= $page)
            {
                $offset = ($page - 1) * $items_on_page;
            }
            else
            {
                $offset = 0;
                $page = 1;
            }
            $service->offset($offset)->limit($items_on_page);

            $services_view = View::factory('frontend/services/services_list')
                                 ->set('services', $service)
                                 ->render();
            $pagination_view = View::factory('frontend/blocks/search/pagination')
                                   ->set('pages_count', $pages_count)
                                   ->set('current', $page)
                                   ->set('css_class', '1')
                                   ->render();
            $discounts_view = View::factory('frontend/navigation/discounts')
                                  ->set('discounts', $discount)
                                  ->set('selected_discounts', $params['discount']['value'])
                                  ->render();
            $result = array(
                'services'   => $services_view,
                'pagination' => $pagination_view,
                'debug_html'      => View::factory('profiler/stats')->render().$services_count,
                'discounts' => $discounts_view,
                's_count' => $services_count
            );
            echo json_encode($result);
        }
    }
    /**
     * Получение округов и станций метро от города при регистрации
     * @return
     */
    function action_get_metro_and_district()
    {
        if ($this->request->is_ajax())
        {
            $result = array(
                'error' => '',
                'data' => array(
                    'metro_form' => '',
                    'district_form' => ''
                )
            );

            $city_name = Arr::get($_POST, 'city_name');

            $city = ORM::factory('city')->where('name', '=', $city_name)->find();

            if (!$city->loaded())
            {
                $result['error'] = 'Нет такого города';
                echo json_encode($result);
                return;
            }
            if (count($city->districts->find_all()) > 0)
            {
                $view = View::factory('frontend/blocks/district_select')
                            ->set('districts', $city->districts);
                $result['data']['district_form'] = $view->render();
            }
            if (count($city->metro->find_all()) > 0)
            {
                $view = View::factory('frontend/blocks/metro_select')
                            ->set('metro', $city->metro);
                $result['data']['metro_form'] = $view->render();
            }
            echo json_encode($result);
        }
    }


   /**
    * Генерация карты YMAPs для сервиса
    * @return void
    */
    function action_get_location()
    {
        $service = ORM::factory('service', $_GET['service']);

        $arr[0] = array("addr" => $service->city->name.", ".$service->address, "name" => $service->orgtype->name." &laquo;".$service->name."&raquo;", "site" => $service->site);

        //Выводим JSON на страницу
        echo json_encode($arr);
    }

    public function action_get_addrs(){

        if ($_GET['city'] == 'moscow') {
            $city_id = 1;
        }
        elseif($_GET['city'] == 'novosib'){
            $city_id = 3;
        }
        elseif($_GET['city'] == 'novgorod'){
            $city_id = 4;
        }
        elseif($_GET['city'] == 'ekaterinburg'){
            $city_id = 5;
        }
        elseif($_GET['city'] == 'samara'){
            $city_id = 6;
        }
        elseif($_GET['city'] == 'omsk'){
            $city_id = 7;
        }
        elseif($_GET['city'] == 'kazan'){
            $city_id = 8;
        }
        elseif($_GET['city'] == 'chelabinsk'){
            $city_id = 9;
        }
        elseif($_GET['city'] == 'rostov_na_donu'){
            $city_id = 10;
        }
        elseif($_GET['city'] == 'ufa'){
            $city_id = 11;
        }
        elseif($_GET['city'] == 'volgograd'){
            $city_id = 12;
        }
        elseif($_GET['city'] == 'siktivkar'){
            $city_id = 13;
        }
        elseif($_GET['city'] == 'krasnojarsk'){
            $city_id = 14;
        }

         else {
            $city_id = 2;
        }

        $services = ORM::factory('service')->find_all();

        if (count($services)) {
            $x = 0;
            foreach ($services as $service) {
                //$arr[$x] = array("addr" => $service->city->name.", ".$service->address, "name" => $service->orgtype->name." &laquo;".$service->name."&raquo;", "site" => $service->site);
                $arr[] = array(
                    'addr' => $service->city->name.", ".$service->address.' '.HTML::anchor($service->site, $service->site).'<br />'.HTML::anchor('services/'.$service->id, 'Подробнее'),
                    'name' => $service->orgtype->name.' &laquo;'.$service->name.'&raquo;'
                );
                $x++;
            }

            //Выводим JSON на страницу
            echo json_encode($arr);
        }
    }

    function action_upload_image()
    {
        $this->_valid = Validation::factory($_FILES)
                ->rule('upload', 'Upload::not_empty')
                ->rule('upload', 'Upload::type', array(':value', array('jpg', 'bmp', 'gif', 'png', 'jpeg')));

        if ($this->_valid->check())
        {
            $file_name = md5(date('YmdHis')).'.'.$this->my_exts($_FILES['upload']['type']);
            Upload::save($_FILES['upload'], $file_name, 'upload/attachments');
            echo View::factory('frontend/blocks/other/ckeditor_script')
                     ->set('error', FALSE)
                     ->set('img_url',  URL::base().'upload/attachments/'.$file_name)
                     ->render();
        }
        else
        {
            echo View::factory('frontend/blocks/other/ckeditor_script')
                     ->set('error', TRUE)
                     ->set('img_url', FALSE)
                     ->render();
        }
    }
    function action_upload_image_2()
    {
        $this->_valid = Validation::factory($_FILES)
                ->rule('file', 'Upload::not_empty')
                ->rule('file', 'Upload::type', array(':value', array('jpg', 'bmp', 'gif', 'png', 'jpeg')));

        if ($this->_valid->check())
        {
            $file_name = md5(date('YmdHis')).'.'.$this->my_exts($_FILES['file']['type']);
            Upload::save($_FILES['file'], $file_name, 'upload/attachments');
            echo json_encode(array('filelink' => '/upload/attachments/'.$file_name));
        }
    }
    private function my_exts($mime)
    {
        switch ($mime)
        {
            case "image/bmp":
                $ext = 'bmp';
            break;

            case "image/x-windows-bmp":
                $ext = 'bmp';
            break;

            case "image/gif":
                $ext = "gif";
            break;

            case "image/jpeg":
                $ext = "jpg";
            break;

            case "image/png":
                $ext = "png";
            break;
        }
        return $ext;
    }
    public function action_select_region()
    {
        if ($this->request->is_ajax())
        {
            $city = ORM::factory('city');
            $current_city = $_POST['city'];
            $cities = $city->get_cities_for_ymaps($current_city);
            $genitive_name = DB::select('genitive_name')->from('cities')->where('ymap_name', '=', $current_city)->execute()->get('genitive_name');
            $view = View::factory('frontend/blocks/select_region_ymaps')
                        ->set('cities', $cities)
                        ->render();
            $result['cities_html'] = $view;
            $result['genitive_name'] = $genitive_name;
            Session::instance()->set('current_city', $current_city);
            echo json_encode($result);
        }
    }
    /**
     * Выбор города на странице добавления отзыва
     * @return void
     */
    public function action_add_review_services()
    {
        if ($this->request->is_ajax())
        {
            $city_id = $this->request->param('id', 0);
            if ($city_id == 0)
            {
                $services = ORM::factory('service')->get_services_as_array();
            }
            else
            {
                $services = ORM::factory('service')->get_services_as_array(array('city_id' => $city_id));
            }
            $view = View::factory('frontend/blocks/add_review_services')
                        ->set('services', $services)
                        ->render();
            $result['services_select_html'] =  $view;
            echo json_encode($result);
        }
    }
    /**
     * Выбор марки авто на странице добавления запроса
     * @return void
     */
    public function action_add_qa_car_models()
    {
        if ($this->request->is_ajax())
        {
            $car_brand_id = $this->request->param('id', NULL);
            $car_models  = ORM::factory('car_model')->get_models($car_brand_id);
            $view = View::factory('frontend/blocks/add_qa_models')
                        ->set('models', $car_models)
                        ->render();
            echo json_encode(array('models_select_html' => $view));
        }
    }
    public function action_get_discount_coupon()
    {
        if ($this->request->is_ajax())
        {
            $result = array(
                'error' => '',
                'coupon_text' => ''
            );
            $service = ORM::factory('service', $this->request->param('id', NULL));
            if (!$service->loaded())
            {
                $result['error'] = 'Нет такого сервиса';

            }
            if (!$service->discount->percent)
            {
                $result['error'] = 'Компания не имеет скидок';
            }
            $result['coupon_text'] = (mb_strlen(trim($service->coupon_text)) == 0)
                                   ? __('coupon_standart_text', array(':percent' => $service->discount->percent, ':service' => $service->name))
                                   : $service->coupon_text;
            $result['coupon_text'] .= '<div class="to_print_div">'.$result['coupon_text'].'</div>';
            echo json_encode($result);
        }
    }
}