<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Notices_Services extends ORM
{
    protected $_table_name = 'notices_services';
    protected $_primary_key = 'notice_id';
    protected $_belongs_to = array(
        'service' => array(
            'model'       => 'service',
            'foreign_key' => 'service_id'
        ),
        'notice' => array(
            'model'       => 'notice',
            'foreign_key' => 'notice_id'
        )
    );

}