<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Payment_Settings extends ORM {

	protected $_table_name = 'payment_settings';
	protected $_table_columns = array(
		'id'      => null,
		'name'    => null,
		'price'   => null,
		'days'    => null,
		'in_list' => null,
		'status'  => null,
		'sort'    => null,
		'system'  => null,
		'value'  => null
	);


	protected $_belongs_to = array();
	protected $_has_many = array();

	public function rules() {

		return array(
			'name'    => array(array('not_empty')),
			'price'   => array(
				array('not_empty'),
				array('numeric', array(':value', TRUE))
			),
			'days'    => array(
				array('not_empty'),
				array('digit', array(':value', TRUE))
			),
			'in_list' => array(
				array('not_empty'),
				array('in_array', array(':value', array('Y', 'N')))
			),
			'status'  => array(
				array('not_empty'),
				array('in_array', array(':value', array('Y', 'N')))
			),
			'sort'    => array(
				array('not_empty'),
				array('digit', array(':value', TRUE))
			),

		);
	}


	public function filters() {

		return array(
			'name'       => array(array('trim')),
			'value'       => array(array('trim')),
			'days'       => array(array('intval')),
			'sort'       => array(array('intval')),
			'price'       => array(array('intval')),
		);
	}
}