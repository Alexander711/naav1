<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'title' => array(
        'not_empty' => 'Введите заголовок запроса'
    ),
    'text' => array(
        'not_empty' => 'Введите текст запроса'
    ),
    'service_id' => array(
        'check_service' => 'Вы не можете отправить запрос для данной компании'
    )
);