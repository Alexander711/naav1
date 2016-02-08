<?php
defined('SYSPATH') or die('No direct script access.');
class Model_ContentSite extends ORM
{
    protected $_table_name = 'content_site';
    protected $_table_columns = array(
        'id'          => NULL,
        'date'        => NULL,
        'url'         => NULL,
        'title'       => NULL,
        'keywords'    => NULL,
        'description' => NULL,
        'text'        => NULL,
        'active'      => NULL
    );
    public function rules()
    {
        return array(
            'title' => array(array('not_empty')),
            'text' => array(array('not_empty')),
            'url' => array(
                array('not_empty'),
                array('alpha_dash')
            ),
            'date' => array(array('not_empty'))
        );
    }
    public function filters()
    {
        return array(
            'title' => array(array('trim')),
            'text' => array(array('trim')),
            'url' => array(array('trim')),
            'date' => array(array('trim'))
        );
    }
}