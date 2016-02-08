<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Payment extends ORM {

	protected $_table_name = 'payment';

	protected $_table_columns = array(
		'id'             => null,
		'position'       => null,
		'status'         => null,
		'payment_name'   => null,
		'tips'           => null,
		'description'    => null
	);


	public function  rules() {

		return array(
			'position'       => array(array('not_empty')),
			'payment_name'   => array(array('not_empty')),
			'description'    => array(array('not_empty')),
			'status'         => array(
				array('not_empty'),
				array('in_array', array(':value', array('Y', 'N')))
			)

		);

	}

	public function filters() {

		return array(
			'tips'            => array(
				array('trim')
			),
			'payment_name' => array(
				array('trim')
			),
			'description'     => array(
				array('trim')
			)
		);
	}


}