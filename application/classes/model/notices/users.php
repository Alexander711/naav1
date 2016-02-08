<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Notices_Users extends ORM
{
    protected $_table_name = 'notices_users';
    protected $_belongs_to = array(
        'user' => array(
            'model'       => 'user',
            'foreign_key' => 'user_id'
        ),
        'notice' => array(
            'model'       => 'notice',
            'foreign_key' => 'notice_id'
        )
    );
}