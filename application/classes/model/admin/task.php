<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Admin_Task extends ORM
{
    protected $_table_name = 'admin_tasks';
    protected $_table_columns = array(
        'id'          => NULL,
        'title'       => NULL,
        'priority'    => NULL,
        'status'      => NULL,
        'text'        => NULL,
        'date_create' => NULL,
        'date_edited' => NULL
    );
    protected $_has_many = array(
        'disputes' => array(
            'model' => 'admin_dispute',
            'foreign_key' => 'task_id'
        )
    );
    public function rules()
    {
        return array(
            'text' => array(array('not_empty')),
            'title' => array(array('not_empty')),
            'priority' => array(array('not_empty')),
            'status' => array(array('not_empty'))
        );
    }
    public function filters()
    {
        return array(
            'text' => array(array('trim')),
            'title' => array(array('trim'))
        );
    }

}