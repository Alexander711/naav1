<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Test extends ORM
{
    protected $_table_name = 'test';
    public function rules()
    {
        if (!$this->validation_enable) return array();
        return array(
            'text' => array(array('not_empty'))
        );
    }

}