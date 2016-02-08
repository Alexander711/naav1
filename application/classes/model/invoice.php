<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Invoice extends ORM {
	protected $_table_name = 'invoice';

	protected $_table_columns = array(
		'id'          => null,
		'user_id'     => null,
		'payment_id'  => null,
		'amount'      => null,
		'days_amount' => null,
		'create_date' => null,
		'modify_date' => null,
		'status'      => null,
		'entrance_fee'      => null
	);


	protected $_belongs_to = array(
		'payment' => array(
			'model'       => 'payment',
			'foreign_key' => 'payment_id'
		),
		'user'    => array(
			'model'       => 'user',
			'foreign_key' => 'user_id'
		)
	);

	public function  rules() {

		return array(
			'modify_date'       => array(array('not_empty')),
			'create_date'       => array(array('not_empty')),
			'user_id' => array(
				array('not_empty'),
//				array(array($this, 'check_user'), array(':value'))
			),
			'payment_id' => array(
				array('not_empty'),
				array(array($this, 'check_payment'), array(':value'))
			),
			'status'  => array(
				array('not_empty'),
				array('in_array', array(':value', array('N', 'P', 'C', 'E')))
			),
			'amount'   => array(
				array('not_empty'),
				array('numeric', array(':value', TRUE))
			),
			'days_amount'    => array(
				array('not_empty'),
				array('digit', array(':value', TRUE))
			),

		);

	}



	/**
     * Проверка на валидность указываемого сервиса.
     * Пользователь может создать новость только к своим сервисам.
     * Исключение админ
     * @return void
     */
    public function check_payment($value)
    {
        if (DB::select('id')->from('payment')->where('id', '=', $value)->execute()->get('id'))
        {
            return true;
        }
        return false;
    }
}