<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    private $akses = '';
    private $allowed_accesses = [
        'is_spadmin'    => 'spadmin',
    ];
    public function __construct()
    {
        parent::__construct();
        $this->load->library('M_datatable');

        //Load Model//
        $this->load->model('Md_siswa');

        //Load Helper//
        $this->load->helper('get_datatable');
        $this->load->helper('encryption_id');
        $this->load->helper('get_datatable');
        $this->load->helper('encryption_id');
        $this->load->helper('log');
        $this->load->helper('indonesia_day');
        $this->load->helper('hr');
        $this->load->helper('integer_to_roman');
        $this->load->helper('pushnotif');
        $this->load->helper('number_format');
        $this->load->helper('number_to_word');
        $this->load->helper('number_generator');

        date_default_timezone_set('Asia/Jakarta');
        
    }

    public function index()
    {
        show_error('Silahkan akses Controller melalui AJAX Request', '403', 'Forbidden Access 403');
    }
    public function manage_siswa()
    {
        $source = getDataForDataTable('Md_siswa', null);
       
        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['siswa_id'] = encrypt($list->siswa_id);
            $row['nisn'] = $list->nisn;
            $row['nama'] = $list->nama;
            $row['jenis_kelamin'] = $list->jenis_kelamin;
            $row['tempat_lahir'] = $list->tempat_lahir;
            $row['tanggal_lahir'] = $list->tanggal_lahir;
            $row['agama'] = $list->agama;
            $row['alamat'] = $list->alamat;
            $row['no_hp'] = $list->no_hp;
            $row['email'] = $list->email;
            $row['kelas_id'] = encrypt($list->kelas_id);
            $row['orang_tua_id'] = encrypt($list->orang_tua_id);
           
            $data[] = $row;
        }

        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }
}