<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Get indonesia date for CodeIgniter 3.x
 *
 * @package     CodeIgniter
 * @category    Helper
	
 */
function hariini()
{
	$hariini = date("N");
	if ($hariini == 1) {
		return "Senin";
	}
	if ($hariini == 2) {
		return "Selasa";
	}
	if ($hariini == 3) {
		return "Rabu";
	}
	if ($hariini == 4) {
		return "Kamis";
	}
	if ($hariini == 5) {
		return "Jumat";
	}
	if ($hariini == 6) {
		return "Sabtu";
	}
	if ($hariini == 7) {
		return "Minggu";
	}
}

function getharifrom($date)
{
	$hari = date("N", strtotime($date));
	if ($hari == 1) {
		return "Senin";
	}
	if ($hari == 2) {
		return "Selasa";
	}
	if ($hari == 3) {
		return "Rabu";
	}
	if ($hari == 4) {
		return "Kamis";
	}
	if ($hari == 5) {
		return "Jumat";
	}
	if ($hari == 6) {
		return "Sabtu";
	}
	if ($hari == 7) {
		return "Minggu";
	}
}

function getBulanFrom($bulan)
{
	if ($bulan == 1 || $bulan == "1") {
		return "Januari";
	}
	if ($bulan == 2 || $bulan == "2") {
		return "Februari";
	}
	if ($bulan == 3 || $bulan == "3") {
		return "Maret";
	}
	if ($bulan == 4 || $bulan == "4") {
		return "April";
	}
	if ($bulan == 5 || $bulan == "5") {
		return "Mei";
	}
	if ($bulan == 6 || $bulan == "6") {
		return "Juni";
	}
	if ($bulan == 7 || $bulan == "7") {
		return "Juli";
	}
	if ($bulan == 8 || $bulan == "8") {
		return "Agustus";
	}
	if ($bulan == 9 || $bulan == "9") {
		return "September";
	}
	if ($bulan == 10 || $bulan == "10") {
		return "Oktober";
	}
	if ($bulan == 11 || $bulan == "11") {
		return "November";
	}
	if ($bulan == 12 || $bulan == "12") {
		return "Desember";
	}
}

function getMonthFrom($bulan)
{
	if ($bulan == 1 || $bulan == "1") {
		return "Jan";
	}
	if ($bulan == 2 || $bulan == "2") {
		return "Feb";
	}
	if ($bulan == 3 || $bulan == "3") {
		return "Mar";
	}
	if ($bulan == 4 || $bulan == "4") {
		return "Apr";
	}
	if ($bulan == 5 || $bulan == "5") {
		return "Mei";
	}
	if ($bulan == 6 || $bulan == "6") {
		return "Jun";
	}
	if ($bulan == 7 || $bulan == "7") {
		return "Jul";
	}
	if ($bulan == 8 || $bulan == "8") {
		return "Ags";
	}
	if ($bulan == 9 || $bulan == "9") {
		return "Sep";
	}
	if ($bulan == 10 || $bulan == "10") {
		return "Okt";
	}
	if ($bulan == 11 || $bulan == "11") {
		return "Nov";
	}
	if ($bulan == 12 || $bulan == "12") {
		return "Des";
	}
}

function tanggal($DATE)
{
	$date = date('d', strtotime($DATE));
	$month = date('m', strtotime($DATE));
	$year = date('Y', strtotime($DATE));

	if ($month == 1 || $month == "1") {
		$month = "Jan";
	}
	if ($month == 2 || $month == "2") {
		$month = "Feb";
	}
	if ($month == 3 || $month == "3") {
		$month = "Mar";
	}
	if ($month == 4 || $month == "4") {
		$month = "Apr";
	}
	if ($month == 5 || $month == "5") {
		$month = "Mei";
	}
	if ($month == 6 || $month == "6") {
		$month = "Jun";
	}
	if ($month == 7 || $month == "7") {
		$month = "Jul";
	}
	if ($month == 8 || $month == "8") {
		$month = "Agus";
	}
	if ($month == 9 || $month == "9") {
		$month = "Sep";
	}
	if ($month == 10 || $month == "10") {
		$month = "Okt";
	}
	if ($month == 11 || $month == "11") {
		$month = "Nov";
	}
	if ($month == 12 || $month == "12") {
		$month = "Des";
	}

	return $date . '-' . $month . '-' . $year;
}

function tanggal2($DATE)
{
	$date = date('d', strtotime($DATE));
	$month = date('m', strtotime($DATE));
	$year = date('Y', strtotime($DATE));

	if ($month == 1 || $month == "1") {
		$month = "Jan";
	}
	if ($month == 2 || $month == "2") {
		$month = "Feb";
	}
	if ($month == 3 || $month == "3") {
		$month = "Mar";
	}
	if ($month == 4 || $month == "4") {
		$month = "Apr";
	}
	if ($month == 5 || $month == "5") {
		$month = "Mei";
	}
	if ($month == 6 || $month == "6") {
		$month = "Jun";
	}
	if ($month == 7 || $month == "7") {
		$month = "Jul";
	}
	if ($month == 8 || $month == "8") {
		$month = "Agus";
	}
	if ($month == 9 || $month == "9") {
		$month = "Sep";
	}
	if ($month == 10 || $month == "10") {
		$month = "Okt";
	}
	if ($month == 11 || $month == "11") {
		$month = "Nov";
	}
	if ($month == 12 || $month == "12") {
		$month = "Des";
	}

	return $date . ' ' . $month . ' ' . $year;
}

function tanggal3($DATE)
{
	$date = date('d', strtotime($DATE));
	$month = date('m', strtotime($DATE));
	$year = date('Y', strtotime($DATE));

	return $date . '/' . $month . '/' . $year;
}


function tanggalpanjang($DATE)
{
	$date = date('d', strtotime($DATE));
	$month = date('m', strtotime($DATE));
	$year = date('Y', strtotime($DATE));

	if ($month == 1 || $month == "1") {
		$month = "Januari";
	}
	if ($month == 2 || $month == "2") {
		$month = "Februari";
	}
	if ($month == 3 || $month == "3") {
		$month = "Maret";
	}
	if ($month == 4 || $month == "4") {
		$month = "April";
	}
	if ($month == 5 || $month == "5") {
		$month = "Mei";
	}
	if ($month == 6 || $month == "6") {
		$month = "Juni";
	}
	if ($month == 7 || $month == "7") {
		$month = "Juli";
	}
	if ($month == 8 || $month == "8") {
		$month = "Agustus";
	}
	if ($month == 9 || $month == "9") {
		$month = "September";
	}
	if ($month == 10 || $month == "10") {
		$month = "Oktober";
	}
	if ($month == 11 || $month == "11") {
		$month = "November";
	}
	if ($month == 12 || $month == "12") {
		$month = "Desember";
	}

	return $date . ' ' . $month . ' ' . $year;
}

function getDuration($firs_date, $secon_date)
{
	if ($secon_date < $firs_date || $firs_date == "" || $secon_date == "") return null;

	$dteStart         = new DateTime($firs_date);
	$dteEnd           = new DateTime($secon_date);

	$interval         = date_diff($dteStart, $dteEnd);

	$DaysToSecconds   = $interval->format('%a') * ((60 * 60) * 24);
	$HoursToSeconds   = $interval->format('%H') * (60 * 60);
	$MinutesToSeconds = $interval->format('%I') * 60;
	$SecondsToSeconds = $interval->format('%S');

	$TotalSecond = $DaysToSecconds + $HoursToSeconds + $MinutesToSeconds + $SecondsToSeconds;

	$data = array(
		'tahun'      => $interval->format('%y'),
		'hari'       => $interval->d,
		'bulan'      => $interval->m,
		'total_hari' => $interval->format('%a'),
		'jam'        => $TotalSecond / 60 * 60,
		'menit'      => $TotalSecond / 60,
		'detik'      => $TotalSecond,
	);

	return (object) $data;
}

function tanggaldanwaktu($DATE)
{
	$date = date('d', strtotime($DATE));
	$month = date('m', strtotime($DATE));
	$year = date('Y', strtotime($DATE));
	$hour = date('H', strtotime($DATE));
	$minute = date('i', strtotime($DATE));
	$second = date('s', strtotime($DATE));

	if ($month == 1 || $month == "1") {
		$month = "Jan";
	}
	if ($month == 2 || $month == "2") {
		$month = "Feb";
	}
	if ($month == 3 || $month == "3") {
		$month = "Mar";
	}
	if ($month == 4 || $month == "4") {
		$month = "Apr";
	}
	if ($month == 5 || $month == "5") {
		$month = "Mei";
	}
	if ($month == 6 || $month == "6") {
		$month = "Jun";
	}
	if ($month == 7 || $month == "7") {
		$month = "Jul";
	}
	if ($month == 8 || $month == "8") {
		$month = "Agus";
	}
	if ($month == 9 || $month == "9") {
		$month = "Sep";
	}
	if ($month == 10 || $month == "10") {
		$month = "Okt";
	}
	if ($month == 11 || $month == "11") {
		$month = "Nov";
	}
	if ($month == 12 || $month == "12") {
		$month = "Des";
	}

	return $date . '-' . $month . '-' . $year . ' ' . $hour . ':' . $minute . ':' . $second;
}

function get_months_between_dates(string $start_date, string $end_date): ?int
{
	$startDate = $start_date instanceof Datetime ? $start_date : new DateTime($start_date);
	$endDate = $end_date instanceof Datetime ? $end_date : new DateTime($end_date);
	$interval = $startDate->diff($endDate);
	$months = ($interval->y * 12) + $interval->m;

	return $startDate > $endDate ? -$months : $months;
}
