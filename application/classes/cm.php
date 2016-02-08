<?php
defined('SYSPATH') or die('No direct script access.');
class Cm
{
	function prd($elem) {
		pr($elem);
		die;
	}

	public static function pr($elem) {
		echo '<hr><pre>';
		print_r($elem);
		echo '</pre><hr>';
	}
}