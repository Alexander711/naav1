<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Content_Filter extends ORM
{
    protected $_table_name = 'content_filter';
    protected $_table_columns = array(
        'id'          => NULL,
        'type'        => NULL,
        'city_id'     => NULL,
        'text'        => NULL,
        'date_edited' => NULL
    );
    protected $_belongs_to = array(
        'city' => array(
            'model' => 'city',
            'foreign_key' => 'city_id'
        )
    );
    public $disable_validation = FALSE;
    function rules()
    {
        if ($this->disable_validation)
            return array();
        $types = array('auto', 'work', 'metro', 'district');
        return array(
            'type' => array(
                array('not_empty'),
                array('in_array', array(':value', $types))
            ),
            'date_edited' => array(array('not_empty')),
            'city_id' => array(
                array(array($this, 'check_city'), array(':value'))
            )
        );
    }
    function filters()
    {
        return array(
            'text' => array(array('trim'))
        );
    }
    // Получение контента для страниц фильтров
    public function get_content($type, $city_id = NULL)
    {
        
    }
    public function check_city($value)
    {
        if ($value == 0)
        {
            return TRUE;
        }
        return (bool) DB::select(array('COUNT("*")', 'total_count'))->from('cities')->where('id', '=', $value)->execute()->get('total_count');
    }
}