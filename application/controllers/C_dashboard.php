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
    //    var_dump($this->session->userdata('hak_akses'));
    //     die;
        $pageData['page_name'] = 'V_dashboard';
        $pageData['page_dir'] = 'dashboard';
        $this->load->view('index', $pageData);

    }
}


