<?php
defined('SYSPATH') or die('No direct script access.');
class C
{
	public static function prd($elem) {
		echo '<hr><pre>';
		print_r($elem);
		echo '</pre><hr>';
		die;
	}

	public static function pr($elem) {
		echo '<hr><pre>';
		print_r($elem);
		echo '</pre><hr>';
	}

	public static function hpr($elem,$die = false) {

		if(isset($_COOKIE['ydebug'])) {
			echo '<hr><pre>';
			print_r($elem);
			echo '</pre><hr>';
			if($die) die();
		}



	}
}