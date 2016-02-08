<?php
defined('SYSPATH') or die('No direct script access.');
class Date extends Kohana_Date {

	public static function full_date($date_str, $with_time = false, $without_year = false) {

		$time_stamp = ($with_time) ? 'd * Y H:i' : 'd * Y';
		if ($without_year === true)
			$time_stamp = str_replace(' Y', '', $time_stamp);
		$formatted_date = Date::formatted_time($date_str, $time_stamp);

		return __($formatted_date, array('*' => __('genitive_month_' . Date::formatted_time($date_str, 'm'))));
	}

	/*
	 * Изврат без пхп 5.3 =)
	 * $format = 'days,hours,minutes,seconds'
	 *
	 * */
	public static function diff($date1, $date2 = null, $format = 'seconds') {
		$date1 = strtotime($date1);

		if(!empty($date2))
			$date2 = strtotime($date2);
		else
			$date2 = time();

		if ($date1 === false || $date2 === false) return 0;


		$seconds = $date1 - $date2;

		$sign = 1;
		if($seconds < 0) {
			$sign = -1;
			$seconds = $seconds*$sign; // аля абс
		}

//		C::hpr($seconds);

		switch ($format) {
			case "days":
				return $sign*floor($seconds/(60*60*24));
				break;
			case "hours":
				return $sign*floor($seconds/(60*60));
				break;
			case "minutes":
				return $sign*floor($seconds/60);
				break;
			case "seconds":

			default:
				return $sign*$seconds;
		}



		return false;
	}
}