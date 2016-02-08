<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Answer extends ORM
{
    protected $_table_columns = array(
        'id'          => NULL,
        'user_id'     => NULL,
        'text'        => NULL,
        'service_id'  => NULL,
        'question_id' => NULL,
        'date'        => NULL
    );
    protected $_belongs_to = array(
        'service' => array(
            'model' => 'service',
            'foreign_key' => 'service_id'
        ),
        'question' => array(
            'model' => 'question',
            'foreign_key' => 'question_id'
        ),
        'user_id' => array(
            'model' => 'user',
            'foreign_key' => 'user_id'
        )
    );
    function rules()
    {
        return array(
            'text' => array(array('not_empty'))
        );
    }
}