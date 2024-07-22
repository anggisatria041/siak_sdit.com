<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_guru extends CI_Controller
{
    private $akses = '';

    private $allowed_accesses = [
        'is_spadmin' => 'spadmin',
    ];

    public function __construct()
    {
        parent::__construct();
        //load model
        $this->load->model('Md_guru');
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
        $configColumn['title'] = array('NO', 'NIP', 'Nama Guru', 'Jenis Kelamin', 'Alamat', 'Pendidikan Terakhir', 'No Hp', 'Aksi');
        $configColumn['field'] = array('no', 'NIP', 'nama_guru', 'jenis_kelamin', 'alamat', 'pendidikan_terakhir', 'no_hp', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE);
        $configColumn['width'] = array(30, 50, 50, 100, 100, 80, 50, 100); //on px
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
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.guru_id +\'\\\');"><i class="la la-edit"></i> Edit Guru</a>\
                                <a class="dropdown-item" href="javascript:hapus(\\\'\'+e.guru_id+\'\\\');"><i class="la la-trash-o"></i> Hapus Guru</a>\
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
        $set['id_table'] = 'tableManageguru'; // tanpa spasi dan karakter
        $set['json_url'] = base_url() . 'dir/api/manage_guru';
        $set['columns'] = $this->m_datatable->setColumn($configColumn);
        $set['filter'] = FALSE; // wajib
        $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
        $set['server_side'] = TRUE; // wajib
        $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

        $pageData['tableManageguru'] = $this->m_datatable->generateScript($set);
       
        $pageData['page_name'] = 'V_guru';
        $pageData['page_dir'] = 'guru';
        $this->load->view('index', $pageData);


    }
    public function add()
    {

        $this->form_validation->set_rules('nisn', '', 'required');
        $this->form_validation->set_rules('nama', '', 'required');
        $this->form_validation->set_rules('jenis_kelamin', '', 'required');
        $this->form_validation->set_rules('tempat_lahir', '', 'required');
        $this->form_validation->set_rules('tanggal_lahir', '', 'required');
        $this->form_validation->set_rules('agama', '', 'required');
        $this->form_validation->set_rules('alamat', '', 'required');
        $this->form_validation->set_rules('no_hp', '', 'required');
        $this->form_validation->set_rules('email', '', 'required');

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

        $siswa_id = decrypt($this->input->post('siswa_id'));
        $nisn = $this->input->post('nisn');
        $nama = $this->input->post('nama');
        $jenis_kelamin = $this->input->post('jenis_kelamin');
        $tempat_lahir = $this->input->post('tempat_lahir');
        $tanggal_lahir = $this->input->post('tanggal_lahir');
        $agama = $this->input->post('agama');
        $alamat = $this->input->post('alamat');
        $no_hp = $this->input->post('no_hp');
        $email = $this->input->post('email');

        $dataInsert = array(
            'nisn' => $nisn,
            'nama' => $nama,
            'jenis_kelamin' => $jenis_kelamin,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'agama' => $agama,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'email' => $email,
            'status' => 1
        );
        $this->db->trans_begin();
        $this->Md_siswa->addSiswa($dataInsert);

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

        $siswa = $this->Md_siswa->getSiswaById(decrypt($argv1));


        $row = array();
        if ($siswa) {
            $row['data'] = TRUE;
            $row['siswa_id'] = encrypt($siswa->siswa_id);
            $row['nisn'] = $siswa->nisn;
            $row['nama'] = $siswa->nama;
            $row['jenis_kelamin'] = $siswa->jenis_kelamin;
            $row['tempat_lahir'] = $siswa->tempat_lahir;
            $row['tanggal_lahir'] = $siswa->tanggal_lahir;
            $row['agama'] = $siswa->agama;
            $row['alamat'] = ($siswa->alamat);
            $row['no_hp'] = ($siswa->no_hp);
            $row['email'] = ($siswa->email);
            $row['status'] = $siswa->status;
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('nisn', '', 'required');
        $this->form_validation->set_rules('nama', '', 'required');
        $this->form_validation->set_rules('jenis_kelamin', '', 'required');
        $this->form_validation->set_rules('tempat_lahir', '', 'required');
        $this->form_validation->set_rules('tanggal_lahir', '', 'required');
        $this->form_validation->set_rules('agama', '', 'required');
        $this->form_validation->set_rules('alamat', '', 'required');
        $this->form_validation->set_rules('no_hp', '', 'required');
        $this->form_validation->set_rules('email', '', 'required');

        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $siswa_id = $this->input->post('siswa_id') == "" ? NULL : decrypt($this->input->post('siswa_id'));
            $nisn = $this->input->post('nisn') == "" ? NULL : $this->input->post('nisn');
            $nama = $this->input->post('nama') == "" ? NULL : $this->input->post('nama');
            $jenis_kelamin = $this->input->post('jenis_kelamin') == "" ? NULL : $this->input->post('jenis_kelamin');
            $tempat_lahir = $this->input->post('tempat_lahir') == "" ? NULL : $this->input->post('tempat_lahir');
            $tanggal_lahir = $this->input->post('tanggal_lahir') == "" ? NULL : $this->input->post('tanggal_lahir');
            $agama = $this->input->post('agama') == "" ? NULL : $this->input->post('agama');
            $alamat = $this->input->post('alamat') == "" ? NULL : $this->input->post('alamat');
            $no_hp = $this->input->post('no_hp') == "" ? NULL : $this->input->post('no_hp');
            $email = $this->input->post('email') == "" ? NULL : $this->input->post('email');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua fill harus terisi',
                    'csrf' => $csrf
                ]));
            }

            $this->db->trans_begin();
            $this->Md_siswa->updateSiswa($siswa_id, [
                'nisn' => $nisn,
                'nama' => $nama,
                'jenis_kelamin' => $jenis_kelamin,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'email' => $email,
                'status' => 1
            ]);
            // addLog('Update Data', 'Mengubah data Siswa', 'Siswa ID' . $siswa_id);
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
        $this->Md_siswa->updateSiswa($dataid, array('status' => 2));

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


