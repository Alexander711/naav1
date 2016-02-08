<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Orgtype extends ORM
{
    protected $_table_name = 'org_types';
    protected $_table_columns = array(
        'id'   => NULL,
        'name' => NULL
    );
    public function get_all_as_array()
    {
        $result = array();
        foreach ($this->find_all() as $o)
        {
            $result[$o->id] = $o->name;
        }
        return $result;
    }

    /**
     * Проверка организации
     * @static
     * @param $org_id
     * @return bool
     */
    public static function available($org_id)
    {
         return (bool) DB::select(array('COUNT("*")', 'total_count'))
                         ->from('org_types')
                         ->where('id', '=', $org_id)
                         ->execute()
                         ->get('total_count');
    }
}