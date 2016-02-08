<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Message extends ORM
{
    protected $_table_columns = array(
        'id'          => NULL,
        'title'       => NULL,
        'text'        => NULL,
        'date'        => NULL,
        'user_id'     => NULL,
        'from'        => NULL,
        'feedback_id' => NULL
    );
    public function rules()
    {
        return array(
            'title' => array(array('not_empty')),
            'text' => array(array('not_empty')),
            'user_id' => array(
                array('not_empty'),
                array('Model_User::available_user', array(':value'))
            ),
            'feedback_id' => array(
                array('not_empty'),
                array(array($this, 'check_feedback'), array(':value'))
            ),
            'from' => array(
                array('not_empty'),
                array('email'),
                array('in_array', array(':value', array('no-reply@as-avtoservice.ru', 'sekretar@as-avtoservice.ru')))
            )
        );
    }
    public function filters()
    {
        return array(
            'text' => array(array('trim')),
            'title' => array(array('trim'))
        );
    }
    public function check_feedback($value)
    {
        if ($value == 0)
        {
            return TRUE;
        }
        return (bool) DB::select(array('COUNT("*")', 'total_count'))
                        ->from('feedback')
                        ->where('id', '=', $value)
                        ->execute()
                        ->get('total_count');
    }
}