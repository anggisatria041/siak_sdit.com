<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function get_token($karyawanid, $ip, $penggunaid) {
    $ci = get_instance();
    $token_kotor = $ci->encryption->encrypt($karyawanid);
    $token_bersih = strtr($token_kotor, "/+", "-_");
    $query = $ci->db->get_where("tokenmobile", ["karyawanid" => $karyawanid, "penggunaid" => $penggunaid]);
    $data_token = ["mytoken" => $token_bersih,
                "karyawanid" => $karyawanid,
                "penggunaid" => $penggunaid,
                "ip" => $ip];
    if ($query->num_rows() > 0) {
        $ci->db->where('penggunaid', $penggunaid)->update("tokenmobile", $data_token);
    } else {
        $ci->db->insert("tokenmobile", $data_token);
    }

    return $token_bersih;
}

function update_tokenfcm($karyawanid, $token) {
    $ci = get_instance();
    $data_token = ["tokenfcb" => $token];
    $ci->db->where('karyawanid', $karyawanid)->update("tokenmobile", $data_token);

    return TRUE;
}

function test_token($token_bersih) {
    $ci = get_instance();
    $token_kotor = strtr($token_bersih, "-_", "/+");
    $karyawanid = $ci->encryption->decrypt($token_kotor);
    if ($karyawanid == "" || $karyawanid == null) {
        $res = ['status' => 0, "pesan" => "Gagal Token Tidak valid"];
    } else {
        $query = $ci->db->query("SELECT k.karyawanid,k.nama from hrd.tokenmobile t 
    JOIN hrd.karyawan k on t.karyawanid=k.karyawanid and k.statuskaryawan = 'Aktif' 
    where t.karyawanid=" . $karyawanid . " AND t.mytoken='" . $token_bersih . "'");
        if ($query->num_rows() > 0) {
            $res = ["status" => 1, "data" => $query->row_array(), "pesan" => "Gagal Token valid"];
        } else {
            $res = ['status' => 0, "pesan" => "Gagal Token Tidak valid"];
        }
    }
    return $res;
}
