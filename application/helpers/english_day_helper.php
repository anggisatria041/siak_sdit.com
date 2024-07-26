<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	 * Get English date for CodeIgniter 3.x
	 *
	 * @package     CodeIgniter
	 * @category    Helper
	
	 */

    function dateformat($DATE){
		$date = date('d',strtotime($DATE));
		$month = date('m',strtotime($DATE));
		$year = date('Y',strtotime($DATE));

		if ($month == 1 || $month == "1"){
			$month = "Jan";
		}
		if ($month == 2 || $month == "2"){
			$month = "Feb";
		}
		if ($month == 3 || $month == "3"){
			$month = "Mar";
		}
		if ($month == 4 || $month == "4"){
			$month = "Apr";
		}
		if ($month == 5 || $month == "5"){
			$month = "May";
		}
		if ($month == 6 || $month == "6"){
			$month = "Jun";
		}
		if ($month == 7 || $month == "7"){
			$month = "Jul";
		}
		if ($month == 8 || $month == "8"){
			$month = "Aug";
		}
		if ($month == 9 || $month == "9"){
			$month = "Sep";
		}
		if ($month == 10 || $month == "10"){
			$month = "Oct";
		}
		if ($month == 11 || $month == "11"){
			$month = "Nov";
		}
		if ($month == 12 || $month == "12"){
			$month = "Dec";
		}

		return $date.'-'.$month.'-'.$year;
	}
	
	function dateTime($DATE){
		$date = date('d',strtotime($DATE));
		$month = date('m',strtotime($DATE));
		$year = date('Y',strtotime($DATE));

		$hours = date('h',strtotime($DATE));
		$minutes = date('i',strtotime($DATE));
		$second = date('s',strtotime($DATE));

		if ($month == 1 || $month == "1"){
			$month = "Jan";
		}
		if ($month == 2 || $month == "2"){
			$month = "Feb";
		}
		if ($month == 3 || $month == "3"){
			$month = "Mar";
		}
		if ($month == 4 || $month == "4"){
			$month = "Apr";
		}
		if ($month == 5 || $month == "5"){
			$month = "May";
		}
		if ($month == 6 || $month == "6"){
			$month = "Jun";
		}
		if ($month == 7 || $month == "7"){
			$month = "Jul";
		}
		if ($month == 8 || $month == "8"){
			$month = "Aug";
		}
		if ($month == 9 || $month == "9"){
			$month = "Sep";
		}
		if ($month == 10 || $month == "10"){
			$month = "Oct";
		}
		if ($month == 11 || $month == "11"){
			$month = "Nov";
		}
		if ($month == 12 || $month == "12"){
			$month = "Dec";
		}

		return $date.'-'.$month.'-'.$year.' '.$hours.':'.$minutes ;
    }

	function getNameMonth($bulan){
		if ($bulan == 1 || $bulan == "1"){
			return "JAN";
		}
		if ($bulan == 2 || $bulan == "2"){
			return "FEB";
		}
		if ($bulan == 3 || $bulan == "3"){
			return "MAR";
		}
		if ($bulan == 4 || $bulan == "4"){
			return "APR";
		}
		if ($bulan == 5 || $bulan == "5"){
			return "MAR";
		}
		if ($bulan == 6 || $bulan == "6"){
			return "JUN";
		}
		if ($bulan == 7 || $bulan == "7"){
			return "JUL";
		}
		if ($bulan == 8 || $bulan == "8"){
			return "AUG";
		}
		if ($bulan == 9 || $bulan == "9"){
			return "SEP";
		}
		if ($bulan == 10 || $bulan == "10"){
			return "OCT";
		}
		if ($bulan == 11 || $bulan == "11"){
			return "NOV";
		}
		if ($bulan == 12 || $bulan == "12"){
			return "DEC";
		}
	}

    
?>