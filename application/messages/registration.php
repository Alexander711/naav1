<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'username' => array(
        'not_empty' => 'Введите имя пользователя',
        'unique' => 'Пользватель с таким именем уже зарегистрирован',
        'max_length' => 'Макс длина имени пользователя 32 символа'
    ),
    'password' => array(
        'not_empty' => 'Введите пароль'
    ),
    'email' => array(
        'not_empty' => 'Введите email',
        'unique' => 'Пользватель с таким email уже зарегистрирован'
    ),
    'username_email' => array(
        'not_empty' => 'Введите имя пользователя или Email адрес'
    )
);