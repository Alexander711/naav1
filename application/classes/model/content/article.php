<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Content_Article extends ORM
{
    protected $_table_name = 'content_articles';
    protected $_table_columns = array(
        'id'          => NULL,
        'active'      => NULL,
        'title'       => NULL,
        'meta_d'      => NULL,
        'meta_k'      => NULL,
        'text'        => NULL,
        'date_create' => NULL,
        'date_edited' => NULL
    );
    public function rules()
    {
        return array(
            'title' => array(array('not_empty')),
            'text' => array(array('not_empty')),
            'date_create' => array(array('not_empty'))
        );
    }
}