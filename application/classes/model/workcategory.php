<?php
defined('SYSPATH') or die('No direct script access.');
class Model_WorkCategory extends ORM
{
    protected $_table_name = 'work_categories';
    protected $_table_columns = array(
        'id' => NULL,
        'name' => NULL
    );
    protected $_has_many = array(
        'works' => array(
            'model' => 'work',
            'foreign_key' => 'category_id'
        )
    );
    public function rules()
    {
        return array(
            'name' => array(array('not_empty'))
        );
    }
    public function get_all_as_array()
    {
        $categories = array();
        foreach ($this->find_all() as $cat)
        {
            $categories[$cat->id] = $cat->name;
        }
        return $categories;
    }
}