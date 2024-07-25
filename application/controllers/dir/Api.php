<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    private $akses = '';
    private $allowed_accesses = [
        'is_spadmin' => 'spadmin',
    ];
    public function __construct()
    {
        parent::__construct();
        $this->load->library('M_datatable');

        //Load Model//
        $this->load->model('Md_siswa');
        $this->load->model('Md_guru');

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
    public function manage_guru()
    {
        $source = getDataForDataTable('Md_guru', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['guru_id'] = encrypt($list->guru_id);
            $row['niy'] = $list->niy;
            $row['nama_guru'] = $list->nama_guru;
            $row['jenis_kelamin'] = $list->jenis_kelamin;
            $row['agama'] = $list->agama;
            $row['tgl_lahir'] = $list->tgl_lahir;
            $row['alamat'] = $list->alamat;
            $row['pendidikan_terakhir'] = $list->pendidikan_terakhir;
            $row['no_hp'] = $list->no_hp;
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

    public function manage_mapel()
    {
        $source = getDataForDataTable('Md_mapel', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['mapel_id'] = encrypt($list->mapel_id);
            $row['nama_mapel'] = $list->nama_mapel;
            $row['deskripsi'] = $list->deskripsi;
            $row['status'] = $list->status;
            $row['date_created'] = $list->date_created;
            $row['kode_mapel'] = $list->kode_mapel;
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
    public function manage_akun()
    {
        $source = getDataForDataTable('Md_akun', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['akun_id'] = encrypt($list->akun_id);
            $row['username'] = $list->username;
            $row['role'] = $list->role;
            $row['password'] = $list->password;
            $row['niy'] = $list->niy;
            $row['nis'] = $list->nis;
            $row['status'] = $list->status;
            $row['date_created'] = $list->date_created;
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
    public function manage_pembayaran()
    {
        $source = getDataForDataTable('Md_pembayaran', null);
        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['pembayaran_id'] = encrypt($list->pembayaran_id);
            $row['nis'] = $list->nis;
            $row['tajaran_id'] = $list->tajaran_id;
            $row['nominal'] = $list->nominal;
            $row['nama_siswa'] = $list->nama_siswa;
            $row['nama_tajaran'] = $list->nama_tajaran;
            $row['tanggal_pembayaran'] = $list->tanggal_pembayaran;
            switch (strtolower($list->status_pembayaran)) {
                case 'belum lunas':
                    $row['status_pembayaran'] = '<span class="m-badge m-badge--danger m-badge--rounded text-white text-center">Belum Lunas</span>';
                    break;
                case 'lunas':
                    $row['status_pembayaran'] = '<span class="m-badge m-badge--success m-badge--rounded text-white text-center">Lunas</span>';
                    break;
                case 'menunggu verifikasi':
                    $row['status_pembayaran'] = '<span class="m-badge m-badge--warning m-badge--rounded text-white text-center">Menunggu Verifikasi</span>';
                    break;
                case 'ditolak':
                    $row['status_pembayaran'] = '<span class="m-badge m-badge--danger m-badge--rounded text-white text-center">Ditolak</span>';
                    break;
                default:
                    $row['status_pembayaran'] = '<span class="m-badge m-badge--info m-badge--rounded text-white text-center">Tidak Diketahui</span>';
                    break;
            }
            if($list->bukti_pembayaran != ''){
                $row['bukti_pembayaran'] = '<a class="btn btn-outline-info text-center" data-bs-toggle="modal" data-bs-target="#modalImage-' . $list->pembayaran_id . '"><img onclick="img('.$list->pembayaran_id.')" src="' . base_url() . 'assets/upload/bukti_pembayaran/' . $list->bukti_pembayaran . '" alt="Bukti Pembayaran" width="50" ></a>';
            }else{
                $row['bukti_pembayaran'] = '-';
            }
            $row['catatan'] = $list->catatan;
            $row['status'] = $list->status;
            $row['date_created'] = $list->date_created;
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