<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'title' => array(
        'not_empty' => 'Введите заголовок'
    ),
    'text' => array(
        'not_empty' => 'Введите текст'
    ),
    'user_id' => array(
        'not_empty' => 'не выбран пользователь для отправки',
        'Model_User::available_user' => 'Нет такого пользователя'
    ),
    'feedback_id' => array(
        'check_feedback' => 'Нет такой записи обратной связи для ответа на него'
    )
);