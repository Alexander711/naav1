<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Content_District extends ORM
{
    protected $_table_name = 'content_district';
    protected $_table_columns = array(
        'id'          => NULL,
        'date_edited' => NULL,
        'district_id' => NULL,
        'text'        => NULL,
        'city_id'     => NULL
    );
    protected $_belongs_to = array(
        'district' => array(
            'model' => 'district',
            'foreign_key' => 'district_id'
        ),
        'city' => array(
            'model' => 'city',
            'foreign_key' => 'city_id'
        )
    );
}