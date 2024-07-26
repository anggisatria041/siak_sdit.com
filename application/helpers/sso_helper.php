<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *  Helper Single Sign On Use JWT
 */
    
use Firebase\JWT\JWT;

function secretkey()
{
    $secret_key = "12e12edzfadqwd2re1e1e12e2sddfwd123";

    return $secret_key;
}

function difftimetoday()
{
    $tglnow = date('Y-m-d H:i:s');
    $datenow = date('Y-m-d');
    $date24hour = $datenow . ' 23:00:00';

    $DT_tglnow = new DateTime($tglnow);
    $DT_date24hour = new DateTime($date24hour);

    $diff = date_diff($DT_tglnow, $DT_date24hour);

    //$detik = ;
    $detik = ((float) $diff->h * 3600) + ((float) $diff->i * 60) + ((float) $diff->s);

    return $detik;
}

function checkCookie()
{
    $CI = get_instance();
    $CI->load->model('Md_siswa');
    $CI->load->helper('cookie');
    $CI->load->library('encryption');
    $useragentCI = $CI->encryption->decrypt(CekUserAgent());
    $secretkey = secretkey();
    if ($CI->input->cookie('X-CEKLOGIN-SESSION')) {

        try {
            $jwt = $CI->input->cookie('X-CEKLOGIN-SESSION');
            $payload = JWT::decode($jwt, $secretkey, ['HS256']);
            if (($payload->auth_login != null) && ($payload->sessionid != null)) {
                $dt = $CI->Md_karyawan->getKaryawanById($CI->encryption->decrypt($payload->auth_login));
                if ($dt) {
                    if (($dt->sessionid != null) && ($CI->encryption->decrypt($payload->sessionid) == $dt->sessionid) &&
                        ($CI->encryption->decrypt($payload->user_agent) == $useragentCI)
                    ) {
                        return true;
                    } else {
                        deleteCookie();
                        return false;
                    }
                } else {
                    deleteCookie();
                    return false;
                }
            } else {
                deleteCookie();
                return false;
            }
        } catch (Exception $e) {
            deleteCookie();
            return false;
        }
    } else {
        return false;
    }
}

function GetClientMac()
{

    $_IP_SERVER = $_SERVER['SERVER_ADDR'];
    $_IP_ADDRESS = $_SERVER['REMOTE_ADDR'];
    if ($_IP_ADDRESS == $_IP_SERVER) {
        ob_start();
        system('ipconfig /all');
        $_PERINTAH  = ob_get_contents();
        ob_clean();
        $_PECAH = strpos($_PERINTAH, "IPv4 Address");

        $_HASIL = substr($_PERINTAH, ($_PECAH + 36), 17);
        // var_dump($_HASIL);
        // die;
    } else {
        $_PERINTAH = "arp -a $_IP_ADDRESS";
        ob_start();
        system($_PERINTAH);
        $_HASIL = ob_get_contents();
        ob_clean();
        $_PECAH = strstr($_HASIL, $_IP_ADDRESS);
        $_PECAH_STRING = explode($_IP_ADDRESS, str_replace(" ", "", $_PECAH));
        $_HASIL = substr($_PECAH_STRING[1], 0, 17);
    }
    $random = md5($_HASIL);
    $nilai = preg_replace("/[^0-9]/", "", $random);
    $macaddr = substr($nilai, 0, 15);

    return $_HASIL;
}

function CekUserAgent()
{
    $CI = get_instance();
    if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){ //cek apakah IP melewati proxy

        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $ip = $CI->input->ip_address(); //jika IP client tidak tersedia maka akan mengambil remote address
    }
    
    $CI->load->library('user_agent');
    $CI->load->library('encryption');

   
    $versiBrwoser = $CI->agent->version();
    $namaBrowser = $CI->agent->browser();
    $platform = $CI->agent->platform();
    //$CI->encryption->encrypt()

    return $CI->encryption->encrypt($ip . '-' . $namaBrowser . '-' . $versiBrwoser . '-' . $platform);
}

function createCookie($karyawanid=null){

    $CI = get_instance();
    $CI->load->library('session');
    $karyawanid = $CI->session->userdata('auth_login') != null ? decrypt($CI->session->userdata('auth_login')) : $karyawanid;

    if($karyawanid){
        $secretkey = secretkey();
        $randInt = rand();
        $CI->Md_karyawan->updateKaryawanById($karyawanid, ['sessionid' => $randInt]);
        $payload = [
            "sessionid" => $CI->encryption->encrypt($randInt),
            "auth_login" => $CI->encryption->encrypt($karyawanid),
            "user_agent" => CekUserAgent()
        ];
        $detik = difftimetoday();
        $jwt = JWT::encode($payload, $secretkey, 'HS256');
       
        $domain = $CI->db->port != '1999' ? '' : 'vadhana.co.id';
        $cookie = array('name'   => 'X-CEKLOGIN-SESSION','value'  => $jwt,'expire' => $detik,'domain' => $domain);
        $CI->input->set_cookie($cookie);
    }
}

function getPayload(){
    $CI = get_instance();
    $secretkey = secretkey();
    $jwt = $CI->input->cookie('X-CEKLOGIN-SESSION');
    $payload = JWT::decode($jwt, $secretkey, ['HS256']);
    return $payload;
}

function deleteCookie(){
    $CI = get_instance();
    $CI->load->helper('cookie');
    $domain = $CI->db->port != '1999' ? '' : '.vadhana.co.id';
    delete_cookie('X-CEKLOGIN-SESSION', $domain, '/', '');
}