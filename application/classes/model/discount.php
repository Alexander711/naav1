<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Discount extends ORM
{
    protected $_table_columns = array(
        'id'      => NULL,
        'percent' => NULL
    );
    private $_params = array();
    private $_has_items = array();
    public function set_params($params)
    {
        $this->_params = $params;
        return $this;
    }
    public function set_has_items($items)
    {
        $this->_has_items = $items;
        return $this;
    }

    public function get_discounts()
    {
        $this->distinct('id')
             ->join(array('services', 'service'))
             ->on('discount.id', '=', 'service.discount_id');

        foreach ($this->_params as $param)
        {
            if ($param['value'])
                $this->and_where('service.'.$param['field'], $param['op'], $param['value']);
        }

        $config = Kohana::$config->load('settings.services_items');
        foreach ($this->_has_items as $name => $item)
        {
            if (!isset($config[$name]))
                continue;

            $has_configuration = $config[$name];
            $this->join(array($has_configuration['table_name'], $name))
                 ->on('service.id', '=', $name.'.service_id');
            if (is_array($item['ids']) AND !empty($item['ids']))
                $this->and_where($name.'.'.$has_configuration['key'], 'in', $item['ids']);
        }
        
        $this->order_by('percent', 'ASC');
        $discounts = array();
        foreach ($this->find_all() as $m)
        {
            $discounts[$m->id] = $m->percent;
        }
        return $discounts;
    }
    public function get_all_as_array()
    {
        $result = array();
        foreach ($this->find_all() as $d)
        {
            $result[$d->id] = $d->percent.'%';
        }
        return $result;
    }

}