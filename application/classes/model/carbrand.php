<?php
defined('SYSPATH') or die('No direct script access.');
class Model_CarBrand extends ORM
{
    protected $_table_name = 'car_brands';
    
    protected $_table_columns = array(
        'id'      => NULL,
        'name'    => NULL,
        'name_ru' => NULL
    );

    protected $_has_many = array(
        'services' => array(
            'model' => 'service',
            'through' => 'services_cars',
            'foreign_key' => 'car_id',
            'far_key' => 'service_id',
        )
    );
    protected $_has_one = array(
        'content' => array(
            'model' => 'contentcars',
            'foreign_key' => 'car_id'
        )
    );
  

    public function rules()
    {
        return array(
            'name_ru' => array(array('not_empty')),
        );
    }
    public function filters()
    {
        return array(
            'name_ru' => array(array('trim')),
            'name' => array(array('trim')),
        );
    }
    
    function get_cars($params = NULL, $witch_ru_names_and_autoservice = FALSE, $works = NULL)
    {
        $this->join(array('services_cars', 'serv_c'))
             ->on('serv_c.car_id', '=', 'carbrand.id')
             ->join(array('services', 'serv'))
             ->on('serv.id', '=', 'serv_c.service_id')
             ->where('serv.active', '=', 1);
        if ($params != NULL AND is_array($params) AND !empty($params))
        {
            foreach ($params as $field => $key)
            {
                $this->and_where('serv.'.$field, '=', $key);
            }
        }
        if ($works AND is_array($works) AND !empty($works))
        {
            $this->join(array('services_works', 'works'))
                 ->on('works.service_id', '=', 'serv.id')
                 ->where('works.work_id', 'in', $works);
        }
        //$this->group_by('name');
        $cars = array();
        foreach ($this->order_by('name_ru', 'ASC')->find_all() as $c)
        {
            if ($witch_ru_names_and_autoservice)
            {
                $name = ($c->name == '') ? '' : ' ('.$c->name.')';
                $str = 'Автосервисы '.$c->name_ru.$name;
            }
            else
            {
                $str = ($c->name != '') ? $c->name : $c->name_ru;
            }
            $cars[$c->id] = $str;
        }
    
        return $cars;
    }
    /**
     * Получаем марки авто
     * @return void
     */
    function get_cars_as_array()
    {
        $cars = array();
        foreach ($this->find_all() as $car)
        {
            $name = ($car->name == '') ? $car->name_ru : $car->name;
            $cars[$car->id] = $name;
        }
        return $cars;
    }
    /**
     * Сортирует авто и услуги для вывода на главной
     * @param array $models_input
     * @param array $works_input
     * @return array
     */
    public function sort_models_works($models_input = array(), $works_input = array())
    {
        $result = array(
            // Car brands
            0 => array(),
            // Works
            1 => array()
        );
        $settings = Kohana::$config->load('settings');

        $models = array_intersect($models_input, $settings['fast_filter_models']);
        if (count($models) > $settings['max_models'])
        {
            for ($i = 0; $i <= count($models) - $settings['max_models']; $i++)
            {
                array_pop($models);
            }
        }
        elseif (count($models) < $settings['max_models'])
        {
            $unused = array_diff($models_input, $models);
            foreach ($unused as $id => $name)
            {
                $models[$id] = $name;
                if (count($models) == $settings['max_models'])
                {
                    break;
                }
            }
        }

        $works = array_intersect($works_input, $settings['fast_filter_works']);
        if (count($works) > $settings['max_works'])
        {
            for ($i = 0; $i <= count($works) - $settings['max_works']; $i++)
            {
                array_pop($works);
            }
        }
        elseif (count($works) < $settings['max_works'])
        {
            $unused = array_diff($works_input, $works);
            foreach ($unused as $id => $name)
            {
                $works[$id] = $name;
                if (count($works) == $settings['max_works'])
                {
                    break;
                }
            }
        }

        $result[0] = $models;
        $result[1] = $works;
        return $result;
    }

}