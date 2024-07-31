<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_kelas extends CI_Controller
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
        $this->load->model('Md_kelas');
        $this->load->model('Md_log');

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
        $configColumn['title'] = array('NO', 'Nama Kelas', 'Keterangan', 'Aksi');
        $configColumn['field'] = array('no', 'nama_kelas', 'keterangan', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, TRUE, FALSE);
        $configColumn['width'] = array(30, 100, 70, 50); //on px
        $configColumn['template'] = array(
            
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
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.kelas_id +\'\\\');"><i class="la la-edit"></i> Edit Kelas</a>\
                                <a class="dropdown-item" href="javascript:hapus(\\\'\'+e.kelas_id+\'\\\');"><i class="la la-trash-o"></i> Hapus Kelas</a>\
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
        $set['id_table'] = 'tableManageKelas'; // tanpa spasi dan karakter
        $set['json_url'] = base_url() . 'dir/api/manage_kelas';
        $set['columns'] = $this->m_datatable->setColumn($configColumn);
        $set['filter'] = FALSE; // wajib
        $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
        $set['server_side'] = TRUE; // wajib
        $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

        $pageData['tableManageKelas'] = $this->m_datatable->generateScript($set);

        $pageData['page_name'] = 'V_kelas';
        $pageData['page_dir'] = 'kelas';
        $this->load->view('index', $pageData);


    }
    public function add()
    {

        $this->form_validation->set_rules('nama_kelas', '', 'required');
        $this->form_validation->set_rules('keterangan', '', 'required');

        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() === FALSE) {
            die(json_encode([
                'status' => 'gagal',
                'message' => 'Semua fill harus terisi',
                'csrf' => $csrf
            ]));
        }

        $kelas_id = decrypt($this->input->post('kelas_id'));
        $nama_kelas = $this->input->post('nama_kelas');
        $keterangan = $this->input->post('keterangan');

        $dataInsert = array(
            'nama_kelas' => $nama_kelas,
            'keterangan' => $keterangan,
            'status' => 1
        );
        $this->db->trans_begin();
        $this->Md_kelas->addKelas($dataInsert);

        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('status' => 'success', 'csrf' => $csrf));
            die;
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 'gagal', 'message' => 'Data Kelas gagal disimpan', 'csrf' => $csrf));
            die;
        }

    }
    public function edit($argv1 = '')
    {

        $valid = $argv1 == '' ? FALSE : (is_int(decrypt($argv1)) ? TRUE : FALSE);
        if (!$valid) {
            echo json_encode(array('data' => FALSE));
            die;
        }

        $kelas = $this->Md_kelas->getKelasById(decrypt($argv1));
        // var_dump($kelas);
        // die;

        $row = array();
        if ($kelas) {
            $row['data'] = TRUE;
            $row['kelas_id'] = encrypt($kelas->kelas_id);
            $row['nama_kelas'] = $kelas->nama_kelas;
            $row['keterangan'] = $kelas->keterangan;
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('nama_kelas', '', 'required');
        $this->form_validation->set_rules('keterangan', '', 'required');

        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $kelas_id = $this->input->post('kelas_id') == "" ? NULL : decrypt($this->input->post('kelas_id'));
            $nama_kelas = $this->input->post('nama_kelas') == "" ? NULL : $this->input->post('nama_kelas');
            $keterangan = $this->input->post('keterangan') == "" ? NULL : $this->input->post('keterangan');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua fill harus terisi',
                    'csrf' => $csrf
                ]));
            }

            $this->db->trans_begin();
            $this->Md_kelas->updateKelas($kelas_id, [
                'nama_kelas' => $nama_kelas,
                'keterangan' => $keterangan,
                'status' => 1
            ]);
            // addLog('Update Data', 'Mengubah data Kelas, 'GKelasD' . $gukelas);
            if ($this->db->trans_status() == TRUE) {
                $this->db->trans_commit();
                echo json_encode(array('status' => 'success', 'csrf' => $csrf));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'gagal', 'message' => 'Data Kelas gagal disimpan', 'csrf' => $csrf));
                die;
            }
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
        $this->Md_kelas->updateKelas($dataid, array('status' => 2));

        // addLog('Delete Data', 'Menghapus data Kelas', 'Kelas ID' . $dataid);
        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('data' => TRUE));
            die;
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('data' => FALSE));
            die;
        }
    }
}
