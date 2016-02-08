<?php
defined('SYSPATH') or die('No direct script access.');
class Model_ContentWorks extends ORM
{
    protected $_table_name = 'content_works';
    protected $_table_columns = array(
        'id'          => NULL,
        'work_id'     => NULL,
        'text'        => NULL,
        'date_edited' => NULL
    );
    protected $_belongs_to = array(
        'work' => array(
            'model' => 'work',
            'foreign_key' => 'work_id'
        )
    );
}