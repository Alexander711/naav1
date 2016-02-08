<?php defined('SYSPATH') or die('No direct script access.');

return array(

	// Application defaults
	'default' => array(
		'current_page'      => array('source' => 'route', 'key' => 'page'), // source: "query_string" or "route"
		'total_items'       => 0,
		'items_per_page'    => 50,
		'view'              => 'pagination/floating',
		'auto_hide'         => TRUE,
		'first_page_in_url' => FALSE,
		'first_page' => FALSE,
		'previous_page' => FALSE,
		'next_page' => FALSE,
		'last_page' => FALSE,
	),
);
