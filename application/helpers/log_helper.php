<?php
defined('BASEPATH') or exit('No direct script access allowed');

function addLog($jenis_aksi, $keterangan, $keterangandetail, $info = null, $karyawanid = null, $ipPrivate = null)
{
    $CI = get_instance();

    $tanggal = date('Y-m-d H:i:s');
    //untuk reverse proxy HTTP_X_FORWARDED_FOR
    $ip = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER['REMOTE_ADDR'];

    $karyawanid = ($karyawanid == null
        ? (decrypt($CI->session->userdata('karyawan_id')) != 0
            ? decrypt($CI->session->userdata('karyawan_id')) :
            decrypt($CI->session->userdata('auth_login'))) : $karyawanid);

    $info = $info ? json_encode($info) : null;

    if (
        $ipPrivate != null
        &&
        !filter_var(
            $ipPrivate,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE |  FILTER_FLAG_NO_RES_RANGE
        )
    ) {
        $dataInsert = array(
            'jenislog' => $jenis_aksi,
            'karyawanid' => $karyawanid,
            'keterangan' => $keterangan,
            'tanggal' => $tanggal,
            'ipaddr' => $ip,
            'status' => 1,
            'keterangandetail' => $keterangandetail,
            'ipaddrprivate'  => $ipPrivate
        );
    } else {
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
    }

    $CI->Md_log->addLog($dataInsert);
}

function dd($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    die;
}
