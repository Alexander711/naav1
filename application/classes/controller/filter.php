<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Filter extends Controller_Frontend
{
    public function before()
    {
        parent::before();
        $this->add_js('assets/js/jquery.columnizer.min.js');
    }

    function action_index()
    {
        $this->request->redirect('filter/auto/city_').$this->_selected_city_ids['has_cars'];
    }
    /**
     * Теги марок автомобилей
     * @return void
     */
    public function action_auto()
    {
        if ($this->request->param('city', NULL))
        {
            $this->_selected_city_ids = Geography::set_selected_city_id($this->_geo_params['cities'], $this->request->param('city'));
            // Указываем выбранные города для шаблона
            $this->template->selected_city_ids = array_intersect_key($this->_selected_city_ids, $this->template->selected_city_ids);
            Cookie::set('filter_city', $this->_selected_city_ids['total']);
        }
        else
        {
            $this->request->redirect('filter/auto/city_'.$this->_selected_city_ids['has_cars']);
        }


        $items['cars'] = array(
            'ids'  => NULL
        );
        // Получаем округи и станции метро
        $district = ORM::factory('district')->set_has_items($items)->get_districts($this->_geo_params, $this->_selected_city_ids['has_cars']);
        $metro = ORM::factory('metro')->set_has_items($items)->get_metro($this->_geo_params, $this->_selected_city_ids['has_cars']);

        $params = array(
            'city' => array(
                'field' => 'city_id',
                'op'    => '=',
                'value' => $this->_selected_city_ids['has_cars']
            )
        );
        $car_brand = ORM::factory('car_brand')->set_params($params)->get_cars();
        if (empty($car_brand))
        {
            Message::set(Message::ERROR, 'Неправильные параметры для фильтра');
            $this->request->redirect('filter/auto/city_'.$this->_selected_city_ids['has_cars']);
        }


        $current_city = $this->_geo_params['cities']['total'][$this->_selected_city_ids['has_cars']];
        $content = $current_city->contents->where('type', '=', 'auto')->find();
        if (!trim($content->text))
            $content->text = __('filter_auto_default_content', array(':city_name' => $content->city->name));;

        if ($this->request->query('selected_city') AND $this->request->query('selected_city') != $this->_selected_city_ids['has_works'])
            Message::set(Message::NOTICE, __('filter_auto_city_not_found', array(':city_name' => $current_city->name)));

        $this->view = View::factory('frontend/filter/car_brands')
                          ->set('content', $content)
                          ->set('car_brands', $car_brand)
                          ->set('cities', array_intersect_key($this->_geo_params['cities']['total'], $this->_geo_params['cities']['has_cars']))
                          ->set('selected_city_ids', $this->_selected_city_ids)
                          ->set('districts', $district)
                          ->set('metro_stations', $metro);
        $this->template->bc['#'] = 'Подбор автосервиса по марке автомобиля';
        $this->template->title = $this->template->bc['#'];
        $this->template->content = $this->view;
    }
    /**
     * Теги услуг
     * @return void
     */
    public function action_work()
    {
        if ($this->request->param('city', NULL))
        {
            $this->_selected_city_ids = Geography::set_selected_city_id($this->_geo_params['cities'], $this->request->param('city', NULL));
            // Указываем выбранные города для шаблона
            $this->template->selected_city_ids = array_intersect_key($this->_selected_city_ids, $this->template->selected_city_ids);
            Cookie::set('filter_city', $this->_selected_city_ids['total']);
        }
        else
        {
            $this->request->redirect('filter/work/city_'.$this->_selected_city_ids['has_works']);
        }



        $items['works'] = array(
            'ids'  => NULL
        );
        // Получаем округи и станции метро
        $district = ORM::factory('district')->set_has_items($items)->get_districts($this->_geo_params, $this->_selected_city_ids['has_works']);
        $metro = ORM::factory('metro')->set_has_items($items)->get_metro($this->_geo_params, $this->_selected_city_ids['has_works']);

        $params = array(
            'city' => array(
                'field' => 'city_id',
                'op'    => '=',
                'value' => $this->_selected_city_ids['has_works']
            ),
        );
        // Берем услуги вместе с категориями
        $work = ORM::factory('work')->set_params($params)->get_works(TRUE);
        if (empty($work))
        {
            Message::set(Message::ERROR, 'Неправильные параметры для фильтра');
            $this->request->redirect('filter/work/city_'.$this->_selected_city_ids['has_works']);
        }

        $current_city = $this->_geo_params['cities']['total'][$this->_selected_city_ids['has_works']];
        $content = $current_city->contents->where('type', '=', 'work')->find();
        if (!trim($content->text))
            $content->text = __('filter_work_default_content', array(':city_name' => $content->city->name));;

        if ($this->request->query('selected_city') AND $this->request->query('selected_city') != $this->_selected_city_ids['has_works'])
            Message::set(Message::NOTICE, __('filter_work_city_not_found', array(':city_name' => $current_city->name)));

        $this->view = View::factory('frontend/filter/works')
                          ->set('content', $content)
                          ->set('category', $work)
                          ->set('cities', array_intersect_key($this->_geo_params['cities']['total'], $this->_geo_params['cities']['has_works']))
                          ->set('selected_city_ids', $this->_selected_city_ids)
                          ->set('districts', $district)
                          ->set('metro_stations', $metro);
        $this->template->bc['#'] = 'Подбор автосервиса по предоставляемым услугам';
        $this->template->title = $this->template->bc['#'];
        $this->template->content = $this->view;
    }
    /**
     * Теги метро
     * @return void
     */
    function action_metro()
    {
        if ($this->request->param('city'))
        {
            $this->_selected_city_ids = Geography::set_selected_city_id($this->_geo_params['cities'], $this->request->param('city'));
            $this->template->selected_city_ids = array_intersect_key($this->_selected_city_ids, $this->template->selected_city_ids);
            Cookie::set('filter_city', $this->_selected_city_ids['total']);
        }
        else
        {
            $this->request->redirect('filter/metro/city_'.$this->_selected_city_ids['has_metro']);
        }


        $params = array(
            'city' => array(
                'field' => 'city_id',
                'op'    => '=',
                'value' => $this->_selected_city_ids['has_metro']
            )
        );
        $metro = ORM::factory('metro')->get_metro($this->_geo_params, $params['city']['value']);
        if (empty($metro))
        {
            Message::set(Message::ERROR, 'Не найдены станции метро');
            $this->request->redirect('filter/metro/city_'.$this->_selected_city_ids['has_metro']);
        }

        $current_city = $this->_geo_params['cities']['total'][$this->_selected_city_ids['has_metro']];
        $content = $current_city->contents->where('type', '=', 'metro')->find();
        if (!trim(strip_tags($content->text)))
            $content->text = __('filter_metro_default_content', array(':name' => $content->city->name));

        if ($this->request->query('selected_city') AND $this->request->query('selected_city') != $this->_selected_city_ids['has_metro'])
            Message::set(Message::NOTICE, __('filter_metro_city_not_found', array(':city_name' => $current_city->name)));


        $this->view = View::factory('frontend/filter/metro_stations')
                          ->set('cities', array_intersect_key($this->_geo_params['cities']['total'], $this->_geo_params['cities']['has_metro']))
                          ->set('selected_city_ids', $this->_selected_city_ids)
                          ->set('metro_stations', $metro)
                          ->set('content', $content);
        $this->template->title = 'Подбор автосервиса по станции метро';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Теги округов
     * @return void
     */
    function action_district()
    {
        if ($this->request->param('city'))
        {
            $this->_selected_city_ids = Geography::set_selected_city_id($this->_geo_params['cities'], $this->request->param('city'));
            $this->template->selected_city_ids = array_intersect_key($this->_selected_city_ids, $this->template->selected_city_ids);
            Cookie::set('filter_city', $this->_selected_city_ids['total']);
        }
        else
        {
            $this->request->redirect('filter/district/city_'.$this->_selected_city_ids['has_district']);
        }

        $params = array(
            'city' => array(
                'field' => 'city_id',
                'op'    => '=',
                'value' => $this->_selected_city_ids['has_district']
            )
        );
        $district = ORM::factory('district')->get_districts($this->_geo_params, $params['city']['value']);
        if (empty($district))
        {
            Message::set(Message::ERROR, 'Не найдены округи');
            $this->request->redirect('filter/district/city_'.$this->_selected_city_ids['has_district']);
        }


        $current_city = $this->_geo_params['cities']['total'][$this->_selected_city_ids['has_district']];
        $content = $current_city->contents->where('type', '=', 'district')->find();

        if (!trim(strip_tags($content->text)))
            $content->text = __('filter_district_default_content', array(':name' => $content->city->name));

        if ($this->request->query('selected_city') AND $this->request->query('selected_city') != $this->_selected_city_ids['has_district'])
            Message::set(Message::NOTICE, __('filter_district_city_not_found', array(':city_name' => $current_city->name)));

        $this->view = View::factory('frontend/filter/districts')
                          ->set('cities', array_intersect_key($this->_geo_params['cities']['total'], $this->_geo_params['cities']['has_district']))
                          ->set('selected_city_ids', $this->_selected_city_ids)
                          ->set('districts', $district)
                          ->set('content', $content);
        $this->template->title = 'Подбор автосервиса по округу';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}