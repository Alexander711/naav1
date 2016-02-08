<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Question extends ORM
{
    protected $_table_columns = array(
        'id'                  => NULL,
        'active'              => NULL,
        'car_id'              => NULL,
        'model_id'            => NULL,
        'volume'              => NULL,
        'gearbox_id'          => NULL,
        'city_id'             => NULL,
        'year'                => NULL,
        'vin'                 => NULL,
        'text'                => NULL,
        'contact'             => NULL,
        'email'               => NULL,
        'phone'               => NULL,
        'user_ip'             => NULL,
        'date'                => NULL,
        'image'               => NULL,
        'for_service_has_car' => NULL,
        'for_service_address' => NULL
    );
    protected $_belongs_to = array(
        'model' => array(
            'model' => 'car_model',
            'foreign_key' => 'model_id'
        ),
        'carbrand' => array(
            'model' => 'car_brand',
            'foreign_key' => 'car_id'
        ),
        'gearbox' => array(
            'model' => 'gearbox',
            'foreign_key' => 'gearbox_id'
        )
    );
    protected $_has_many = array(
        'works' => array(
            'model' => 'work',
            'through' => 'questions_works'
        ),
        'answers' => array(
            'model' => 'answer',
            'foreign_key' => 'question_id'
        ),
        'services' => array(
            'model' => 'service',
            'through' => 'questions_services'
        )
    );
    function rules()
    {
        return array(
            'car_id' => array(
                array('not_empty'),
                array(array($this, 'check_car_brand'), array(':value'))
            ),
            'model_id' => array(
                array('not_empty'),
                array(array($this, 'check_car_model'), array(':value'))
            ),
            'gearbox_id' => array(
                array('not_empty'),
                array(array($this, 'check_gearbox'), array(':value'))
            ),
            'email' => array(
                array('not_empty'),
                array('email')
            ),
            'contact' => array(array('not_empty')),
            'text' => array(array('not_empty')),
            'date' => array(array('not_empty')),
        );
    }
    function filters()
    {
        return array(
            'text' => array(array('trim')),
            'phone' => array(array('trim')),
            'email' => array(array('trim')),
            'contact' => array(array('trim')),
            'vin' => array(array('trim')),
        );
    }
    public function check_car_brand($value)
    {
        return (bool) DB::select(array('COUNT("*")', 'total_count'))->from('car_brands')->where('id', '=', $value)->execute()->get('total_count');
    }
    public function check_car_model($value)
    {
        return (bool) DB::select(array('COUNT("*")', 'total_count'))->from('car_models')->where('id', '=', $value)->execute()->get('total_count');
    }
    public function check_gearbox($value)
    {
        return (bool) DB::select(array('COUNT("*")', 'total_count'))->from('gearbox')->where('id', '=', $value)->execute()->get('total_count');
    }
}