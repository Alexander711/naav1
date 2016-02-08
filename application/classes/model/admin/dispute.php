<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Admin_Dispute extends ORM
{
    protected $_table_name = 'admin_disputes';
    protected $_table_columns = array(
        'id'          => NULL,
        'task_id'     => NULL,
        'text'        => NULL,
        'date_create' => NULL,
        'date_edited' => NULL
    );
    protected $_belongs_to = array(
        'task' => array(
            'model' => 'admin_task',
            'foreign_key' => 'task_id'
        )
    );
    public function rules()
    {
        return array(
            'text' => array(array('not_empty'))
        );
    }
    public function filters()
    {
        return array(
            'text' => array(array('trim'))
        );
    }
}