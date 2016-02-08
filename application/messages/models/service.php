<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'phone' => array(
        'not_empty' => 'Введите контактный телефон',
        'digit' => 'Номер телефона может состоять только из цифр'
    ),
    'name' => array(
        'not_empty' => 'Введите название компании'
    ),
    'group_type' => array(
        'not_empty' => 'Выберите категорию организации',
        'available' => 'Неправильный категория организации'
    ),
    'org_type' => array(
        'not_empty' => 'Выберите тип организации',
        'available' => 'Неправильный тип организации'
    ),
    'address' => array(
        'not_empty' => 'Введите адрес'
    ),
    'city_id' => array(
        'not_empty' => 'Выберите город',
        'available' => 'Неверный город'
    ),
    'district_id' => array(
        'available' => 'Неверный округ'
    ),
    'metro_id' => array(
        'available' => 'Неверная станция метро'
    ),
    'code' => array(
        'not_empty' => 'Введите код города',
        'digit' => 'Код города может состоять только из цифр'
    ),
    'inn' => array(
        'digit' => 'ИНН может состоять только из цифр'
    ),
    'discount' => array(
        'digit' => 'Скидка может быть только в виде цифр',
        'array_key_exists' => 'Неправильное значение скидки'
    )
);