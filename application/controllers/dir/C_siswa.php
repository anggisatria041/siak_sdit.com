<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_siswa extends CI_Controller
{
    private $akses = '';

    private $allowed_accesses = [
        'is_spadmin' => 'spadmin',
    ];
    
    public function __construct()
    {
        parent::__construct();
        //load model
    }
    public function index()
    {
        $pageData['page_name'] = 'V_siswa';
        $pageData['page_dir'] = 'siswa';
        $this->load->view('index', $pageData);

    }
}


