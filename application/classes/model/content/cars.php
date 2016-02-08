<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Content_Cars extends ORM
{
    protected $_table_name = 'content_cars';
    protected $_table_columns = array(
        'id'          => NULL,
        'car_id'      => NULL,
        'text'        => NULL,
        'date_edited' => NULL,
        'city_id'     => NULL
    );
    protected $_belongs_to = array(
        'car' => array(
            'model' => 'car_brand',
            'foreign_key' => 'car_id'
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