<?php
defined('BASEPATH') or exit('No direct script access allowed');

function addLog($jenis_aksi, $keterangan, $keterangandetail)
{
    $CI = get_instance();

    $karyawan_id = NULL;
    $tanggal = date('Y-m-d H:i:s');
    $ip = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER['REMOTE_ADDR'];

    $karyawan_id = decrypt($CI->session->userdata('karyawan_id'));
    $dataInsert = array(
        'jenis_log' => $jenis_aksi,
        'karyawanid' => $karyawan_id,
        'keterangan' => $keterangan,
        'tanggal' => $tanggal,
        'status' => 1,
        'keterangan_detail' => $keterangandetail,
        'ipaddr' => $ip
    );

    $CI->Md_log->addLog($dataInsert);
}

function addLogSCM($jenis_aksi, $keterangan, $keterangandetail, $info = null, $karyawanid = null)
{
    $CI = get_instance();

    $tanggal = date('Y-m-d H:i:s');
    //untuk reverse proxy HTTP_X_FORWARDED_FOR
    $ip = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER['REMOTE_ADDR'];

    $karyawanid = ($karyawanid == null ? decrypt($CI->session->userdata('karyawan_id')) : $karyawanid);

    $info = $info ? json_encode($info) : null;

    $dataInsert = array(
        'jenislog' => $jenis_aksi,
        'karyawanid' => $karyawanid,
        'keterangan' => $keterangan,
        'tanggal' => $tanggal,
        'ipaddr' => $ip,
        'status' => 1,
        'keterangandetail' => $keterangandetail,
        'info'  => $info
    );

    $CI->Md_log->addLogSCM($dataInsert);
}

function dd($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
    die;
}
