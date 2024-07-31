<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_absensi extends CI_Controller
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
        $this->load->model('Md_guru');
        $this->load->model('Md_log');
        $this->load->model('Md_tahun_ajaran');
        $this->load->model('Md_siswa');
        $this->load->model('Md_absensi');

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
            if (!$this->session->userdata($key))
                continue;
            $validate = true;
            $this->akses = $value;
            break;
        }
        if (!$validate)
            redirect(base_url('lawang'));


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
        $configColumn['title'] = array('NO', 'Kelas', 'Aksi');
        $configColumn['field'] = array('no', 'nama_kelas', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, FALSE);
        $configColumn['width'] = array(30, 100, 100); //on px
        $configColumn['template'] = array(
            FALSE,
            FALSE,
            'function (e) {
                    return \'\
                        <div class="dropdown down">\
                            <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                <i class="la la-gear"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-right">\
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.kelas_id +\'\\\');"><i class="la la-edit"></i> Input Absensi</a>\
                                <a class="dropdown-item" href="javascript:detail(\\\'\'+e.kelas_id+\'\\\');"><i class="la la-trash-o"></i> Detail Absensi</a>\
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
        $set['id_table'] = 'tableManageAbsensi'; // tanpa spasi dan karakter
        $set['json_url'] = base_url() . 'dir/api/manage_absensi';
        $set['columns'] = $this->m_datatable->setColumn($configColumn);
        $set['filter'] = FALSE; // wajib
        $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
        $set['server_side'] = TRUE; // wajib
        $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

        $pageData['tableManageAbsensi'] = $this->m_datatable->generateScript($set);

        $pageData['page_name'] = 'V_absensi';
        $pageData['page_dir'] = 'absensi';
        $this->load->view('index', $pageData);


    }
    public function add()
    {

        $this->form_validation->set_rules('kelas', '', 'required');
        $this->form_validation->set_rules('mata_pelajaran', '', 'required');
        $this->form_validation->set_rules('guru', '', 'required');
        $this->form_validation->set_rules('tanggal', '', 'required');
        $this->form_validation->set_rules('jam_mulai', '', 'required');
        $this->form_validation->set_rules('jam_selesai', '', 'required');

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

        $guru_id = decrypt($this->input->post('guru_id'));
        $niy = $this->input->post('niy');
        $nama_guru = $this->input->post('nama_guru');
        $jenis_kelamin = $this->input->post('jenis_kelamin');
        $agama = $this->input->post('agama');
        $tgl_lahir = $this->input->post('tgl_lahir');
        $alamat = $this->input->post('alamat');
        $pendidikan_terakhir = $this->input->post('pendidikan_terakhir');
        $no_hp = $this->input->post('no_hp');

        $dataInsert = array(
            'niy' => $niy,
            'nama_guru' => $nama_guru,
            'jenis_kelamin' => $jenis_kelamin,
            'agama' => $agama,
            'tgl_lahir' => $tgl_lahir,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'pendidikan_terakhir' => $pendidikan_terakhir,
            'status' => 1
        );
        $this->db->trans_begin();
        $this->Md_guru->addGuru($dataInsert);

        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('status' => 'success', 'csrf' => $csrf));
            die;
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 'gagal', 'message' => 'Data Guru gagal disimpan', 'csrf' => $csrf));
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

        $tajaran = $this->Md_tahun_ajaran->getTahunAjaranAktif();
        $nama_tajaran = $tajaran['nama_tajaran'];
        $tajaran_id = $tajaran['tajaran_id'];
        $siswa = $this->Md_siswa->getSiswaByKelas(decrypt($argv1));

        // var_dump($siswa);
        // die;

        $row = array();
        if ($siswa) {
            $row['data'] = TRUE;
            $row['tajaran_id'] = encrypt($tajaran_id);
            $row['nama_tajaran'] = $nama_tajaran;
            $row['siswa'] = $siswa;

        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('tanggal', '', 'required');
        $this->form_validation->set_rules('keterangan', '', 'required');
        $this->form_validation->set_rules('siswa_hadir[]', '', 'required');


        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $tanggal = $this->input->post('tanggal') == "" ? NULL : $this->input->post('tanggal');
            $keterangan = $this->input->post('keterangan') == "" ? NULL : $this->input->post('keterangan');
            $tajaran_id = $this->input->post('tajaran_id') == "" ? NULL : decrypt($this->input->post('tajaran_id'));
            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua fill harus terisi',
                    'csrf' => $csrf
                ]));
            }
            $siswa_hadir = $this->input->post('siswa_hadir');
            foreach ($siswa_hadir as $key => $value) {
                $nis = $this->Md_siswa->getSiswaByNis($key);
                $absen_valid = $this->Md_absensi->getAbsensiByTanggal($tanggal, $tajaran_id, $nis['nis']);
                if ($absen_valid) {
                    echo json_encode(array('status' => 'gagal', 'message' => 'Data Absensi sudah ada', 'csrf' => $csrf));
                    die;
                }
                $dataInsert[] = array(
                    'tanggal' => $tanggal,
                    'keterangan' => $keterangan,
                    'tajaran_id' => $tajaran_id,
                    'nis' => $nis['nis'],
                    'kehadiran' => $value,
                );
            }



            $this->db->trans_begin();
            $this->Md_absensi->addAbsensi($dataInsert);

            // addLog('Update Data', 'Mengubah data Guru, 'GGuruD' . $guguru);
            if ($this->db->trans_status() == TRUE) {
                $this->db->trans_commit();
                echo json_encode(array('status' => 'success', 'csrf' => $csrf));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'gagal', 'message' => 'Data Guru gagal disimpan', 'csrf' => $csrf));
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
        $this->Md_guru->updateGuru($dataid, array('status' => 2));

        // addLog('Delete Data', 'Menghapus data Guru', 'Guru ID' . $dataid);
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
    public function detail($id_kelas)
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

        $absensi = $this->Md_siswa->getSiswaByKelas(decrypt($id_kelas));
        if ($absensi) {
            $configColumn['title'] = array('NO', 'Nama Siswa');
            $configColumn['field'] = array('no', 'nama_siswa');
            $configColumn['sortable'] = array(FALSE, TRUE);
            $configColumn['width'] = array(30, 100);
            $configColumn['template'] = array(
                FALSE,
                FALSE,
            );

            for ($i = 1; $i <= 31; $i++) {
                $configColumn['title'][] = $i;
                $configColumn['field'][] = 'kehadiran_' . $i;
                $configColumn['sortable'][] = FALSE;
                $configColumn['width'][] = 50; //on px
                $configColumn['template'][] = FALSE;
            }
            $configColumn['title'][] = 'Total Kehadiran';
            $configColumn['field'][] = 'total_kehadiran';
            $configColumn['sortable'][] = FALSE;
            $configColumn['width'][] = 50; //on px
            $configColumn['template'][] =
                'function (e) {
                    return \'\
                        <div class="dropdown down">\
                            <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                <i class="la la-gear"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-right">\
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.kelas_id +\'\\\');"><i class="la la-edit"></i> Edit Absensi</a>\
                                <a class="dropdown-item" href="javascript:detail(\\\'\'+e.kelas_id+\'\\\');"><i class="la la-trash-o"></i> Detail Absensi</a>\
                            </div>\
                        </div>\
                    \';
                    }'
            ;
            $configFilter = FALSE;

            /**
             * @var $set['columns'] -> Mendefinisikan kolom-kolom pada table
             * @var $set['search'] -> Mendefinisikan box searching ditampilkan atau tidak
             * @var $set['filter'] -> Mendefinisikan box filtering bagian kolom tertentu
             * @var $set['URL'] -> Mendefinisikan url mengambil data dari server 
             * @var $set['search'] -> Mendefinisikan box searching ditampilkan atau tidak.
             */
            $set['id_table'] = 'tableManageDetail'; // tanpa spasi dan karakter
            $set['json_url'] = base_url() . 'dir/api/manage_detail/' . encrypt($id_kelas);
            $set['columns'] = $this->m_datatable->setColumn($configColumn);
            $set['filter'] = FALSE; // wajib
            $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set['server_side'] = TRUE; // wajib
            $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

            $pageData['tableManageDetail'] = $this->m_datatable->generateScript($set);

            $pageData['page_name'] = 'V_absensi_detail';
            $pageData['page_dir'] = 'absensi';
            // var_dump($set);
            // die;
            $this->load->view('index', $pageData);
        } else {
            echo "Data absensi tidak ditemukan";
        }
    }
}


