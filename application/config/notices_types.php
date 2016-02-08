<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'types' => array(
        'service' => array(
            'unique' => FALSE,
            'for' => 'service',
            'date_column' => 'date',
            'read_column' => 'read_notices_services'
        ),
        'user' => array(
            'unique' => FALSE,
            'for' => 'user',
            'date_column' => 'date',
            'read_column' => 'read_notices_users'
        ),
        'registration_complete' => array(
            'unique' => TRUE,
            'for' => 'user',
            'date_column' => 'date_notices_users',
            'read_column' => 'read_notices_users'
        ),
        'shop_create' => array(
            'unique' => TRUE,
            'for' => 'service',
            'date_column' => 'date_notices_services',
            'read_column' => 'read_notices_services'
        ),
        'service_create' => array(
            'unique' => TRUE,
            'for' => 'service',
            'date_column' => 'date_notices_services',
            'read_column' => 'read_notices_services'
        )
    ),
    'selected_columns' => array(
        array('notices.id',            'id'),
        array('notices.text',          'text'),
        array('notices.title',         'title'),
        array('notices.type',          'type'),
        array('notices.date',          'date'),
        array('notices_services.service_id', 'service_id'),
        array('notices_services.id',   'id_notices_services'),
        array('notices_users.id',      'id_notices_users'),
        array('notices_services.read', 'read_notices_services'),
        array('notices_users.read',    'read_notices_users'),
        array('notices_users.date',    'date_notices_users'),
        array('notices_services.date', 'date_notices_services')
    )

);