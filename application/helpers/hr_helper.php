<?php

function getLoginasList()
{
    $CI = get_instance();
    $CI->load->model('Md_pengguna');
    $CI->load->library('session');
    $dt_pengguna = $CI->Md_pengguna->getPenggunaByDivisi(decrypt($CI->session->userdata('karyawan_id')), 'SCM');

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

    $dt_pengguna = $CI->Md_pengguna->getPenggunaByKaryawanidAndDivisiAndHakaksesname(decrypt($CI->session->userdata('karyawan_id')), 'HRD', 'Staff');

    if ($dt_pengguna) {
        $array = array(
            'penggunaid' => $dt_pengguna->penggunaid,
            'hakaksesname' => $dt_pengguna->hakaksesname,
        );
        array_push($arr_pengguna, (object)$array);
    }

    //$arrayobject = (object)$arr_pengguna;
    return $arr_pengguna;
}

function getLastSeqCA($perusahaanid)
{
    $CI = get_instance();
    $dtperusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    if (!isset($dtperusahaan)) {
        return null;
        die;
    }

    //start no surat
    $seq = 0;
    $dtnoca = $CI->Md_cad->getLastSequence($perusahaanid, 'Uncontinue');
    if (isset($dtnoca)) {
        $seq = $dtnoca->seq + 1;
    } else {
        $seq = 1;
    }
    $month = date('m');
    $month = integerToRoman($month);
    $year = date('Y');

    $nocad = str_pad($seq, 4, '0', STR_PAD_LEFT) . "/" . $dtperusahaan->kodeperusahaan . "-FCAD/" . $month . "/" . $year;
    //end no surat

    $row = array();
    $row['data'] = TRUE;
    $row['seq'] = $seq;
    $row['nocad'] = $nocad;
    $row['perusahaanid'] = $dtperusahaan->perusahaanid;
    return $row;
}

function getLastSeqPC($perusahaanid)
{
    $CI = get_instance();
    $dtperusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    if (!isset($dtperusahaan)) {
        return null;
        die;
    }

    //start no surat
    $seq = 0;
    $dtnoca = $CI->Md_cad->getLastSequence($perusahaanid, 'Continue');
    if (isset($dtnoca)) {
        $seq = $dtnoca->seq + 1;
    } else {
        $seq = 1;
    }
    $month = date('m');
    $month = integerToRoman($month);
    $year = date('Y');

    $nocad = str_pad($seq, 4, '0', STR_PAD_LEFT) . "/" . $dtperusahaan->kodeperusahaan . "-FPCH/" . $month . "/" . $year;
    //end no surat

    $row = array();
    $row['data'] = TRUE;
    $row['seq'] = $seq;
    $row['nocad'] = $nocad;
    $row['perusahaanid'] = $dtperusahaan->perusahaanid;
    return $row;
}

function getLastSeqExr($perusahaanid)
{
    $CI = get_instance();
    $dtperusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    if (!isset($dtperusahaan)) {
        return null;
        die;
    }

    //start no surat
    $seq = 0;
    $dtnoexr = $CI->Md_exr->getLastSequence($perusahaanid);
    if (!empty($dtnoexr)) {
        $seq = $dtnoexr->seq + 1;
    } else {
        $seq = 1;
    }
    $month = date('m');
    $month = integerToRoman($month);
    $year = date('Y');

    $noexr = str_pad($seq, 4, '0', STR_PAD_LEFT) . "/" . $dtperusahaan->kodeperusahaan . "-FLP/" . $month . "/" . $year;
    //end no surat

    $row = array();
    $row['data'] = TRUE;
    $row['seq'] = $seq;
    $row['noexr'] = $noexr;
    $row['perusahaanid'] = $dtperusahaan->perusahaanid;
    return $row;
}

function getLastSeqRfp($perusahaanid)
{
    $CI = get_instance();
    $dtperusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    if (!isset($dtperusahaan)) {
        return null;
        die;
    }

    //start no surat
    $seq = 0;
    $dtnorfp = $CI->Md_rfp->getLastSequence($perusahaanid);
    if (isset($dtnorfp)) {
        $seq = $dtnorfp->seq + 1;
    } else {
        $seq = 1;
    }
    $month = date('m');
    $month = integerToRoman($month);
    $year = date('Y');

    $norfp = str_pad($seq, 4, '0', STR_PAD_LEFT) . "/" . $dtperusahaan->kodeperusahaan . "-FPP/" . $month . "/" . $year;
    //end no surat

    $row = array();
    $row['data'] = TRUE;
    $row['seq'] = $seq;
    $row['norfp'] = $norfp;
    $row['perusahaanid'] = $dtperusahaan->perusahaanid;
    return $row;
}

function getLastSeqTrans($perusahaanid, $rekening, $tgltrans, $jenistrans)
{
    $CI = get_instance();
    $dtperusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    if (!isset($dtperusahaan)) {
        return null;
        die;
    }

    # untuk sequence
    $year1 = date('Y', strtotime($tgltrans));
    $month1 = date('n', strtotime($tgltrans));
    //start no surat
    $seq = 0;
    $dtnotrans = $CI->Md_trans->getLastSequenceKasNew($perusahaanid, $rekening, $jenistrans, $month1, $year1);
    if (isset($dtnotrans)) {
        $seq = $dtnotrans->seq + 1;
    } else {
        $seq = 1;
    }

    # untuk kode jenis trans
    if ($jenistrans == 'Cash Payment' || $jenistrans == 'Bank Payment') {
        $trans = 'P';
    } else {
        $trans = 'R';
    }

    # untuk kode voucher
    $datarekening = $CI->Md_rekening->getRekeningById($rekening);

    # untuk penomoran
    $month = date('m', strtotime($tgltrans));
    $month = integerToRoman($month);
    $year = date('y', strtotime($tgltrans));
    $notrans = str_pad($seq, 4, '0', STR_PAD_LEFT) . "/" . $dtperusahaan->kodeperusahaan . "-" . $datarekening->kodevoucher . "-" . $trans . "/" . $month . "/" . $year;
    //end no surat

    $row = array();
    $row['data'] = TRUE;
    $row['seq'] = $seq;
    $row['no_trans'] = $notrans;
    $row['perusahaanid'] = $dtperusahaan->perusahaanid;
    return $row;
}

function getLastSeqTransRev($perusahaanid, $rekening, $tgltrans, $jenistrans)
{
    $CI = get_instance();
    $dtperusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    if (!isset($dtperusahaan)) {
        return null;
        die;
    }

    # untuk sequence
    $year1 = date('Y', strtotime($tgltrans));
    $month1 = date('n', strtotime($tgltrans));
    //start no surat
    $seq = 0;
    $dtnotrans = $CI->Md_trans->getLastSequenceKasNew($perusahaanid, $rekening, $jenistrans, $month1, $year1);
    if (isset($dtnotrans)) {
        $seq = $dtnotrans->seq + 1;
    } else {
        $seq = 1;
    }

    # untuk kode jenis trans
    if ($jenistrans == 'Cash Payment' || $jenistrans == 'Bank Payment') {
        $trans = 'P';
    } else {
        $trans = 'R';
    }

    # untuk kode voucher
    $datarekening = $CI->Md_rekening->getRekeningById($rekening);

    # untuk penomoran
    $month = date('m', strtotime($tgltrans));
    $month = integerToRoman($month);
    $year = date('y', strtotime($tgltrans));
    $notrans = str_pad($seq, 4, '0', STR_PAD_LEFT) . "/" . $dtperusahaan->kodeperusahaan . "-" . $datarekening->kodevoucher . "-" . $trans . "/" . $month . "/" . $year;
    //end no surat

    $row = array();
    $row['data'] = TRUE;
    $row['seq'] = $seq;
    $row['no_trans'] = $notrans;
    $row['perusahaanid'] = $dtperusahaan->perusahaanid;
    return $row;
}

function getLastSeqJurnal($perusahaanid)
{
    $CI = get_instance();
    $dtperusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    if (!isset($dtperusahaan)) {
        return null;
        die;
    }

    //start no surat
    $seq = 0;
    $dtnojurnal = $CI->Md_jurnalumum->getLastSequence($perusahaanid);
    if (isset($dtnojurnal)) {
        $seq = $dtnojurnal->seq + 1;
    } else {
        $seq = 1;
    }
    $month = date('m');
    $year = date('y');

    $nojurnal = "JU-" . $dtperusahaan->kodeperusahaan . $year . $month . str_pad($seq, 4, '0', STR_PAD_LEFT);
    //end no surat

    $row = array();
    $row['data'] = TRUE;
    $row['seq'] = $seq;
    $row['nojurnal'] = $nojurnal;
    $row['perusahaanid'] = $dtperusahaan->perusahaanid;
    return $row;
}

function generateRandomString($length = 30)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function search($array, $search_list)
{

    // Create the result array
    $result = array();

    // Iterate over each array element
    foreach ($array as $key => $value) {

        // Iterate over each search condition
        foreach ($search_list as $k => $v) {

            // If the array element does not meet
            // the search condition then continue
            // to the next element
            if (!isset($value[$k]) || $value[$k] != $v) {

                // Skip two loops
                continue 2;
            }
        }

        // Append array element's key to the
        //result array
        $result[] = $value;
    }

    // Return result 
    return $result;
}
