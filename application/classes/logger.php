<?php
defined('SYSPATH') or die('No direct script access.');
class Logger
{
    const EDIT = 'edit';
    const ADD = 'add';
    const DELETE = 'delete';
    const ACTION = 'action';

    static function write($type, $text, Model_User $user)
    {
        DB::insert('user_logs', array('type', 'user_id', 'text', 'date'))->values(array($type, $user->id, $text, Date::formatted_time()))->execute();
    }
}