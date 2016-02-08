<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Feedback extends ORM
{
    protected $_table_name = 'feedback';
    protected $_table_columns = array(
        'id'      => NULL,
        'user_id' => NULL,
        'service_id' => NULL,
        'type'    => NULL,
        'date'    => NULL,
        'title'   => NULL,
        'text'    => NULL
    );
    protected $_belongs_to = array(
        'user' => array(
            'model' => 'user',
            'foreign_key' => 'user_id'
        ),
        'service' => array(
            'model' => 'service',
            'foreign_key' => 'service_id'
        )
    );
    public $cabinet_feedback = TRUE;

    function rules()
    {
        $this->validation()->bind(':feedback_type', $this->type);
        $types = array(
            1 => 'feedback',
            2 => 'adv'
        );
        $rules = array(
            'type' => array(
                array('not_empty'),
                /*
                 * Проверка типа, на всякий пожарный
                 * 1 - обычный запрос обратной связи
                 * 2 - запрос на рекламу
                 */
                array('array_key_exists', array(':value', $types))
            ),
            'text' => array(array('not_empty')),
            'title' => array(array('not_empty')),
            'service_id' => array(
                array(array($this, 'check_service'), array(':value', ':feedback_type'))
            )
        );

        if ($this->cabinet_feedback)
            $rules['user_id'] = array(
                array('not_empty'),
                array(array($this, 'check_username'), array(':value'))
            );

        return $rules;
    }
    function filters()
    {
        return array(
            'text' => array(array('trim')),
            'title' => array(array('trim')),
        );
    }
    public function check_username($value)
    {
        if (Auth::instance()->logged_in())
        {
            return TRUE;
        }
        return FALSE;
    }
    public function check_service($value, $type)
    {
        if ($type == 1 OR $value == 0)
        {
            return TRUE;
        }
        $user = Auth::instance()->get_user();

        $service = DB::select('user_id')->from('services')->where('id', '=', $value)->execute()->get('user_id');
        if (!$user OR !$service OR $service != $user->id)
        {
            return FALSE;
        }
        return TRUE;
    }
}