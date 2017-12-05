<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function date_ind($format, $date = null)
{
	$str_date = str_replace("F", "{*}", $format);
	$str_date = str_replace("M", "{**}", $str_date);
	$str_date = str_replace("l", "{***}", $str_date);
	$str_date = str_replace("D", "{****}", $str_date);

	
	if ($date != null) {
		$str_date = date($str_date, strtotime($date));
		$str_date = str_replace("{*}", format_ind("F", $date), $str_date);
		$str_date = str_replace("{**}", format_ind("M", $date), $str_date);
		$str_date = str_replace("{***}", format_ind("l", $date), $str_date);
		$str_date = str_replace("{****}", format_ind("D", $date), $str_date);
	}else{
		$str_date = date($str_date);
		$str_date = str_replace("{*}", format_ind("F"), $str_date);
		$str_date = str_replace("{**}", format_ind("M"), $str_date);
		$str_date = str_replace("{***}", format_ind("l"), $str_date);
		$str_date = str_replace("{****}", format_ind("D"), $str_date);
	}

	return $str_date;
}

function format_ind($format, $date = null)
{
	$month = array(
		'January' 	=> 'Januari',
		'February' 	=> 'Februari',
		'March' 	=> 'Maret',
		'April'		=> 'April',
		'May'		=> 'Mei',
		'June'		=> 'Juni',
		'July'		=> 'Juli',
		'August'	=> 'Agustus',
		'September'	=> 'September',
		'October'	=> 'Oktober',
		'November' 	=> 'November',
		'December'	=> 'December');

	$month_short = array(
		'Jan' 	=> 'Jan',
		'Feb' 	=> 'Feb',
		'Mar' 	=> 'Mar',
		'Apr'	=> 'Apr',
		'May'	=> 'Mei',
		'Jun'	=> 'Jun',
		'Jul'	=> 'Jul',
		'Aug'	=> 'Agu',
		'Sep'	=> 'Sep',
		'Oct'	=> 'Okt',
		'Nov' 	=> 'Nov',
		'Dec'	=> 'Dec');

	$day = array(
		'Sunday' 	=> 'Minggu', 
		'Monday'	=> 'Senin',
		'Tuesday'	=> 'Selasa',
		'Wednesday'	=> 'Rabu',
		'Thursday'	=> 'Kamis',
		'Friday'	=> 'Jumat',
		'Saturday'	=> 'Sabtu');

	$day_short = array(
		'Sun'	=>	'Min',
		'Mon'	=>	'Sen',
		'Tue'	=>	'Sel',
		'Wed'	=>	'Rab',
		'Thu'	=>	'Kam',
		'Fri'	=>	'Jum',
		'Sat'	=>	'Sab');

	$date = strtotime((isset($date))? $date : date('Y-m-d H:m:i'));


	if ($format == 'F') {
		return isset($month[date($format, $date)]) ? $month[date($format, $date)] : $format;
	}elseif ($format == 'l') {
		return isset($day[date($format, $date)]) ? $day[date($format, $date)] : $format;
	}elseif ($format == 'D') {
		return isset($day_short[date($format, $date)]) ? $day_short[date($format, $date)] : $format;
	}elseif ($format == 'M') {
		return isset($month_short[date($format, $date)]) ? $month_short[date($format, $date)] : $format;
	}else{
		return $format;
	}
}

function time_range($date)
{
	$time_result = '';
	if (date('Y', strtotime($date)) == date('Y')) {
		$date_mail = (int)date('d', strtotime($date));
		$date_now = (int)date('d');
		$time = strtotime($date);
		$time_now = strtotime(date('Y-m-d H:m:i'));
		$temp = 12 * 60 * 60;
		$time_range = $time_now - $time;

		if ($time_range <= $temp) {
			$minute = (int)($time_range / 60);
			if ($time_range < 60) {
				$time_result = 'just now';
			}else if ($minute < 60) {
				$time_result = sprintf("%d minutes ago", $minute);
			}else{
				$hours = (int)($time_range / 3600);
				$time_result = sprintf("%d hours ago", $hours);
			}
		}else{
			if ($date_now > $date_mail) {
				$day = $date_now - $date_mail;
				if ($day == 1) {
					$time_result = 'Yesterday';
				}else if ($day == 2 || $day == 3 ) {
					$time_result = sprintf("%d days ago", $day);
				}else{
					$time_result = date('M d', strtotime($date));
				}
			}else{
				$hours = (int)($time_range / 3600);
				$time_result = sprintf("%d hours ago", $hours);
			}
		}
		//$time_result = date('M d', strtotime($date));		
	}else{
		$time_result = date('d/m/Y', strtotime($date));
	}

	return $time_result;
}