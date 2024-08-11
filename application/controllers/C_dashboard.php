<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_dashboard extends CI_Controller
{
    private $akses = '';

    private $allowed_accesses = [
        'is_guru' => 'guru',
        'is_admin' => 'admin',
        'is_ortu' => 'orang_tua',
    ];
    
    public function __construct()
    {
        parent::__construct();
        //load model
        $this->load->model('Md_siswa');
        $this->load->model('Md_guru');
        $this->load->model('Md_kelas');
        $this->load->model('Md_mapel');
        $this->load->model('Md_tahun_ajaran');
        $this->load->helper('encryption_id');


        date_default_timezone_set('Asia/Jakarta');
        $validate = false;
        foreach ($this->allowed_accesses as $key => $value) {
            if (!$this->session->userdata($key)) continue;
            $validate = true;
            $this->akses = $value;
            break;
        }
        if (!$validate) redirect(base_url('lawang'));

        
    }
    public function index()
    {
        $hakakses =$this->akses;
        $nis =$this->session->userdata('nis');
        $pageData['siswa'] = $this->Md_siswa->getSiswaCount();
        $pageData['guru'] = $this->Md_guru->getGuruCount();
        if($hakakses == 'ortu'){
            $pageData['kelas'] = $this->Md_kelas->getKelasCount($nis);
            $pageData['mapel'] = $this->Md_mapel->getMapelCount($nis);
            $pageData['siswa_nis'] = $this->Md_siswa->getSiswaByNISLimit1($nis);
            $pageData['mapel_nis'] = $this->Md_mapel->getByNIS($nis);
        }else{
            $pageData['kelas'] = $this->Md_kelas->getKelasCount();
            $pageData['mapel'] = $this->Md_mapel->getMapelCount();
        }
        $pageData['tahun_ajaran'] = $this->Md_tahun_ajaran->getAktif();
        $pageData['page_name'] = 'V_dashboard';
        $pageData['page_dir'] = 'dashboard';
        $this->load->view('index', $pageData);

    }
}


