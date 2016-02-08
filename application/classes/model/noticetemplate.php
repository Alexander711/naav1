<?php
defined('SYSPATH') or die('No direct script access.');
class Model_NoticeTemplate extends ORM
{
    protected $_table_name = 'notice_templates';
    protected $_table_columns = array(
        'id'    => NULL,
        'name'  => NULL,
        'title' => NULL,
        'text'  => NULL
    );
    
}
?>