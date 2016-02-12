<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'accept_rule' => array(
        'not_empty' => 'Согласитесь с уставом ассоциации'
    ),
    'news_image' => array(
        'Upload::type' => 'Неправильный формат изображения.'
    ),
    'password' => array(
        'not_empty' => 'Введите пароль',
        'min_length' => 'Пароль должен состоять как минимум из 6 символов',
        'alpha_dash' => 'Пароль должен состоять только из букв латинского языка, цифр и знаков _ и -'
    ),
    'service' => array(
        'Model_Notice::check_services' => 'Неправильные сервисы'
    ),
    'antibot' => array(
        'not_empty' => 'Подвердите отправку отзыва'
    ),
    'group_id_1' => array(
        'Model_Group::check_valid_group' => 'Неправильная группа'
    ),
    'sub_group_id_1' => array(
        'Model_Group::check_valid_group' => 'Неправильная подгруппа'
    ),
    'group_id_2' => array(
        'Model_Group::check_valid_group' => 'Неправильная группа'
    ),
    'sub_group_id_2' => array(
        'Model_Group::check_valid_group' => 'Неправильная подгруппа'
    ),
    'group_id_3' => array(
        'Model_Group::check_valid_group' => 'Неправильная группа'
    ),
    'sub_group_id_3' => array(
        'Model_Group::check_valid_group' => 'Неправильная подгруппа'
    ),
    'group_id_4' => array(
        'Model_Group::check_valid_group' => 'Неправильная группа'
    ),
    'sub_group_id_4' => array(
        'Model_Group::check_valid_group' => 'Неправильная подгруппа'
    ),
    'group_id_5' => array(
        'Model_Group::check_valid_group' => 'Неправильная группа'
    ),
    'sub_group_id_5' => array(
        'Model_Group::check_valid_group' => 'Неправильная подгруппа'
    ),
    'city_name' => array(
        'not_empty' => 'Введите город',
        'Model_Service::check_valid_city' => 'Такого городе нет'
    ),
);