<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Paymentlog extends ORM {
	protected $_table_name = 'payment_log';

	protected $_table_columns = array(
		'id'          => null,
		'user_id'     => null,
		'cdate' => null,
		'old_value' => null,
		'new_value'      => null
	);

	protected $_belongs_to = array(
		'user' => array(
			'model'       => 'user',
			'foreign_key' => 'user_id'
		)
	);
}