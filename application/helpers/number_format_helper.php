<?php
defined('BASEPATH') OR exit('No direct script access allowed');


	function format_angka($value='',$decimal='')
	{
		$value = floatval($value);
		return $hasil = number_format($value,$decimal,',','.');
	}

	function format_qty($value='', $decimal=''){
		//$value = floatval($value);
		$arrayval = explode('.', $value);

		$hasil = number_format($value,$decimal,',','.');

		$arrayhasil = explode(',', $hasil);
		if($arrayhasil[1] == 0){
			return $arrayhasil[0];
		}else{
			//$hasil = float($hasil) + 0;
			return $arrayhasil[0] .','.$arrayval[1];
		}
	}

	function format_angka_without_round($number='', $decimal=''){
		return $number;
	}

/* End of file number_format_helper.php */
/* Location: ./application/helpers/number_format_helper.php */