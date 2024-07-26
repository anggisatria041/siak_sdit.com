<?php
defined('BASEPATH') or exit('No direct script access allowed');


function generate_no_depo($tanggal)
{
    $CI = get_instance();
    //mendefisikan sequence ex. 0001/SPK-VDI/III/2021 (reset perbulan) 
    $bulan = date('m');
    $tahun = date('Y');
    $romawi = getRomawi($bulan);

    // Mengambil Kode perusahaan 
    $dt_perusahaan = $CI->Md_requestdeposit->getPerusahaan();
    $kode = $dt_perusahaan->kodeperusahaan;
    $id_perusahaan = $dt_perusahaan->perusahaanid;

    $data = $CI->Md_requestdeposit->getNoDepoByMonthAndPerusahaanid($bulan, $tahun, $id_perusahaan);
    if (empty($data)) {
        $no = 1;
    } else {
        $no_depo = explode("/", $data->no_deposit);
        $no = $no_depo[0] + 1;
    }

    $no_depo = sprintf("%04s", $no) . '/ReqDepo-' . $kode . '/' . $romawi . '/' . $tahun;
    return $no_depo;
}


function generate_no_fuel_ticket($jenis_warehouse, $perusahaanid): array
{
    $CI = get_instance();
    $CI->load->model('Md_fuelticket');
    $CI->load->model('Md_perusahaan');

    //mendefisikan sequence ex. 0001/SPK-VDI/III/2021 (reset perbulan) 
    $bulan = date('m');
    $tahun = date('Y');
    $romawi = getRomawi($bulan);

    // Mengambil Kode perusahaan 
    $dt_perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);
    $kode = $dt_perusahaan->kodeperusahaan;
    $id_perusahaan = $dt_perusahaan->perusahaanid;

    $data = $CI->Md_fuelticket->getNoTicketByMonthAndPerusahaanid($bulan, $tahun, $id_perusahaan);
    if (empty($data)) {
        $no = 1;
    } else {
        $noticket = explode("/", $data->no_ticket);
        $no = $noticket[0] + 1;
    }

    $noticket = sprintf("%04s", $no) . '/FTM-' . $kode . '/' . $romawi . '/' . $tahun;
    if ($jenis_warehouse == 'Warehouse') {
        $noticket = sprintf("%04s", $no) . '/FT-' . $kode . '/' . $romawi . '/' . $tahun;
    }

    return ['no' => $noticket, 'seq' => $no];
}

/**
 * Mengambil nomor mio terakhir
 * 
 * @param int $perusahaanid
 * @param string $jenis = Material In/Material Out
 */
function generate_no_mio($perusahaanid, $jenis): array
{
    $CI = get_instance();
    $CI->load->model('Md_perusahaan');
    $CI->load->model('Md_mio');

    //mendefisikan sequence ex. 0001 /SPK-VDI/III/2021 (reset perbulan) 
    $bulan = date('m');
    $tahun = date('Y');
    $romawi = getRomawi($bulan);

    // Mengambil Kode perusahaan 
    $dt_perusahaan = $CI->Md_perusahaan->getPerusahaanById($perusahaanid);

    $kode = $dt_perusahaan->kodeperusahaan;
    // dd($kode);


    $data = $CI->Md_mio->getNoMioByMonth($bulan, $tahun, $jenis);

    $no = 1;
    if (!empty($data)) {
        $no_mio = explode("/", $data->nomio);
        $no = $no_mio[0] + 1;
    }

    $no_mio = sprintf("%04s", $no) . '/MO-' . $kode . '/' . $romawi . '/' . $tahun;
    if ($jenis == 'Material In') {
        $no_mio = sprintf("%04s", $no) . '/MI-' . $kode . '/' . $romawi . '/' . $tahun;
    }


    return ['no' => $no_mio, 'seq' => $no];
}

function generate_no_pengajuan()
{
    $CI = get_instance();
    $CI->load->model('Md_fuelticketpengajuan');

    $bulan = date('m');
    $tahun = date('Y');
    $romawi = getRomawi($bulan);
    $pengTicket = $CI->Md_fuelticketpengajuan->getNoPengajuanByMonth($bulan, $tahun);

    $nom = 1;
    if (!empty($pengTicket)) {
        $noPeng = explode("/", $pengTicket->no_pengajuan);
        $nom = intval($noPeng[0]) + 1;
    }

    $nopengajuan = sprintf("%04s", $nom) . '/PFT/' . $romawi . '/' . $tahun;
    return ['no' => $nopengajuan, 'seq' => $nom];
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
