<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Invoicelog extends ORM {
	protected $_table_name = 'invoice_log';

	protected $_table_columns = array(
		'id'          => null,
		'invoice_id'     => null,
		'cdate' => null,
		'content' => null,
		'status'      => null
	);

	protected $_belongs_to = array(
		'invoice' => array(
			'model'       => 'invoice',
			'foreign_key' => 'invoice_id'
		)
	);
}