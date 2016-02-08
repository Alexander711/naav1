<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Content_Works extends ORM
{
    protected $_table_name = 'content_works';
    protected $_table_columns = array(
        'id'          => NULL,
        'work_id'     => NULL,
        'text'        => NULL,
        'date_edited' => NULL,
        'city_id'     => NULL
    );
    protected $_belongs_to = array(
        'work' => array(
            'model' => 'work',
            'foreign_key' => 'work_id'
        ),
        'city' => array(
            'model' => 'city',
            'foreign_key' => 'city_id'
        )
    );
    public function filters()
    {
        return array(
            'text' => array(
                array('Model_Content_Site::clean_brs')
            )
        );
    }
}