<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'title' => array(
        'not_empty' => 'Введите заголовок'
    ),
    'text' => array(
        'not_empty' => 'Введите текст'
    ),
    'url' => array(
        'not_empty' => 'Введите url',
        'alpha_dash' => 'URL может состоять только из англ. букв, знаков "_" и "-"'
    )
);