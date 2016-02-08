<?php
defined('SYSPATH') or die('No direct script access.');
class ORM extends Kohana_ORM
{
    static  $sort_field = 'name';

    public function sort(Array $items, $mode = 'symbol', $sort_field = 'name')
    {
        self::$sort_field = $sort_field;

        switch ($mode)
        {
            case 'len':
                uasort($items, array('ORM', 'len'));
                break;
            default:
                uasort($items, array('ORM', 'cmp'));
        }

        return $items;
    }
    protected static  function cmp($a, $b)
    {
        $field = self::$sort_field;
        $sort = strcmp($a->$field, $b->$field);
        if ($sort == 0) return 0;
        return ($sort == '-1') ? -1 : 1;
    }
    protected static function len($a, $b)
    {
        $field = self::$sort_field;
        $len_a =  mb_strlen($a->$field);
        $len_b = mb_strlen($b->$field);
        if ($len_a == $len_b)
            $sort = 0;
        else if ($len_a > $len_b)
            $sort = 1;
        else
            $sort = -1;
        if ($sort == 0) return 0;
        return ($sort == '-1') ? -1 : 1;
    }

    public function get_table_name()
    {
        return $this->_table_name;
    }
}