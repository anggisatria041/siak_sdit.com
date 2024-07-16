<?php
defined('BASEPATH') or exit('No direct script access allowed');

function getNomorReturPO($tanggal, $perusahaanid)
{
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_returpo');

    $month      = date('m', strtotime($tanggal));
    $year       = date('Y', strtotime($tanggal));

    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $rp         = $CI->Md_returpo->getLastRPO($month, $year, $perusahaanid);

    if (!empty($rp)) {
        $seq = $rp->seq + 1;
        $newid = sprintf("%04d", $seq);
        $nomor = "$newid/RPO-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    } else {
        $seq = 1;
        $nomor = "0001/RPO-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    }

    return (object)['nomor' => $nomor, 'seq' => $seq];
}

function getNomorTransAlih($tanggal, $perusahaanid){
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_transalih');
    
    $month      = date('m', strtotime($tanggal));
    $year       = date('Y', strtotime($tanggal));
    
    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $ta         = $CI->Md_transalih->getLastPBR($month, $year, $perusahaanid);
    
    if (!empty($ta)) {
        $seq = $ta->seq + 1;
        $newid = sprintf("%04d", $seq);
        $nomor = "$newid/PBR-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    } else {
        $seq = 1;
        $nomor = "0001/PBR-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    }

    return (object)['nomor' => $nomor, 'seq' => $seq];
}

function getNomorpcoapproval($tanggal, $perusahaanid){
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_periodecoaapproval');
    
    $month      = date('m', strtotime($tanggal));
    $year       = date('Y', strtotime($tanggal));
    
    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $pcoa         = $CI->Md_periodecoaapproval->getLastPcoaApp($month, $year, $perusahaanid);
    
    if (!empty($pcoa)) {
        $seq = $pcoa->seq + 1;
        $newid = sprintf("%04d", $seq);
        $nomor = "$newid/ASA-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    } else {
        $seq = 1;
        $nomor = "0001/ASA-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    }

    return (object)['nomor' => $nomor, 'seq' => $seq];
}

function getNomorProgress($perusahaanid)
{
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_progress');

    $year  = date('Y');

    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $progress   = $CI->Md_progress->getLastProgress($year, $perusahaanid);

    $seq   = 1;
    $nomor = "$perusahaan->kodeperusahaan/PRO/$year/0001";

    if (!empty($progress)) {
        $seq = $progress->seq + 1;
        $newid = sprintf("%04d", $seq);
        $nomor = "$perusahaan->kodeperusahaan/PRO/$year/$newid";
    }

    return (object)['nomor' => $nomor, 'seq' => $seq];
}

function getNomorTransFAT($tanggal, $perusahaanid)
{
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_trans');

    $month      = date('m', strtotime($tanggal));
    $year       = date('Y', strtotime($tanggal));

    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $transfat   = $CI->Md_trans->getLastTransFat($month, $year, $perusahaanid);

    //= reformat year
    $year = date('y', strtotime($tanggal));

    if (!empty($transfat)) {
        $seq = $transfat->seq + 1;
        $newid = sprintf("%04d", $seq);
        $nomor = "$newid/VR/FAT-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    } else {
        $seq = 1;
        $nomor = "0001/VR/FAT-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    }

    return (object)['notrans' => $nomor, 'seq' => $seq];
}

function getNomorTransReturMI($tanggal, $perusahaanid)
{
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_trans');

    $month      = date('m', strtotime($tanggal));
    $year       = date('Y', strtotime($tanggal));

    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $trans   = $CI->Md_trans->getLastTransReturMI($month, $year, $perusahaanid);

    //= reformat year
    $year = date('y', strtotime($tanggal));

    if (!empty($trans)) {
        $seq = $trans->seq + 1;
        $newid = sprintf("%04d", $seq);
        $nomor = "$newid/VR/RMI-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    } else {
        $seq = 1;
        $nomor = "0001/VR/RMI-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    }

    return (object)['notrans' => $nomor, 'seq' => $seq];
}

function getNomorTransNotaReturMI($tanggal, $perusahaanid)
{
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_trans');

    $month      = date('m', strtotime($tanggal));
    $year       = date('Y', strtotime($tanggal));

    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $trans   = $CI->Md_trans->getLastTransNotaReturMI($month, $year, $perusahaanid);

    //= reformat year
    $year = date('y', strtotime($tanggal));

    if (!empty($trans)) {
        $seq = $trans->seq + 1;
        $newid = sprintf("%04d", $seq);
        $nomor = "$newid/VR/NR-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    } else {
        $seq = 1;
        $nomor = "0001/VR/NR-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    }

    return (object)['notrans' => $nomor, 'seq' => $seq];
}

function getNomorTransFATCancel($tanggal, $perusahaanid)
{
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_trans');

    $month      = date('m', strtotime($tanggal));
    $year       = date('Y', strtotime($tanggal));

    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $transfat   = $CI->Md_trans->getLastTransFatcancel($month, $year, $perusahaanid);

    //= reformat year
    $year = date('y', strtotime($tanggal));

    if (!empty($transfat)) {
        $seq = $transfat->seq + 1;
        $newid = sprintf("%04d", $seq);
        $nomor = "$newid/VR/FCL-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    } else {
        $seq = 1;
        $nomor = "0001/VR/FCL-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    }

    return (object)['notrans' => $nomor, 'seq' => $seq];
}

function getNomorTransMIO($tanggal, $perusahaanid, $sumber)
{
    $kode = [
        'MI-PO'  => 'MI',
        'MO'     => 'MO',
        'MI-MT'  => 'MTI',
        'MO-MT'  => 'MTO',
        'MI-RMO' => 'RMO',
        'MO-RMO' => 'MOR',
        'MI-SER' => 'MIS',
        'MI-RPO' => 'MIR',
        'Opname' => 'OPN',
        'Adjust' => 'ADJ',
        'Assembly' => 'ASM',
    ];

    $jenis_trans = [
        'MI-PO'  => 'MI',
        'MO'     => 'MO',
        'MI-MT'  => 'MT',
        'MO-MT'  => 'MT',
        'MI-RMO' => 'RMO',
        'MO-RMO' => 'MO From Retur',
        'MI-SER' => 'MI Service',
        'MI-RPO' => 'MI From Retur',
        'Opname' => 'Opname',
        'Adjust' => 'Adjust',
        'Assembly' => 'Assembly',
    ];

    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_trans');

    $month      = date('m', strtotime($tanggal));
    $year       = date('Y', strtotime($tanggal));

    $_sumber = '';
    if ($jenis_trans[$sumber] == 'MTI') {
        $_sumber = 'MT In';
    } else if ($jenis_trans[$sumber] == 'MTO') {
        $_sumber = 'MT Out';
    }

    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $trans      = $CI->Md_trans->getLastTrans($month, $year, $perusahaanid, $jenis_trans[$sumber], $_sumber);

    $seq    = 1;
    $nomor  = "0001/VR/" . $kode[$sumber] . "-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    if (!empty($trans)) {
        $seq    = $trans->seq + 1;
        $newid  = sprintf("%04d", $seq);
        $nomor  = "$newid/VR/" . $kode[$sumber] . "-$perusahaan->kodeperusahaan/" . integerToRoman($month) . "/$year";
    }

    return (object)['notrans' => $nomor, 'seq' => $seq];
}

function getTglValidasi(int $itemdetailid, int $tglvalidasi)
{
    $CI = get_instance();
    $CI->load->model('Md_miodetail');

    $item = $CI->Md_miodetail->getDataBy([
        'itemdetailid' => $itemdetailid,
        'tglvalidasi' => date('Y-m-d H:i:s', $tglvalidasi)
    ]);

    $counter = 0;
    if (count($item) > 0) {
        $miodetail = $CI->Md_miodetail->getDataBy([
            'itemdetailid' => $itemdetailid,
            'mioid' => end($item)->mioid
        ]);
        $counter = count($miodetail);
    }


    return date('Y-m-d H:i:s', $tglvalidasi + $counter);
}

function getNomorTrans($perusahaanid, $tgltrans, $sumber)
{
    $CI = get_instance();
    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    if (!isset($perusahaan)) {
        return null;
        die;
    }

    # untuk sequence
    $y = date('Y', strtotime($tgltrans));
    $m = date('n', strtotime($tgltrans));
    //start no surat
    $trans = $CI->Md_trans->getLastSequenceInvoiceProgress($perusahaanid, $m, $y, $sumber);
    $seq = 1;
    if (!empty($trans)) {
        $seq = $trans->seq + 1;
    }

    if ($sumber == 'Payroll') {
        $kode = 'PR';
    } else if ($sumber == 'Progress') {
        $kode = 'PG';
    } else {
        $kode = 'INVS';
    }

    # untuk penomoran
    $month = date('m', strtotime($tgltrans));
    $month = integerToRoman($month);
    $year = date('y', strtotime($tgltrans));
    $notrans = str_pad($seq, 4, '0', STR_PAD_LEFT) . "/" . $perusahaan->kodeperusahaan . "-$kode-R/" . $month . "/" . $year;
    //end no surat

    $row = array();
    $row['data'] = TRUE;
    $row['seq'] = $seq;
    $row['no_trans'] = $notrans;
    return $row;
}

function get_nomor_mr($tgl, $perusahaanid, $kodejenis)
{
    $CI = get_instance();
    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $kodeperusahaan = $perusahaan->kodeperusahaan;

    $month = date('m', strtotime($tgl));
    $year = date('Y', strtotime($tgl));
    $mr = $CI->Md_mr->getLastMrByPerusahaanidAndMonth($perusahaanid, $month, $year);
    $month = integerToRoman($month);

    if (isset($mr)) {
        $seq = explode('/', $mr->nomr);
        if ($seq[2] == $month) {
            $seq[0] = $seq[0] + 1;
            $nomr = str_pad($seq[0], 4, '0', STR_PAD_LEFT) . "/" . $kodejenis . "-$kodeperusahaan/$month/$year";
        } else {
            $nomr = "0001/" . $kodejenis . "-$kodeperusahaan/$month/$year";
        }
    } else {
        $nomr = "0001/" . $kodejenis . "-$kodeperusahaan/$month/$year";
    }

    return $nomr;
}

function getNomorFatLama($perusahaanid){
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_fatlama');
    
    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $fatlama    = $CI->Md_fatlama->getLastSequenceFatLama($perusahaanid);
    
    if (!empty($fatlama)) {
        $seq = $fatlama->seq + 1;
        $newid = sprintf("%04d", $seq);
        $nomor = "$newid/$perusahaan->kodeperusahaan/FATLAMA";
    } else {
        $seq = 1;
        $nomor = "0001/$perusahaan->kodeperusahaan/FATLAMA";
    }

    return (object)['nomor' => $nomor, 'seq' => $seq];
}
function getNomorVoucherFatLama($perusahaanid){
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_trans');
    
    $perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $trans    = $CI->Md_trans->getLastSequenceFatLama($perusahaanid);
    
    if (!empty($trans)) {
        $seq = $trans->seq + 1;
        $newid = sprintf("%04d", $seq);
        $nomor = "$newid/VR/FATLAMA-$perusahaan->kodeperusahaan";
    } else {
        $seq = 1;
        $nomor = "0001/VR/FATLAMA-$perusahaan->kodeperusahaan";
    }

    return (object)['nomor' => $nomor, 'seq' => $seq];
}
