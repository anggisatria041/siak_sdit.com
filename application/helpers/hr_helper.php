<?php
defined('BASEPATH') or exit('No direct script access allowed');

function getLoginasList()
{
    $CI = get_instance();
    $CI->load->library('session');
    $dt_pengguna = $CI->Md_pengguna->getPenggunaByDivisi(decrypt($CI->session->userdata('karyawan_id')), 'Transport');

    $arr_pengguna = array();
    //if (count($dt_pengguna) > 0) {

    foreach ($dt_pengguna as $arr) {
        $array = array(
            'penggunaid' => $arr->penggunaid,
            'hakaksesname' => $arr->hakaksesname,
        );
        array_push($arr_pengguna, (object)$array);
    }
    //}

    return $arr_pengguna;
}
