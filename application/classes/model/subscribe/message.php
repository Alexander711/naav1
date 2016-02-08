<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Модель хранящая очередь на отправку сообщений пользователям (не уведомления)
 */
class Model_Subscribe_Message extends ORM
{
    protected $_table_name = 'message_subscribe';
    protected $_table_columns = array(
        'id',
        'message_id' => NULL,
        'user_id'    => NULL,
        'status_id'  => NULL,
        'from'       => NULL
    );
    protected $_belongs_to = array(
        'user' => array(
            'model'       => 'user',
            'foreign_key' => 'user_id'
        ),
        'message' => array(
            'model'       => 'message',
            'foreign_key' => 'message_id'
        )
    );
}