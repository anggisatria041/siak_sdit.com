<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Fungsi untuk memotong text menjadi "Ini adalah text yang..."
function truncate($string, $length = 100, $append = "...")
{
	$string = trim($string);

	if (strlen($string) > $length) {
		$string = wordwrap($string, $length);
		$string = explode("\n", $string, 2);
		$string = $string[0] . $append;
	}

	return $string;
}

function format_angka($value = 0, $decimal = 0)
{
	$value = floatval($value);
	$angka = 0;
	if ($value > 0) {
		$angka = number_format($value, $decimal, ',', '.');
	} else if ($value < 0) {
		$angka = number_format($value, $decimal, ',', '.');
	}
	return $angka;
}

    function format_angka_withpoint($data,$digit,$p1='x',$p2='x'){
        if(count(str_split($data))>1){

            if($p1=='x' && $p2=='x'){
                $angka = number_format($data,$digit);
            }else{
                $angka = number_format($data, $digit, $p1,$p2);
            }

            $arr = str_split($angka);
            if(count($arr)==4){
                if($arr[1]=='.' && $arr[2]=='0' && $arr[3]=='0'){
                    $angka=0.01;
                }
            }
        }else{
            $angka=$data;
        }

        return $angka;
    }

function format_qty($value = '', $decimal = '')
{
	//$value = floatval($value);
	$arrayval = explode('.', $value);

	$hasil = number_format($value, $decimal, ',', '.');

	$arrayhasil = explode(',', $hasil);
	if ($arrayhasil[1] == 0) {
		return $arrayhasil[0];
	} else {
		//$hasil = float($hasil) + 0;
		return $arrayhasil[0] . ',' . $arrayval[1];
	}
}

function format_angka_without_round($number = '', $decimal = '')
{
	return $number;
}

function number_format_short($n)
{
	// first strip any formatting;
	$n = (0 + str_replace(",", "", $n));
	// is this a number?
	if (!is_numeric($n)) return false;
	// now filter it;
	if ($n > 1000000000000) return round(($n / 1000000000000), 0) . 'T';
	elseif ($n > 1000000000) return round(($n / 1000000000), 0) . 'M';
	elseif ($n > 1000000) return round(($n / 1000000), 0) . 'Jt';
	elseif ($n > 1000) return round(($n / 1000), 0) . 'Rb';

	return number_format($n);
}

function coaformat($number)
{
	if ($number == '') {
		return '';
	}

	$a = substr($number, 0, 1);
	$b = substr($number, 1, 1);
	$c = substr($number, 2, 1);
	$d = substr($number, 3, 2);
	$e = substr($number, 5, 2);
	return $a . "." . $b . "." . $c . "." . $d . "." . $e;
}

/* End of file number_format_helper.php */
/* Location: ./application/helpers/number_format_helper.php */
