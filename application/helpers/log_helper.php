<?php
defined('BASEPATH') or exit('No direct script access allowed');

function addLog($jenis_aksi, $keterangan, $userid = null)
{
    $CI = get_instance();

    $tanggal = date('Y-m-d H:i:s');
    //untuk reverse proxy HTTP_X_FORWARDED_FOR
    $ip = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER['REMOTE_ADDR'];

    $userid = ($userid == null
        ? (decrypt($CI->session->userdata('user_id')) != 0
            ? decrypt($CI->session->userdata('user_id')) :
            decrypt($CI->session->userdata('auth_login'))) : $userid);

        $dataInsert = array(
            'jenislog' => $jenis_aksi,
            'userid' => $userid,
            'keterangan' => $keterangan,
            'tanggal' => $tanggal,
            'status' => 1,
        );

    $CI->Md_log->addLog($dataInsert);
}

function dd($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    die;
}
