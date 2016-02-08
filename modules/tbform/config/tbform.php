<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'form_id_prefix' => 'tbform_',
    'input' => array(
        'size' => 6,
        'horizontal' => FALSE
    ),
    'textarea' => array(
        'rows' => 3,
        'size' => 6,
        'horizontal' => FALSE
    ),
    'checkboxes' => array(
        'horizontal' => FALSE,
        'value_key' => 'val',
        'label_key' => 'label',
        'default_label_text' => 'checkbox_'
    ),
    'radiobuttons' => array(
        'horizontal' => FALSE,
        'value_key' => 'val',
        'label_key' => 'label'
    ),
    'actions' => array(
        'horizontal' => FALSE,
        'presets' => array(
            'save_and_reset' => array(
                'submit' => array(
                    'text' => 'button_save',
                    'attributes' => array('class' => 'btn btn-large btn-success')
                ),
                'reset' => array(
                    'text' => 'button_reset',
                    'attributes' => array('class' => 'btn btn-large btn-danger')
                )
            ),
            'send_and_reset' => array(
                'submit' => array(
                    'text' => 'button_send',
                    'attributes' => array()
                ),
                'reset' => array(
                    'text' => 'button_reset',
                    'attributes' => array()
                )
            ),
            'save' => array(
                'submit' => array(
                    'text' => 'button_save',
                    'attributes' => array('class' => 'btn btn-large btn-success')
                )
            )
        )
    )
);