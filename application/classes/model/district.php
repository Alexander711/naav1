<?php
defined('SYSPATH') or die('No direct script access.');
class Model_District extends ORM
{
    protected $_table_name = 'okrug';
    protected $_table_columns = array(
        'id'           => NULL,
        'name'         => NULL,
        'full_name'    => NULL,
        'abbreviation' => NULL,
        'city_id'      => NULL
    );
    protected $_has_one = array(
        'content' => array(
            'model'       => 'content_district',
            'foreign_key' => 'district_id'
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
            'foreign_key' => 'district_id'
        )
    );
    protected $_reload_on_wakeup = FALSE;
    private $_has_items = array();
    public function rules()
    {
        return array(
            'name' => array(array('not_empty')),
            'full_name' => array(array('not_empty')),
            'city_id' => array(
                array('not_empty'),
                array(array('Model_City', 'available'), array(':value'))
            )
        );
    }
    /**
     * Указываем, какие услуги или марки авто должны иметь сервисы для выборки их округов
     * @param $items
     * @return Model_District
     */
    public function set_has_items($items)
    {
        $this->_has_items = $items;
        return $this;
    }
    /**
     * Получение округов
     * @param null $city_id
     * @param null $has
     * @return array
     */
    public function get_districts(Array $geo_params, $city_id = NULL)
    {
        $districts = array();
        $config = Kohana::$config->load('settings.services_items');
        foreach ($this->_has_items as $name => $item)
            if (!array_key_exists($name, $config))
                unset($this->_has_items[$name]);

        foreach ($geo_params['districts'] as $id => $value)
        {
            if (count($this->_has_items) > 0)
            {
                $has_items = count($this->_has_items);
                foreach ($this->_has_items as $name => $item)
                {
                    if (empty($value[$name]))
                        $has_items --;

                    if (!empty($value[$name]) AND isset($item['ids']) AND is_array($item['ids']) AND !empty($item['ids']))
                    {
                        $available_items = array_intersect($item['ids'], $value[$name]);
                        if (empty($available_items))
                            $has_items --;
                    }
                }
                if ($has_items <= 0)
                    continue;
            }

            if ($value['city_id'] == $city_id)
            {
                $districts[$id] = $value['obj'];
            }
        }

        $districts = $this->sort($districts, 'symbol', 'name');
        return $districts;
    }
    /**
     * Получение округов от города
     * @param $city_id
     * @return array
     */
    public function get_all_by_city($city_id)
    {
        $districts = array();
        foreach ($this->where('city_id', '=', $city_id)->order_by('name', 'ASC')->find_all() as $d)
        {
            $districts[$d->id] = $d->name;
        }
        return $districts;
    }

    /**
     * Проверка наличия округа
     * @static
     * @param $district_id
     * @param $city_id
     * @return bool
     */
    public static function available($district_id, $city_id)
    {
        return (bool) DB::select(array('COUNT("*")', 'total_count'))
                        ->from('okrug')
                        ->where('id', '=', $district_id)
                        ->and_where('city_id', '=', $city_id)
                        ->execute()->get('total_count');
    }

    public static function  check_city_relations($district_id, $city_id)
    {
        //$district = DB::select('city_id')->from('okrug')->w
    }
}