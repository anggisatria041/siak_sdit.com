<?php

use Firebase\JWT\JWT;

function getRomawi($bln)
{
    switch ($bln) {
        case 1:
            return "I";
            break;
        case 2:
            return "II";
            break;
        case 3:
            return "III";
            break;
        case 4:
            return "IV";
            break;
        case 5:
            return "V";
            break;
        case 6:
            return "VI";
            break;
        case 7:
            return "VII";
            break;
        case 8:
            return "VIII";
            break;
        case 9:
            return "IX";
            break;
        case 10:
            return "X";
            break;
        case 11:
            return "XI";
            break;
        case 12:
            return "XII";
            break;
    }
}

function getJumlahMenitBySelisihTanggal($date)
{

    $CI = get_instance();
    $CI->load->model('Md_harilibur');

   /* -------------------------------------------
        Aturan :
        1. Hari Senin s/d Kamis
           a. Masuk : 08:00:00
           b. mulai istirahat : 12:00:00
           c. selesai istirahat : 13:00:00
           d. Pulang : 17:00:00
        
        2. Jumat
           a. Masuk : 08:00:00
           b. mulai istirahat : 12:00:00
           c. selesai istirahat : 13:30:00
           d. Pulang : 17:30:00
    --------------------------------------------- */

    # Tgl awal (doing/request)
    $datetimeDoing  = new DateTime($date);
    $dayDoing       = date('D', strtotime($date));
    $dateDoing      = $datetimeDoing->format('Y-m-d');
    $timeDoing      = $datetimeDoing->format('H:i:s');

    # Tanggal dan jam hari ini
    // $today       = '2021-11-17 18:00:00'; //untuk testing
    $today = date('Y-m-d H:i:s');
    $datetimeNow = new DateTime($today);
    $DayNow      = date('D');
    $dateNow     = $datetimeNow->format('Y-m-d');
    $timeNow     = $datetimeNow->format('H:i:s');

    $dt_Masuk               = new DateTime($dateNow . ' 08:00:00');
    $dt_Pulang              = $dayDoing != 'Fri' ? new DateTime($dateDoing . ' 17:00:00') : new DateTime($dateDoing. ' 17:30:00');
    $dt_MulaiIstirahat      = new DateTime($dateDoing . ' 12:00:00');
    $dt_SelesaiIstirahat    = $dayDoing != 'Fri' ? new DateTime($dateDoing . ' 13:00:00') : new DateTime($dateDoing . ' 13:30:00');

    $JamMasuk = $dt_Masuk->format('H:i:s');
    $JamPulang = $dt_Pulang->format('H:i:s');
    $JamMulaiIstirahat = $dt_MulaiIstirahat->format('H:i:s');
    $JamSelesaiIstirahat = $dt_SelesaiIstirahat->format('H:i:s');

    # calculate duration with datetime
    $diff = date_diff($datetimeDoing, $datetimeNow);

    # calculate duration with just date
    $dt_now = date_create($dateNow);
    $dt_doing = date_create($dateDoing); 
    $dateDiff = date_diff($dt_doing,$dt_now);

    $dateDiff = $dateDiff->days == 0 ? $diff->days : $dateDiff->days;
    $duration = 0;

    if($dateDiff == 0){
        # Jika Request Doing dan Submit Completion di hari yang sama
        $Libur = $CI->Md_harilibur->getHariLiburByTgl($dateNow);

        # jika hari libur / lembur maka waktu tidak dihitung
        if ($DayNow == 'Sat' || $DayNow == 'Sun' || $Libur != null) {
            $duration += 0;
            $getDuration = False;
        }

        # selain waktu libur
        if(!isset($getDuration)){
            
            # Jam request/doing < 08:00:00
            if(strtotime($timeDoing) < strtotime($JamMasuk)){

                if(strtotime($timeNow) < strtotime($JamMasuk)){
                    $duration += 0;
                }else if (strtotime($timeNow) <= strtotime($JamMulaiIstirahat)){
                    $getDiff = date_diff($dt_Masuk, $datetimeNow);
                    $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                }else if (strtotime($timeNow) <= strtotime($JamSelesaiIstirahat)){
                    $getDiff = date_diff($dt_Masuk, $dt_MulaiIstirahat);
                    $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                }else if (strtotime($timeNow) >= strtotime($JamSelesaiIstirahat) && strtotime($timeNow) <= strtotime($JamPulang)){
                    # jam 08:00 - jam 12.00
                    $getDiff = date_diff($dt_Masuk, $dt_MulaiIstirahat);
                    $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    # jam 13:00/13.30 - jam sekarang
                    $getDiff = date_diff($dt_SelesaiIstirahat, $datetimeNow);
                    $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                }else{
                    # jam 08:00 - jam 12.00
                    $getDiff = date_diff($dt_Masuk, $dt_MulaiIstirahat);
                    $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    # jam 13:00/13.30 - jam pulang
                    $getDiff = date_diff($dt_SelesaiIstirahat, $dt_Pulang);
                    $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                }

            }else{ # Jam request/doing > 08:00:00
                
                # Jam request/doing diwaktu  >> ISTIRAHAT << 12:00:00 s.d jam selesai istirahat (13:00:00 atau 13:30:00)
                if(strtotime($timeDoing) >= strtotime($JamMulaiIstirahat) && strtotime($timeDoing) <= strtotime($JamSelesaiIstirahat)){

                    if(strtotime($timeNow) <= strtotime($JamSelesaiIstirahat)){
                        $duration += 0;
                    }else if(strtotime($timeNow) <= strtotime($JamPulang)){
                        $getDiff = date_diff($dt_SelesaiIstirahat, $datetimeNow);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    }else{
                        $getDiff = date_diff($dt_SelesaiIstirahat, $dt_Pulang);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    }

                }else{ # Jam request/doing diluar jam Istirahat

                    if(strtotime($timeDoing) >= strtotime($JamPulang) && strtotime($timeNow) >= strtotime($JamPulang)){
                        $duration += 0;
                    }else if(strtotime($timeNow) <= strtotime($JamMulaiIstirahat)){
                        $getDiff = date_diff($datetimeDoing, $datetimeNow);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    }else if (strtotime($timeNow) <= strtotime($JamSelesaiIstirahat)){
                        $getDiff = date_diff($datetimeDoing, $dt_MulaiIstirahat);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    } else {

                        # jam request yang > 13.00 / 13.30 - jam sekarang < 17.00 / 17.30
                        if(strtotime($timeDoing) >= strtotime($JamSelesaiIstirahat) && strtotime($timeNow) <= strtotime($JamPulang)){
                            $getDiff = date_diff($datetimeDoing, $datetimeNow);
                            $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));

                        }else if (strtotime($timeDoing) >= strtotime($JamSelesaiIstirahat) && strtotime($timeNow) > strtotime($JamPulang)){
                            $getDiff = date_diff($datetimeDoing, $dt_Pulang);
                            $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));

                        }else{
                            
                            # jam request yang > 08:00 - jam 12.00
                            $getDiff = date_diff($datetimeDoing, $dt_MulaiIstirahat);
                            $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                            # jam selesai istirahat - jam terakhir
                            $getDiff = date_diff($dt_SelesaiIstirahat, $dt_Pulang);
                            $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                        }
                        
                    }
                }
            }
        }
    }else{
        # pengerjaan dilakukan di tanggal yang berbeda dari tanggal request/doing
        for ($i = 0; $i <= $dateDiff; $i++) {
            
            # cek hari libur
            $dateDoing = $i == 0 ? $dateDoing : date('Y-m-d', strtotime($dateDoing. " + 1 days"));
            $dayDoing  = date('D', strtotime($dateDoing));

            $dt_Masuk               = new DateTime($dateDoing . ' 08:00:00');
            $dt_Pulang              = $dayDoing != 'Fri' ? new DateTime($dateDoing . ' 17:00:00') : new DateTime($dateDoing. ' 17:30:00');
            $dt_MulaiIstirahat      = new DateTime($dateDoing . ' 12:00:00');
            $dt_SelesaiIstirahat    = $dayDoing != 'Fri' ? new DateTime($dateDoing . ' 13:00:00') : new DateTime($dateDoing . ' 13:30:00');

            $Libur = $CI->Md_harilibur->getHariLiburByTgl($dateDoing);
            if ($dayDoing == 'Sat' || $dayDoing == 'Sun' || $Libur != null) { // hari libur total tiak dihitung
                $duration += 0;
            }else{
                # hari pertama request/doing
                if($i == 0 ){
                    # Jam request/doing < 08:00:00
                    if(strtotime($timeDoing) < strtotime($JamMasuk)){

                        # jam 08:00 - jam 12.00
                        $getDiff = date_diff($dt_Masuk, $dt_MulaiIstirahat);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                        # jam 13:00/13.30 - jam pulang
                        $getDiff = date_diff($dt_SelesaiIstirahat, $dt_Pulang);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    
                    }else{ # Jam request/doing > 08:00:00
                        
                        if(strtotime($timeDoing) >= strtotime($JamMulaiIstirahat) && strtotime($timeDoing) <= strtotime($JamSelesaiIstirahat)){
                            $getDiff = date_diff($dt_SelesaiIstirahat, $dt_Pulang);
                            $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));

                        }else if (strtotime($timeDoing) < strtotime($JamMulaiIstirahat)){
                            # jam request yang > 08:00 - jam 12.00
                            $getDiff = date_diff($datetimeDoing, $dt_MulaiIstirahat);
                            $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                            # jam selesai istirahat - jam terakhir
                            $getDiff = date_diff($dt_SelesaiIstirahat, $dt_Pulang);
                            $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                        }else{
                            # jam selesai istirahat - jam terakhir
                            $getDiff = date_diff($datetimeDoing, $dt_Pulang);
                            $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                        }
                    }

                } else if ($i == $dateDiff){ # hari terakhir request/doing

                    if(strtotime($timeNow) <= strtotime($JamMasuk)){
                        $duration += 0;
                    }else if (strtotime($timeNow) >= strtotime($JamMulaiIstirahat) && strtotime($timeNow) <= strtotime($JamSelesaiIstirahat)){
                        $getDiff = date_diff($dt_Masuk, $dt_MulaiIstirahat);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    }else if (strtotime($timeNow) < strtotime($JamMulaiIstirahat)){
                        $getDiff = date_diff($dt_Masuk, $datetimeNow);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    }else if (strtotime($timeNow) > strtotime($JamPulang)){
                        # jam request yang > 08:00 - jam 12.00
                        $getDiff = date_diff($dt_Masuk, $dt_MulaiIstirahat);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                        # jam selesai istirahat - jam terakhir
                        $getDiff = date_diff($dt_SelesaiIstirahat, $dt_Pulang);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    }else{
                        # jam request yang > 08:00 - jam 12.00
                        $getDiff = date_diff($dt_Masuk, $dt_MulaiIstirahat);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                        # jam selesai istirahat - jam terakhir
                        $getDiff = date_diff($dt_SelesaiIstirahat, $datetimeNow);
                        $duration += (((float) $getDiff->h * 60) + ((float) $getDiff->i) + ((float) $getDiff->s / 60));
                    }

                } else {
                    # hari yang dilalui
                    $duration += 480;
                }
            }
        }
    }

    return $duration;
}

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
    $CI->load->model('Md_karyawan');
    $CI->load->helper('cookie');
    $CI->load->library('encryption');
    $useragentCI = $CI->encryption->decrypt(CekUserAgent());
    $secretkey = secretkey();
    if ($CI->input->cookie('X-CEKLOGIN-SESSION')) {

        try {
            $jwt = $CI->input->cookie('X-CEKLOGIN-SESSION');
            $payload = JWT::decode($jwt, $secretkey, ['HS256']);
            if (($payload->auth_login != null) && ($payload->sessionid != null) && $CI->encryption->decrypt($payload->auth_login) != false) {
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
        $CI->Md_karyawan->updateKaryawan($karyawanid, ['sessionid' => $randInt]);
        $payload = [
            "sessionid" => $CI->encryption->encrypt($randInt),
            "auth_login" => $CI->encryption->encrypt($karyawanid),
            "user_agent" => CekUserAgent()
        ];
        $detik = difftimetoday();
        $jwt = JWT::encode($payload, $secretkey, 'HS256');
       
        $domain = $CI->db->hostname == 'localhost' ? '' : 'vadhana.co.id';
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
    $domain = $CI->db->hostname == 'localhost' ? '' : '.vadhana.co.id';
    delete_cookie('X-CEKLOGIN-SESSION', $domain, '/', '');
}