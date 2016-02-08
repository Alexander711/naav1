<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Content_Metro extends ORM
{
    protected $_table_name = 'content_metro';
    protected $_table_columns = array(
        'id'          => NULL,
        'date_edited' => NULL,
        'metro_id'    => NULL,
        'text'        => NULL,
        'city_id'     => NULL
    );
    protected $_belongs_to = array(
        'metro' => array(
            'model' => 'metro',
            'foreign_key' => 'metro_id'
        ),
        'city' => array(
            'model' => 'city',
            'foreign_key' => 'city_id'
        )
    );
}