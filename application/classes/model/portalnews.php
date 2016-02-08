<?php
defined('SYSPATH') or die('No direct script access.');
class Model_PortalNews extends ORM
{
    protected $_table_name = 'assoc_news';
    protected $_table_columns = array(
        'id'     => NULL,
        'date'   => NULL,
        'active' => NULL,
        'title'  => NULL,
        'text'   => NULL
    );
}