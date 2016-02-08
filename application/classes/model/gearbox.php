<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Gearbox extends ORM
{
    protected $_table_name = 'gearbox';
    protected $_has_one = array(
        'question' => array(
            'model' => 'question',
            'foreign_key' => 'gearbox_id'
        )
    );

    public function get_gearboxes()
    {
        $gearboxes = array();
        foreach ($this->find_all() as $g)
        {
            $gearboxes[$g->id] = $g->name;
        }
        return $gearboxes;
    }
}