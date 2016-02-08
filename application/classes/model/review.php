<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Review extends ORM
{
    protected $_table_columns = array(
        'id'         => NULL,
        'service_id' => NULL,
        'text'       => NULL,
        'date'       => NULL,
        'active'     => NULL,
        'user_id'    => NULL,
        'user_ip'    => NULL,
        'name'       => NULL,
        'email'      => NULL
    );
    
    protected $_belongs_to = array(
        'service' => array(
            'model' => 'service',
            'foreign_key' => 'service_id'
        ),
        'user' => array(
            'model' => 'user',
            'foreign_key' => 'user_id'
        )
    );

    public function rules()
    {
        return array(
            'service_id' => array(
                array('not_empty'),
                array(array('Model_Service', 'available'), array(':value'))
            ),
            'text' => array(array('not_empty')),
            'email' => array(
                array('not_empty'),
                array('email')
            ),
            'name' => array(
                array('not_empty')
            ),
            'date' => array(array('not_empty'))
        );
    }
    public function filters()
    {
        return array(
            'text' => array(array('trim')),
            'email' => array(array('trim')),
            'name' => array(array('trim')),
            'date' => array(array('trim'))
        );
    }
    /**
     * Получаем все отзывы
     * @param string $date_sort
     * @return Database_Result
     */
    public function get_reviews($date_sort = 'DESC')
    {
        return $this->where('active', '=', 1)->order_by('date', $date_sort)->find_all();
    }
    /**
     * Получаем отзыв
     * @param $id
     * @return ORM
     */
    public function get_review($id)
    {
        return $this->where('id', '=', $id)->where('active', '=', 1)->find();
    }
}