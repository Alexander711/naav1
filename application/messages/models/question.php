<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'contact' => array(
        'not_empty' => 'Введите имя для связи с вами'
    ),
    'email' => array(
        'not_empty' => 'Введите контактный Email',
        'email' => 'Введите корректный Email'
    ),
    'car_id' => array(
        'check_car_brand' => 'Выберите марку автомобиля'
    ),
    'model_id' => array(
        'check_car_model' => 'Выберите модель автомобиля'
    ),
    'text' => array(
        'not_empty' => 'Введите сопроводительный текст'
    ),
    'image' => array(
        'Upload::type' => 'Неправильный формат изображения.'
    )
);