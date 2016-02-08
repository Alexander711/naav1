<?php
defined('SYSPATH') or die('No direct script access.');
class Model_CompanyImage extends ORM
{
    protected $_table_name = 'company_images';
    protected $_table_columns = array(
        'id'             => NULL,
        'date_created'   => NULL,
        'date_edited'    => NULL,
        'company_id'     => NULL,
        'img_path'       => NULL,
        'thumb_img_path' => NULL,
        'name'           => NULL,
        'title'          => NULL,
    );
    protected $_belongs_to = array(
        'company' => array(
            'model' => 'service',
            'foreign_key' => 'company_id'
        )
    );
    public function rules()
    {
        return array(
            'company_id' => array(
                array('not_empty'),
                array(array('Model_Service', 'available'))
            ),
            'img_path' => array(array('not_empty')),
            'thumb_img_path' => array(array('not_empty'))
        );
    }

}