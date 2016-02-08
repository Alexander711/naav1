<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'username' => array(
        'not_empty' => 'Введите имя пользователя',
        'max_length' => 'Макс длина имени пользователя 32 символа',
        'unique' => 'Такое имя пользователя уже занято'
    ),
    'password' => array(
        'not_empty' => 'Введите пароль'
    ),
    'email' => array(
        'not_empty' => 'Введите email',
        'email' => 'Введите корректный email',
        'unique' => 'Пользователь с таким email уже зарегистрирован'
    ),
    '_external' => array(
    'accept_rule' => array(
        'not_empty' => 'Согласитесь с уставом ассоциации'
    )
    )
);