<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_siswa extends CI_Controller
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
        $this->load->model('Md_kelas');
        $this->load->model('Md_log');
        $this->load->model('Md_orang_tua');

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
        $configColumn['title'] = array('No', 'NIS', 'Nama', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Alamat', 'No HP', 'Kelas', 'Nama Ayah', 'Nama Ibu', 'Aksi');
        $configColumn['field'] = array('no', 'nis', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat', 'no_hp', 'nama_kelas', 'nama_ayah', 'nama_ibu', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE);
        $configColumn['width'] = array(30, 50, 50, 100, 100, 80, 50, 100, 50, 60, 100, 100, 80); //on px
        $configColumn['template'] = array(
            FALSE,
            FALSE,
            FALSE,
            FALSE,
            FALSE,
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
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.siswa_id +\'\\\');"><i class="la la-edit"></i> Edit Siswa</a>\
                                <a class="dropdown-item" href="javascript:hapus(\\\'\'+e.siswa_id+\'\\\');"><i class="la la-trash-o"></i> Hapus Siswa</a>\
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
        $pageData['orang_tua'] = $this->Md_orang_tua->getOrangTua();
        $pageData['kelas'] = $this->Md_kelas->getkelas();
        $pageData['page_name'] = 'V_siswa';
        $pageData['page_dir'] = 'siswa';
        $this->load->view('index', $pageData);


    }
    public function add()
    {

        $this->form_validation->set_rules('nis', '', 'required');
        $this->form_validation->set_rules('nama', '', 'required');
        $this->form_validation->set_rules('jenis_kelamin', '', 'required');
        $this->form_validation->set_rules('tempat_lahir', '', 'required');
        $this->form_validation->set_rules('tanggal_lahir', '', 'required');
        $this->form_validation->set_rules('agama', '', 'required');
        $this->form_validation->set_rules('alamat', '', 'required');
        $this->form_validation->set_rules('no_hp', '', 'required');

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
        $id_orang_tua = decrypt($this->input->post('id_orang_tua'));
        $kelas_id = decrypt($this->input->post('kelas_id'));
        $nis = $this->input->post('nis');
        $nama = $this->input->post('nama');
        $jenis_kelamin = $this->input->post('jenis_kelamin');
        $tempat_lahir = $this->input->post('tempat_lahir');
        $tanggal_lahir = $this->input->post('tanggal_lahir');
        $agama = $this->input->post('agama');
        $alamat = $this->input->post('alamat');
        $no_hp = $this->input->post('no_hp');

        $dataInsert = array(
            'id_orang_tua' => $id_orang_tua,
            'kelas_id' => $kelas_id,
            'nis' => $nis,
            'nama' => $nama,
            'jenis_kelamin' => $jenis_kelamin,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'agama' => $agama,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
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
            $row['id_orang_tua'] = encrypt($siswa->id_orang_tua);
            $row['kelas_id'] = encrypt($siswa->kelas_id);
            $row['siswa_id'] = encrypt($siswa->siswa_id);
            $row['nis'] = $siswa->nis;
            $row['nama'] = $siswa->nama;
            $row['jenis_kelamin'] = $siswa->jenis_kelamin;
            $row['tempat_lahir'] = $siswa->tempat_lahir;
            $row['tanggal_lahir'] = $siswa->tanggal_lahir;
            $row['agama'] = $siswa->agama;
            $row['alamat'] = ($siswa->alamat);
            $row['no_hp'] = ($siswa->no_hp);
            $row['status'] = $siswa->status;
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('nis', '', 'required');
        $this->form_validation->set_rules('nama', '', 'required');
        $this->form_validation->set_rules('jenis_kelamin', '', 'required');
        $this->form_validation->set_rules('tempat_lahir', '', 'required');
        $this->form_validation->set_rules('tanggal_lahir', '', 'required');
        $this->form_validation->set_rules('agama', '', 'required');
        $this->form_validation->set_rules('alamat', '', 'required');
        $this->form_validation->set_rules('no_hp', '', 'required');

        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $id_orang_tua = $this->input->post('id_orang_tua') == "" ? NULL : decrypt($this->input->post('id_orang_tua'));
            $kelas_id = $this->input->post('kelas_id') == "" ? NULL : decrypt($this->input->post('kelas_id'));
            $siswa_id = $this->input->post('siswa_id') == "" ? NULL : decrypt($this->input->post('siswa_id'));
            $nis = $this->input->post('nis') == "" ? NULL : $this->input->post('nis');
            $nama = $this->input->post('nama') == "" ? NULL : $this->input->post('nama');
            $jenis_kelamin = $this->input->post('jenis_kelamin') == "" ? NULL : $this->input->post('jenis_kelamin');
            $tempat_lahir = $this->input->post('tempat_lahir') == "" ? NULL : $this->input->post('tempat_lahir');
            $tanggal_lahir = $this->input->post('tanggal_lahir') == "" ? NULL : $this->input->post('tanggal_lahir');
            $agama = $this->input->post('agama') == "" ? NULL : $this->input->post('agama');
            $alamat = $this->input->post('alamat') == "" ? NULL : $this->input->post('alamat');
            $no_hp = $this->input->post('no_hp') == "" ? NULL : $this->input->post('no_hp');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua fill harus terisi',
                    'csrf' => $csrf
                ]));
            }

            $this->db->trans_begin();
            $this->Md_siswa->updateSiswa($siswa_id, [
                'id_orang_tua' => $id_orang_tua,
                'kelas_id' => $kelas_id,
                'nis' => $nis,
                'nama' => $nama,
                'jenis_kelamin' => $jenis_kelamin,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'agama' => $agama,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
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


