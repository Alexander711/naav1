<?php
defined('SYSPATH') or die('No direct script access.');
class Model_EmailSender extends ORM
{
    protected $_table_name = 'messages_list';
    protected $_table_columns = array(
        'id'          => NULL,
        'title'       => NULL,
        'text'        => NULL,
        'mail_from'   => NULL,
        'mail_to'     => NULL,
        'status'      => NULL,
        'date_create' => NULL,
        'date_send'   => NULL
    );
    public $validation_mode = TRUE;
    public function rules()
    {
        if (!$this->validation_mode)
            return array();

        return array(
            'title'     => array(array('not_empty')),
            'text'      => array(array('not_empty')),
            'mail_to'   => array(
                array('not_empty'),
                array('email')
            ),
            'mail_from' => array(
                array('not_empty'),
                array('email')
            )
        );
    }
}