<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_orang_tua extends CI_Controller
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
        $this->load->model('Md_orang_tua');
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
        $configColumn['title'] = array('No', 'Nama Ayah', 'Tahun Lahir Ayah', 'Pekerjaan Ayah', 'Nama Ibu', 'Tahun Lahir Ibu', 'Pekerjaan Ibu', 'Aksi');
        $configColumn['field'] = array('no', 'nama_ayah', 'tahun_lahir_ayah', 'pekerjaan_ayah', 'nama_ibu', 'tahun_lahir_ibu', 'pekerjaan_ibu', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, FALSE);
        $configColumn['width'] = array(20, 120, 100, 120, 100, 80, 100, 50);
        $configColumn['template'] = array(
            FALSE,
            FALSE,
            FALSE,
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
                            <a class="dropdown-item" href="javascript:edit(\\\'\'+e.id_orang_tua +\'\\\');"><i class="la la-edit"></i> Edit Orang Tua</a>\
                            <a class="dropdown-item" href="javascript:hapus(\\\'\'+e.id_orang_tua+\'\\\');"><i class="la la-trash-o"></i> Hapus Orang Tua</a>\
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
        $set['id_table'] = 'tableManageOrangTua';
        $set['json_url'] = base_url() . 'dir/api/manage_orang_tua';
        $set['columns'] = $this->m_datatable->setColumn($configColumn);
        $set['filter'] = FALSE; // wajib
        $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
        $set['server_side'] = TRUE; // wajib
        $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

        $pageData['tableManageOrangTua'] = $this->m_datatable->generateScript($set);
        $pageData['page_name'] = 'V_orang_tua';
        $pageData['page_dir'] = 'orang_tua';
        $this->load->view('index', $pageData);


    }
    public function add()
    {

        $this->form_validation->set_rules('nama_ayah', '', 'required');
        $this->form_validation->set_rules('tahun_lahir_ayah', '', 'required');
        $this->form_validation->set_rules('pekerjaan_ayah', '', 'required');
        $this->form_validation->set_rules('pendidikan_ayah', '', 'required');
        $this->form_validation->set_rules('penghasilan_ayah', '', 'required');
        $this->form_validation->set_rules('alamat_ayah', '', 'required');
        $this->form_validation->set_rules('nama_ibu', '', 'required');
        $this->form_validation->set_rules('tahun_lahir_ibu', '', 'required');
        $this->form_validation->set_rules('pekerjaan_ibu', '', 'required');
        $this->form_validation->set_rules('pendidikan_ibu', '', 'required');
        $this->form_validation->set_rules('penghasilan_ibu', '', 'required');
        $this->form_validation->set_rules('alamat_ibu', '', 'required');

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

        $dataInsert = array(
            'nama_ayah' => $this->input->post('nama_ayah'),
            'tahun_lahir_ayah' => $this->input->post('tahun_lahir_ayah'),
            'pekerjaan_ayah' => $this->input->post('pekerjaan_ayah'),
            'pendidikan_ayah' => $this->input->post('pendidikan_ayah'),
            'penghasilan_ayah' => $this->input->post('penghasilan_ayah'),
            'alamat_ayah' => $this->input->post('alamat_ayah'),
            'nama_ibu' => $this->input->post('nama_ibu'),
            'tahun_lahir_ibu' => $this->input->post('tahun_lahir_ibu'),
            'pekerjaan_ibu' => $this->input->post('pekerjaan_ibu'),
            'pendidikan_ibu' => $this->input->post('pendidikan_ibu'),
            'penghasilan_ibu' => $this->input->post('penghasilan_ibu'),
            'alamat_ibu' => $this->input->post('alamat_ibu'),
            'status' => 1
        );

        $this->db->trans_begin();
        $this->Md_orang_tua->addOrangTua($dataInsert);

        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('status' => 'success', 'csrf' => $csrf));
            die;
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 'gagal', 'message' => 'Data Siswa gagal disimpan', 'csrf' => $csrf));
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

        $orang_tua = $this->Md_orang_tua->getOrangTuaById(decrypt($argv1));


        $row = array();
        if ($orang_tua) {
            $row['data'] = TRUE;
            $row['id_orang_tua'] = encrypt($orang_tua->id_orang_tua);
            $row['nama_ayah'] = $orang_tua->nama_ayah;
            $row['tahun_lahir_ayah'] = $orang_tua->tahun_lahir_ayah;
            $row['pekerjaan_ayah'] = $orang_tua->pekerjaan_ayah;
            $row['pendidikan_ayah'] = $orang_tua->pendidikan_ayah;
            $row['penghasilan_ayah'] = $orang_tua->penghasilan_ayah;
            $row['alamat_ayah'] = $orang_tua->alamat_ayah;
            $row['nama_ibu'] = $orang_tua->nama_ibu;
            $row['tahun_lahir_ibu'] = $orang_tua->tahun_lahir_ibu;
            $row['pekerjaan_ibu'] = $orang_tua->pekerjaan_ibu;
            $row['pendidikan_ibu'] = $orang_tua->pendidikan_ibu;
            $row['penghasilan_ibu'] = $orang_tua->penghasilan_ibu;
            $row['alamat_ibu'] = $orang_tua->alamat_ibu;
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('nama_ayah', '', 'required');
        $this->form_validation->set_rules('tahun_lahir_ayah', '', 'required');
        $this->form_validation->set_rules('pekerjaan_ayah', '', 'required');
        $this->form_validation->set_rules('pendidikan_ayah', '', 'required');
        $this->form_validation->set_rules('penghasilan_ayah', '', 'required');
        $this->form_validation->set_rules('alamat_ayah', '', 'required');
        $this->form_validation->set_rules('nama_ibu', '', 'required');
        $this->form_validation->set_rules('tahun_lahir_ibu', '', 'required');
        $this->form_validation->set_rules('pekerjaan_ibu', '', 'required');
        $this->form_validation->set_rules('pendidikan_ibu', '', 'required');
        $this->form_validation->set_rules('penghasilan_ibu', '', 'required');
        $this->form_validation->set_rules('alamat_ibu', '', 'required');


        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $id_orang_tua = $this->input->post('id_orang_tua') == "" ? NULL : decrypt($this->input->post('id_orang_tua'));
            $nama_ayah = $this->input->post('nama_ayah') == "" ? NULL : $this->input->post('nama_ayah');
            $tahun_lahir_ayah = $this->input->post('tahun_lahir_ayah') == "" ? NULL : $this->input->post('tahun_lahir_ayah');
            $pekerjaan_ayah = $this->input->post('pekerjaan_ayah') == "" ? NULL : $this->input->post('pekerjaan_ayah');
            $pendidikan_ayah = $this->input->post('pendidikan_ayah') == "" ? NULL : $this->input->post('pendidikan_ayah');
            $penghasilan_ayah = $this->input->post('penghasilan_ayah') == "" ? NULL : $this->input->post('penghasilan_ayah');
            $alamat_ayah = $this->input->post('alamat_ayah') == "" ? NULL : $this->input->post('alamat_ayah');
            $nama_ibu = $this->input->post('nama_ibu') == "" ? NULL : $this->input->post('nama_ibu');
            $tahun_lahir_ibu = $this->input->post('tahun_lahir_ibu') == "" ? NULL : $this->input->post('tahun_lahir_ibu');
            $pekerjaan_ibu = $this->input->post('pekerjaan_ibu') == "" ? NULL : $this->input->post('pekerjaan_ibu');
            $pendidikan_ibu = $this->input->post('pendidikan_ibu') == "" ? NULL : $this->input->post('pendidikan_ibu');
            $penghasilan_ibu = $this->input->post('penghasilan_ibu') == "" ? NULL : $this->input->post('penghasilan_ibu');
            $alamat_ibu = $this->input->post('alamat_ibu') == "" ? NULL : $this->input->post('alamat_ibu');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua field harus terisi',
                    'csrf' => $csrf
                ]));
            }

            $this->db->trans_begin();
            $this->Md_orang_tua->updateOrangTua($id_orang_tua, [
                'nama_ayah' => $nama_ayah,
                'tahun_lahir_ayah' => $tahun_lahir_ayah,
                'pekerjaan_ayah' => $pekerjaan_ayah,
                'pendidikan_ayah' => $pendidikan_ayah,
                'penghasilan_ayah' => $penghasilan_ayah,
                'alamat_ayah' => $alamat_ayah,
                'nama_ibu' => $nama_ibu,
                'tahun_lahir_ibu' => $tahun_lahir_ibu,
                'pekerjaan_ibu' => $pekerjaan_ibu,
                'pendidikan_ibu' => $pendidikan_ibu,
                'penghasilan_ibu' => $penghasilan_ibu,
                'alamat_ibu' => $alamat_ibu
            ]);
            // addLog('Update Data', 'Mengubah data Orang Tua', 'ID Orang Tua' . $id_orang_tua);
            if ($this->db->trans_status() == TRUE) {
                $this->db->trans_commit();
                echo json_encode(array('status' => 'success', 'csrf' => $csrf));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'gagal', 'message' => 'Data Orang Tua gagal disimpan', 'csrf' => $csrf));
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
        $this->Md_orang_tua->updateOrangTua($dataid, array('status' => 2));

        // addLog('Delete Data', 'Menghapus data Siswa', 'Siswa ID' . $dataid);
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


