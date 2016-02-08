<?php
defined('SYSPATH') or die('No direct script access.');
class Model_ContentCars extends ORM
{
    protected $_table_name = 'content_cars';
    protected $_table_columns = array(
        'id'          => NULL,
        'car_id'      => NULL,
        'text'        => NULL,
        'date_edited' => NULL
    );
    protected $_belongs_to = array(
        'car' => array(
            'model' => 'carbrand',
            'foreign_key' => 'car_id'
        )
    );
   
}