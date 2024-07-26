<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function addmailbox($param1, $param2, $param3, $param4, $param5) {
    $CI = get_instance();
    
    $dataInsertMail = array();
    
    if(!is_array($param1)){
        $to = array($param1);
        $perusahaan = array($param2);
        $subjek = array($param3);
        $isi = array($param4);
        $aksi = array($param5);
    }else{
        $to = $param1;
        $perusahaan = $param2;
        $subjek = $param3;
        $isi = $param4;
        $aksi = $param5;
    }
    
    $i = 0;
    foreach($to as $row){
        $dt_perusahaan = $perusahaan[$i] ? $CI->Md_perusahaan->getPerusahaanById($perusahaan[$i]) : null;
        if($dt_perusahaan){
            if($dt_perusahaan->kodeperusahaan != "MT"){
                $from = 'noreply@vadhana.co.id';
                $footer = '<p>Silahkan login ke <a href="https://hr.vadhana.co.id">HR Information System</a> untuk '.$aksi[$i].'.</p>

                        <br/><br/>
                        <br><br><br>Terima Kasih.<br><br>
                        <br/>Contact us at info@vadhana.co.id Do not reply to this computer-generated email. <br/>
                            Vadhana International, Jl. Soekarno Hatta No 88 (Simpang Arifin Ahmad),Kel. Tobek Godang Kec. Tampan, Pekanbaru - 28297, Riau';
            }else{
                $from = 'noreply@multiteraindo.com';
                $footer = '<p>Silahkan login ke <a href="https://hr.multiteraindo.com">HR Information System</a> untuk '.$aksi[$i].'.</p>

                        <br/><br/>
                        <br><br><br>Terima Kasih.<br><br>
                        <br/>Contact us at info@multiteraindo.com Do not reply to this computer-generated email. <br/>
                        MULTI TERAINDO, Jl. Soekarno Hatta No 89 (Simpang Arifin Ahmad),Kel. Tobek Godang Kec. Tampan, Pekanbaru - 28297, Riau';
            }
        }else{
            $from = 'noreply@vadhana.co.id';
            $footer = '<p>Silahkan login ke <a href="https://hr.vadhana.co.id">HR Information System</a> untuk '.$aksi[$i].'.</p>

                        <br/><br/>
                        <br><br><br>Terima Kasih.<br><br>
                        <br/>Contact us at info@vadhana.co.id Do not reply to this computer-generated email. <br/>
                            Vadhana International, Jl. Soekarno Hatta No 88 (Simpang Arifin Ahmad),Kel. Tobek Godang Kec. Tampan, Pekanbaru - 28297, Riau';
        }

        if(!empty($to[$i])) {
            array_push($dataInsertMail, array(
                'to' => $to[$i],
                'from' => $from,
                'subjek' => $subjek[$i],
                'isi' => $isi[$i].$footer,
                'tglpost' => date('Y-m-d H:i:s'),
                'statuskirim' => "belum", 
            ));
        }
        
        $i++;
    }

    if($dataInsertMail){
        $CI->Md_mailbox->addMails($dataInsertMail);
    }
}

?>
