<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Metro extends ORM
{
    protected $_table_name = 'metro';
    protected $_table_columns = array(
        'id'              => NULL,
        'name'            => NULL,
        'city_id'         => NULL,
        'main_left'   => NULL,
        'main_top'    => NULL,
        'main_width'  => NULL,
        'main_height' => NULL,
        'marker_top'  => NULL,
        'marker_left' => NULL,
        'name_top'    => NULL,
        'name_top'    => NULL
    );
    protected $_has_one = array(
        'content' => array(
            'model'       => 'content_metro',
            'foreign_key' => 'metro_id'
        )
    );
    protected $_belongs_to = array(
        'city' => array(
            'model' => 'city',
            'foreign_key' => 'city_id'
        )
    );
    protected $_has_many = array(
        'services' => array(
            'model' => 'service',
            'foreign_key' => 'metro_id'
        )
    );
    protected $_reload_on_wakeup = FALSE;
    private $_has_items = array();
    public function rules()
    {
        return array(
            'name' => array(array('not_empty')),
            'city_id' => array(
                array('not_empty'),
                array(array('Model_City', 'available'), array(':value'))
            )
        );
    }

    /**
     * Проверка наличия станции метро
     * @static
     * @param $metro_id
     * @param $city_id
     * @return bool
     */
    public static function available($metro_id, $city_id)
    {
        return (bool) DB::select(array('COUNT("*")', 'total_count'))
                        ->from('metro')
                        ->where('id', '=', $metro_id)
                        ->and_where('city_id', '=', $city_id)
                        ->execute()
                        ->get('total_count');
    }
    public function set_has_items($items)
    {
        $this->_has_items = $items;
        return $this;
    }
    /**
     * Возвращаем станции метро по городу и/или округу
     * @param array $cities
     * @param null $city_id
     * @param array $districts
     * @param null $district_id
     * @return array
     */
    public function get_metro(Array $geo_params = NULL, $city_id = NULL, $district_id = NULL)
    {
        $metro_stations = array();
        $config = Kohana::$config->load('settings.services_items');
        foreach ($this->_has_items as $name => $item)
        {
            if (!array_key_exists($name, $config))
                unset($this->_has_items[$name]);
        }
        unset ($name);
        unset ($item);

        foreach ($geo_params['metro_stations'] as $id => $value)
        {
            if (!empty($this->_has_items))
            {
                $has_items = count($this->_has_items);
                foreach ($this->_has_items as $name => $item)
                {
                    if (empty($value[$name]))
                    {
                        $has_items --;
                        continue;
                    }

                    if (isset($item['ids']) AND is_array($item['ids']) AND !empty($item['ids']))
                    {

                        $available_items = array_intersect($value[$name], $item['ids']);
                        if (empty($available_items))
                            $has_items --;
                    }
                }
                if ($has_items <= 0)
                    continue;
            }

            if ($district_id AND array_key_exists($district_id, $geo_params['districts']))
            {
                if ($value['city_id'] == $city_id AND $value['district_id'] == $district_id)
                {
                    $metro_stations[$id] = $value['obj'];
                }
            }
            else
            {
                if ($value['city_id'] == $city_id)
                {
                    $metro_stations[$id] = $value['obj'];
                }
            }
        }

        $metro_stations = $this->sort($metro_stations, 'symbol', 'name');


        return $metro_stations;
    }
    public function get_cities()
    {
        $cities = array();
        foreach ($this->find_all() as $metro)
        {
            if (!array_key_exists($metro->city_id, $cities))
                $cities[$metro->city_id] = $metro->city->name;
        }
        return $cities;
    }
    /**
     * Получение достпуных станций метро от города
     * @param $city_id
     * @return array
     */
    public function get_all_by_city($city_id)
    {
        $stations = array();
        foreach ($this->where('city_id', '=', $city_id)->order_by('name', 'ASC')->find_all() as $m)
        {
            $stations[$m->id] = $m->name;
        }
        return $stations;
    }
    protected  static  function cmp($a, $b)
    {
        $sort = strcmp($a->name, $b->name);
        if ($sort == 0) return 0;
        return ($sort == '-1') ? -1 : 1;
    }
}