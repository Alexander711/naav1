<?php
defined('SYSPATH') or die('No direct script access.');
class MyDate
{
    static function show($date, $witch_time = FALSE)
    {
        $month_lang_key = 'genitive_month_'.Date::formatted_time($date, 'm');
        $result = Date::formatted_time($date, 'd').' '.__($month_lang_key).' '.Date::formatted_time($date, 'Y');
        if ($witch_time)
            $result .= ' '.Date::formatted_time($date, 'H').':'.Date::formatted_time($date, 'i');
        return $result;
    }
    static function show_small($date)
    {
        return Date::formatted_time($date, 'd').'.'.Date::formatted_time($date, 'm').'.'.Date::formatted_time($date, 'Y');
    }
    static function show_unix_date($date)
    {
        return mktime(Date::formatted_time($date, 'H'), Date::formatted_time($date, 'i'), Date::formatted_time($date, 's'), Date::formatted_time($date, 'm'), Date::formatted_time($date, 'd'), Date::formatted_time($date, 'Y'));
    }
}