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
        $this->load->model('Md_siswa');
        //Load library
        $this->load->library('M_datatable');
        $this->load->library('Pdf');

        //Load Helper
        $this->load->helper('get_datatable');
        $this->load->helper('encryption_id');
        $this->load->helper('log');
        $this->load->helper('indonesia_day');
        $this->load->helper('hr');
        $this->load->helper('integer_to_roman');
        $this->load->helper('pushnotif');
        $this->load->helper('number_format');
        $this->load->helper('number_to_word');

        date_default_timezone_set('Asia/Jakarta');
        

    }
    public function index()
    {
        /*             * * FOR CREATE DATA TABLE ** */
        /**
         * @var $config for configuration column and field data table into helper m_datatable
         * @param title    | name of table columns
         * @param field    | record that will be shown into tabl
         * @param sortable | setting each column if it can be sorted
         * @param width    | setting width each column -> default value is FALSE for auto width
         * @param template | making template for displaying record -> default value is FALSE
         */
        $configColumn['title'] = array('NO', 'NISN', 'Nama', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Alamat', 'No HP', 'Email', 'Aksi');
        $configColumn['field'] = array('no', 'nisn', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat', 'no_hp', 'email', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE);
        $configColumn['width'] = array(30, 50, 50, 100, 100,80,50,100,50,100,50); //on px
        $configColumn['template'] = array(
            FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE,
            'function (e) {
                    return \'\
                        <div class="dropdown down">\
                            <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                <i class="la la-gear"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-right">\
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.divisiposisiid+\'\\\');"><i class="la la-edit"></i> Edit Orchart</a>\
                                <a class="dropdown-item" href="javascript:hapus(\\\'\'+e.divisiposisiid+\'\\\');"><i class="la la-trash-o"></i> Hapus Orchart</a>\
                            </div>\
                        </div>\
                    \';
                }'
        );
        $configFilter = FALSE;

         /**
         * @var $set['columns'] -> Mendefinisikan kolom-kolom pada table
         * @var $set['search'] -> Mendefinisikan box searching ditampilkan atau tidak
         * @var $set['filter'] -> Mendefinisikan box filtering bagian kolom tertentu
         * @var $set['URL'] -> Mendefinisikan url mengambil data dari server 
         * @var $set['search'] -> Mendefinisikan box searching ditampilkan atau tidak.
         */
        $set['id_table'] = 'tableManageSiswa'; // tanpa spasi dan karakter
        $set['json_url'] = base_url() . 'dir/api/manage_siswa';
        $set['columns'] = $this->m_datatable->setColumn($configColumn);
        $set['filter'] = FALSE; // wajib
        $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
        $set['server_side'] = TRUE; // wajib
        $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

        $pageData['tableManageSiswa'] = $this->m_datatable->generateScript($set);
        $pageData['page_name'] = 'V_siswa';
        $pageData['page_dir'] = 'siswa';
        $this->load->view('index', $pageData);


    }
}


