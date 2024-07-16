<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function bcsum(array $numbers) : string {
    $total = "0";
    foreach ($numbers as $number) {
        $total = bcadd($total, $number, 2);
    }
    return $total;
}

function bcround($number, $scale=0) {
    $fix = "5";
    for ($i=0;$i<$scale;$i++) $fix="0$fix";
    $number = bcadd($number, "0.$fix", $scale+1);
    return    bcdiv($number, "1.0",    $scale);
}

function validasiPersentaseJob($jobid, $persen, $defaultjob) {
    $CI = get_instance();

    if(!is_array($jobid)){
        $listjobid = array($jobid);
        $listpersen = array($persen);
    }else{
        $listjobid = $jobid;
        $listpersen = $persen;
    }

    $flag = true;
    $message = '';
    $totalpersen = 0;
    $datapersenjob = array();

    //cek duplikat
    if(count($listjobid) != count(array_unique($listjobid))){
        $flag = false;
        $message = 'Job yang dipilih tidak boleh duplikat.';
    }
    
    if($flag){
        if(count($listjobid) == 0){
            $flag = false;
            $message = 'Penghasilan wajib diklasifikasi ke minimal 1 Job.';
        }
    }

    if($flag){
        if($defaultjob == null){
            $flag = false;
            $message = 'Job Default harus dipilih.';   
        }
    }

    if($flag){
        foreach ($listjobid as $key => $list) {
            if($list && $listpersen[$key]){
                if($listpersen[$key] > 0){
                    array_push($datapersenjob, array(
                        'jobid' => decrypt($list),
                        'persen' => $listpersen[$key],
                        'defaultjob' => $defaultjob == decrypt($list) ? 'ya' : 'tidak',
                    ));
                    $totalpersen = bcadd($totalpersen + $listpersen[$key], 2);
                }
            }else{
                $flag = false;
                $message = 'Semua kolom wajib diisi.';
                break;
            }
        }
        if($totalpersen != 100){
            $flag = false;
            $message = 'Persentase Job harus 100%';
        }
    }

    $row = array();
    $row['status'] = $flag;
    $row['message'] = $message;
    $row['totalpersen'] = $totalpersen;
    $row['datapersenjob'] = $datapersenjob;
    return $row;
}

function hitungPersentaseJobPerKomponen($jobid, $persen, $defaultjob, $komponenpenghasilan){
    $CI = get_instance();

    $CI->load->model('Md_penghasilan');
    
    //SET DATA TO ARRAY
    $listjobid = !is_array($jobid) ? array($jobid) : $jobid;
    $listpersen = !is_array($jobid) ? array($persen) : $persen;
    $listkomponenpenghasilan = !is_array($komponenpenghasilan) ? array($komponenpenghasilan) : $komponenpenghasilan;
   
    //VARIABLE OUTPUT
    $flag = true;
    $message = '';
    $dataoutput = array();

    //PENGECEKAN KELENGKAPAN DATA
    if(count($listjobid) == 0 || count($listpersen) == 0 || count($listkomponenpenghasilan) == 0 || count($listjobid) != count($listpersen)){
        $flag = false;
        $message = 'Data tidak valid.';
    }

    if($flag){
        $listkomponenid = array();

        //HITUNG NOMINAL BERDASARKAN PERSEN
        foreach ($listjobid as $key => $dt) {
            $jobid = $dt;
            $persen = $listpersen[$key];
            if($flag){
                foreach ($listkomponenpenghasilan as $key2 => $dt2) {
                    if(!isset($dt2->jumlah)){ $dt2->jumlah = 0; }
                    
                    //Pajak Penghasilan 21 Tahunan dibagi dengan masa kerja
                    if($dt2->komponenid == 40){ 
                        $dtpenghasilan = $CI->Md_penghasilan->getPengByJob($dt2->karyawanid, $dt2->bulan, $dt2->tahun, $dt2->jobid);
                        if(count($dtpenghasilan) == 0){
                            $flag = false;
                            $message = 'Penghasilan tidak ditemukan';
                            break;
                        }

                        $listkomponenid[$dt2->komponenid] = round($dt2->jumlah/$dtpenghasilan[0]->masakerja);
                        $dataoutput[$dt2->komponenid][$jobid] = round($dt2->jumlah/$dtpenghasilan[0]->masakerja) * $persen / 100;                        
                    } else{
                        $listkomponenid[$dt2->komponenid] = $dt2->jumlah;
                        $dataoutput[$dt2->komponenid][$jobid] = $dt2->jumlah * $persen / 100;
                    }
                }
            }else{
                break;
            }
        }

        if($flag){
            //cek selisih pembulatan
            //selisih pembulatan di masukan ke dalam salah satu job
            foreach ($listkomponenid as $key => $dt) {
                $totalpembagian = array_sum($dataoutput[$key]);
                $totalseharusnya = $dt;
                $selisih = $totalseharusnya - $totalpembagian;
                $dataoutput[$key][$defaultjob] = $dataoutput[$key][$defaultjob] + $selisih;
            }
        }
    }

    $row = array();
    $row['status'] = $flag;
    $row['message'] = $message;
    $row['dataoutput'] = $dataoutput;
    return $row;
}

function validasiJurnal($debit, $kredit){
    //method untuk memastikan debit dan kredit balance

    $listdebit = !is_array($debit) ? array($debit) : $debit;
    $listkredit = !is_array($kredit) ? array($kredit) : $kredit;
    
    $flag = true;
    $message = '';
    $dataoutput = array();
    
    $totaldebit = array_sum($listdebit);
    $totalkredit = array_sum($listkredit);

    if(bccomp($totaldebit, $totalkredit, 3) != 0){
        $flag = false;
        $message = 'Total Debit dan Total Kredit tidak balance.';
        //$dataoutput = array('totaldebit' => 0, 'totalkredit' => 0);
    }else{
        $flag = true;
        //$dataoutput = array('totaldebit' => $totaldebit, 'totalkredit' => $totalkredit);
    }
    $dataoutput = array('totaldebit' => $totaldebit, 'totalkredit' => $totalkredit);

    $row = array();
    $row['status'] = $flag;
    $row['message'] = $message;
    $row['dataoutput'] = $dataoutput;
    return $row;
}

?>
