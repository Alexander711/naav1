<?php
defined('SYSPATH') or die('No direct script access.');
class Model_City extends ORM
{
    protected $_table_name   = 'cities';
    protected $_table_columns = array(
        'id'                  => NULL,
        'name'                => NULL,
        'img_metro_map'       => NULL,
        'img_metro_map_clear' => NULL,
        'img_metro_width'      => NULL,
        'img_metro_height'     => NULL,
        'genitive_name'       => NULL,
        'dativus_name'        => NULL
    );
    protected $_reload_on_wakeup = FALSE;
    protected $_has_many = array(
        'districts' => array(
            'model' => 'district',
            'foreign_key' => 'city_id'
        ),
        'metro' => array(
            'model' => 'metro',
            'foreign_key' => 'city_id'
        ),
        'services' => array(
            'model' => 'service',
            'foreign_key' => 'city_id'
        ),
        'contents' => array(
            'model' => 'content_filter',
            'foreign_key' => 'city_id'
        ),
        'content_cars' => array(
            'model'       => 'content_cars',
            'foreign_key' => 'city_id'
        ),
        'content_works' => array(
            'model'       => 'content_works',
            'foreign_key' => 'city_id'
        )
    );

    private $_has_items = array();

    public function rules()
    {
        return array(
            'name'          => array(array('not_empty')),
            'genitive_name' => array(array('not_empty')),
            'dativus_name'  => array(array('not_empty')),
        );
    }

    public function set_has_items($items)
    {
        $this->_has_items = $items;
        return $this;
    }

    /**
     * Проверка наличия города.
     * @static
     * @param $city_id
     * @return bool
     */
    public static function available($city_id)
    {

        return (bool) DB::select(array('COUNT("*")', 'total_count'))->from('cities')->where('id', '=', $city_id)->execute()->get('total_count');
    }
    public function get_all_cities()
    {
        $cities = array();
        foreach ($this->order_by('name', 'ASC')->find_all() as $city)
        {
            $cities[$city->id] = $city->name;
        }
        return $cities;
    }
    /**
     * Возврвщаем города для сервисов
     * Параметры указываются сеттером set_has_items
     * @return array
     */
    public function get_cities($full_name = FALSE, $current_id = NULL)
    {
        $this->join(array('services', 'service'))
             ->on('service.city_id', '=', 'city.id')
             ->where('service.type', '=', 1);
        $config = Kohana::$config->load('settings.services_items');
        foreach ($this->_has_items as $item)
        {
            if (isset($item['name']))
            {
                switch ($item['name'])
                {
                    case 'metro':

                        $this->join(array('metro', 'metro'))
                             ->on('service.metro_id', '=', 'metro.id');
                        break;
                    case 'districts':
                        $this->join(array('okrug', 'district'))
                             ->on('service.district_id', '=', 'district.id');
                        break;
                    default:
                        if (isset($config[$item['name']]))
                        {
                            $this->join(array($config[$item['name']]['table_name'], $item['name']))
                                 ->on( $item['name'].'.service_id', '=','service.id');
                            if (is_array($item['ids']) AND !empty($item['ids']))
                                $this->and_where($item['name'].'.'.$config[$item['name']]['key'], 'in', $item['ids']);
                        }
                }
            }
        }

        $cities = array();
        foreach ($this->find_all() as $c)
        {
            if ($full_name)
            {
                $cities[$c->id] = array(
                    'name'          => $c->name,
                    'genitive_name' => $c->genitive_name,
                    'dativus_name'  => $c->dativus_name
                );
                if ($current_id AND $current_id == $c->id)
                    $cities[$c->id]['current_city_object'] = $c;
            }
            else
            {
                $cities[$c->id] = $c->name;
            }



        }
        return $cities;
    }
    /**
     * Получаем города для сервисов с разными параметрами, имеющие марки авто, услуги, метро и округи
     * @return array|mixed
     */
    public function get_cities_cache()
    {
        $cache = Cache::instance();
        if ($cache->get('services_cities'))
        {
            return $cache->get('services_cities');
        }
        else
        {
            $this->select(array('cars.service_id', 'cars_service_id'), array('works.service_id', 'works_service_id'), array('services.district_id', 'service_district_id'), array('services.metro_id', 'service_metro_id'))
                  ->join(array('services', 'services'))
                  ->on('services.city_id', '=', 'city.id')
                  ->where('services.type', '=', 1)
                  ->join(array('services_works', 'works'), 'left')
                  ->on('works.service_id', '=', 'services.id')
                  ->join(array('services_cars', 'cars'), 'left')
                  ->on('cars.service_id', '=', 'services.id');

            $cities['update_date'] = Date::formatted_time();

            foreach ($this->find_all() as $c)
            {
                //echo $c->service_name .' '.$c->works_service_id.' '.$c->name.'<br/>';

                if ($c->works_service_id)
                    $cities['has_works'][$c->id] = $c->name;
                if ($c->cars_service_id)
                    $cities['has_cars'][$c->id] = $c->name;
                if ($c->service_district_id)
                    $cities['has_district'][$c->id] = $c->name;
                if ($c->service_metro_id)
                    $cities['has_metro'][$c->id] = $c->name;
            }
            return $cities;
        }
    }
    public function get_cities_cache_2()
    {
        $cache = Cache::instance();
        if ($cache->get('services_cities_2'))
        {
            return $cache->get('services_cities_2');
        }
        else
        {
            return $this->update_cities_cache();
        }
    }
    /**
     * Обновление кэша городов
     * @return array
     */
    public function update_cities_cache()
    {
        $this
            ->select(array('cars.service_id', 'cars_service_id'), array('works.service_id', 'works_service_id'), array('services.district_id', 'service_district_id'), array('services.metro_id', 'service_metro_id'))
              ->join(array('services', 'services'))
              ->on('services.city_id', '=', 'city.id')
              ->where('services.type', '=', 1)
              ->join(array('services_works', 'works'), 'left')
              ->on('works.service_id', '=', 'services.id')
              ->join(array('services_cars', 'cars'), 'left')
              ->on('cars.service_id', '=', 'services.id');
        $cities['update_date'] = Date::formatted_time();

        foreach ($this->find_all() as $c)
        {
            if ($c->works_service_id)
                $cities['has_works'][$c->id] = $c->name;
            if ($c->cars_service_id)
                $cities['has_cars'][$c->id] = $c->name;
            if ($c->service_district_id)
                $cities['has_districts'][$c->id] = $c->name;
            if ($c->service_metro_id)
                $cities['has_metro_stations'][$c->id]['city'] = $c->name;

        }
        //Cache::instance()->set('services_cities_2', $cities);
        return $cities;
    }
    public static function cmp($a, $b)
    {
        $sort = strcmp($a->name, $b->name);
        if ($sort == 0) return 0;
        return ($sort == '-1') ? -1 : 1;
    }

}