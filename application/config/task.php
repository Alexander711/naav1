<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'statuses' => array(
        1 => array(
            // На рассмотрении
            'i18n' => 'task_status_1',
            'css' => 'label'
        ),
        2 => array(
            // Требуются доработки
            'i18n' => 'task_status_2',
            'css' => 'label label-warning'
        ),
        3 => array(
            // В разработке
            'i18n' => 'task_status_3',
            'css' => 'label label-info'
        ),
        4 => array(
            // Выполнено
            'i18n' => 'task_status_4',
            'css' => 'label label-success'
        ),
        5 => array(
            // Отклонено
            'i18n' => 'task_status_5',
            'css' => 'label label-important'
        ),
    ),
    'priorities' => array(
        1 => array(
            'i18n' => 'task_priority_1'
        ),
        2 => array(
            'i18n' => 'task_priority_2'
        ),
        3 => array(
            'i18n' => 'task_priority_3'
        ),
    )
);