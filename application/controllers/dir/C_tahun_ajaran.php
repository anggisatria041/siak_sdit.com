<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_tahun_ajaran extends CI_Controller
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
        $this->load->model('Md_log');
        $this->load->model('Md_tahun_ajaran');

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
        /*             * * FOR CREATE DATA TABLE ** */
        /**
         * @var $config for configuration column and field data table into helper m_datatable
         * @param title    | name of table columns
         * @param field    | record that will be shown into tabl
         * @param sortable | setting each column if it can be sorted
         * @param width    | setting width each column -> default value is FALSE for auto width
         * @param template | making template for displaying record -> default value is FALSE
         */
        $configColumn['title'] = array('NO', 'Tahun Ajaran', 'Semester', 'Status', 'Aksi');
        $configColumn['field'] = array('no', 'nama_tajaran', 'semester', 'status_tajaran', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, TRUE, TRUE, FALSE);
        $configColumn['width'] = array(30, 100, 100, 100, 100);
        $configColumn['template'] = array(
            FALSE,
            FALSE,
            FALSE,
            FALSE,
            'function (e) {
                return \'\
                    <div class="dropdown down">\
                        <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                            <i class="la la-gear"></i>\
                        </a>\
                        <div class="dropdown-menu dropdown-menu-right">\
                            <a class="dropdown-item" href="javascript:edit(\\\'\'+e.tajaran_id +\'\\\');"><i class="la la-edit"></i> Edit Tahun Ajaran</a>\
                            <a class="dropdown-item" href="javascript:hapus(\\\'\'+e.tajaran_id+\'\\\');"><i class="la la-trash-o"></i> Hapus Tahun Ajaran</a>\
                        </div>\
                    </div>\
                \';
            }'
        );

        $set['id_table'] = 'tableManageTahunAjaran';
        $set['json_url'] = base_url() . 'dir/api/manage_tahun_ajaran';
        $set['columns'] = $this->m_datatable->setColumn($configColumn);
        $set['filter'] = FALSE;
        $set['search'] = TRUE;
        $set['server_side'] = TRUE;
        $set['perpage'] = 10;

        $pageData['tableManageTahunAjaran'] = $this->m_datatable->generateScript($set);
        $pageData['page_name'] = 'V_tahun_ajaran';
        $pageData['page_dir'] = 'tahun_ajaran';
        $this->load->view('index', $pageData);
    }
    public function add()
    {

        $this->form_validation->set_rules('tahun_ajaran', 'Tahun Ajaran', 'required');
        $this->form_validation->set_rules('status_tajaran', 'Status Tahun Ajaran', 'required');

        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() === FALSE) {
            die(json_encode([
                'status' => 'gagal',
                'message' => 'Semua field harus terisi',
                'csrf' => $csrf
            ]));
        }

        $tahun_ajaran = $this->input->post('tahun_ajaran');
        $status_tajaran = $this->input->post('status_tajaran');

        $ta=$this->Md_tahun_ajaran->getTahunAjaran();

        // if($ta->status_tajaran == 'Aktif' && $status_tajaran == 'Aktif'){
        //     echo json_encode(array('status' => 'gagal', 'message' => 'Tahun Ajaran ada yang aktif', 'csrf' => $csrf));
        //     die;
        // }
        $dataInsert = [
            [
                'nama_tajaran' => $tahun_ajaran,
                'semester' => 1,
                'status_tajaran' => $status_tajaran
            ],
            [
                'nama_tajaran' => $tahun_ajaran,
                'semester' => 2,
                'status_tajaran' => $status_tajaran
            ]
        ];

        $this->db->trans_begin();
        $this->Md_tahun_ajaran->addTahunAjaran($dataInsert);

        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('status' => 'success', 'csrf' => $csrf));
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 'gagal', 'message' => 'Data Tahun Ajaran gagal disimpan', 'csrf' => $csrf));
        }

    }
    public function edit($argv1 = '')
    {
        $valid = $argv1 == '' ? FALSE : (is_int(decrypt($argv1)) ? TRUE : FALSE);
        if (!$valid) {
            echo json_encode(array('data' => FALSE));
            die;
        }

        $tahun_ajaran = $this->Md_tahun_ajaran->getTahunAjaranById(decrypt($argv1));

        $row = array();
        if ($tahun_ajaran) {
            $row['data'] = TRUE;
            $row['tajaran_id'] = encrypt($tahun_ajaran->tajaran_id);
            $row['nama_tajaran'] = $tahun_ajaran->nama_tajaran;
            $row['semester'] = $tahun_ajaran->semester;
            $row['status_tajaran'] = $tahun_ajaran->status_tajaran;
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('tahun_ajaran', 'Tahun Ajaran', 'required');
        $this->form_validation->set_rules('status_tajaran', 'Status Tahun Ajaran', 'required');

        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() === FALSE) {
            die(json_encode([
                'status' => 'gagal',
                'message' => 'Semua field harus terisi',
                'csrf' => $csrf
            ]));
        }

        $tajaran_id = decrypt($this->input->post('tajaran_id'));
        $nama_tajaran = $this->input->post('tahun_ajaran');
        $status_tajaran = $this->input->post('status_tajaran');

        $this->db->trans_begin();
        $this->Md_tahun_ajaran->updateTahunAjaran($tajaran_id, [
            'nama_tajaran' => $nama_tajaran,
            'status_tajaran' => $status_tajaran
        ]);

        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('status' => 'success', 'csrf' => $csrf));
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 'gagal', 'message' => 'Data Tahun Ajaran gagal diperbarui', 'csrf' => $csrf));
        }
    }
    public function delete($argv1 = '')
    {
        $valid = $argv1 == '' ? FALSE : (is_int(decrypt($argv1)) ? TRUE : FALSE);
        if (!$valid) {
            echo json_encode(array('data' => FALSE));
            die;
        }

        $dataid = decrypt($argv1);
        $this->db->trans_begin();
        $this->Md_tahun_ajaran->updateTahunAjaran($dataid, array('status' => 2));

        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('data' => TRUE));
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('data' => FALSE));
        }
    }
}


