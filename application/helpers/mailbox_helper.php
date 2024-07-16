<?php

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
        $from = 'noreply@vadhana.co.id';
        $footer = '<p>Silahkan login ke <a href="https://stfms.vadhana.co.id/"> Sistem Informasi STFMS</a> untuk '.$aksi[$i].'.</p>

                <br/><br/>
                <br><br><br>Terima Kasih.<br><br>';

        array_push($dataInsertMail, array(
            'to' => $to[$i],
            'from' => $from,
            'subjek' => $subjek[$i],
            'isi' => $isi[$i].$footer,
            'tglpost' => date('Y-m-d H:i:s'),
            'statuskirim' => "Draft", 
        ));
        
        $i++;
    }

    if($dataInsertMail){
        $CI->Md_mailbox->addMultipleMailbox($dataInsertMail);
    }
}

?>
