<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Subscribe extends ORM
{
    protected $_table_columns = array(
        'id'        => NULL,
        'notice_id' => NULL,
        'user_id'   => NULL,
        'status'    => NULL
    );
    protected $_table_name = 'email_subscribe';

}