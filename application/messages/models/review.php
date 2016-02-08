<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'text' => array(
        'not_empty' => 'Введите текст отзыва'
    ),
    'name' => array(
        'not_empty' => 'Введите ваше имя'
    ),
    'email' => array(
        'not_empty' => 'Введите email',
        'email'     => 'Введите корректный email'
    ),
    'service_id' => array(
        'not_empty' => 'Выберите компанию',
        'available' => 'Не найдена компания'
    )
);