<?php
defined('SYSPATH') or die('No direct script access.');
class Kohana_NError
{
    public static function show_error($value, $array = array())
    {
        if (isset($array[$value]))
        {
            $view = View::factory('error');
            return $view->set('text', $array[$value])->render();
        }
    }
}