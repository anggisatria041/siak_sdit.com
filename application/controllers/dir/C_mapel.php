<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_mapel extends CI_Controller
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
        $this->load->model('Md_mapel');
        $this->load->model('Md_log');
        $this->load->model('Md_kelas'); // Tambahkan model kelas untuk mengambil nama kelas

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
        $configColumn['title'] = array('NO', 'Kode Mapel', 'Nama Mapel', 'Deskripsi', 'Kelas', 'Aksi');
        $configColumn['field'] = array('no', 'kode_mapel', 'nama_mapel', 'deskripsi', 'nama_kelas', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, TRUE, FALSE, TRUE, FALSE);
        $configColumn['width'] = array(30, 100, 70, 300, 100, 50); //on px
        $configColumn['template'] = array(

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
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.mapel_id +\'\\\');"><i class="la la-edit"></i> Edit Mapel</a>\
                                <a class="dropdown-item" href="javascript:hapus(\\\'\'+e.mapel_id+\'\\\');"><i class="la la-trash-o"></i> Hapus Mapel</a>\
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
        $set['id_table'] = 'tableManageMapel'; // tanpa spasi dan karakter
        $set['json_url'] = base_url() . 'dir/api/manage_mapel';
        $set['columns'] = $this->m_datatable->setColumn($configColumn);
        $set['filter'] = FALSE; // wajib
        $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
        $set['server_side'] = TRUE; // wajib
        $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

        $pageData['tableManageMapel'] = $this->m_datatable->generateScript($set);

        $pageData['page_name'] = 'V_mapel';
        $pageData['page_dir'] = 'mapel';
        $pageData['kelas'] = $this->Md_kelas->getKelas();
        $this->load->view('index', $pageData);


    }
    public function add()
    {

        $this->form_validation->set_rules('nama_mapel', '', 'required');
        $this->form_validation->set_rules('deskripsi', '', 'required');
        // $this->form_validation->set_rules('status', '', 'required');
        // $this->form_validation->set_rules('date_created', '', 'required');
        $this->form_validation->set_rules('kode_mapel', '', 'required');
        $this->form_validation->set_rules('kelas_id', '', 'required'); // Tambahkan validasi untuk kelas_id

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

        $mapel_id = decrypt($this->input->post('mapel_id'));
        $nama_mapel = $this->input->post('nama_mapel');
        $deskripsi = $this->input->post('deskripsi');
        $status = $this->input->post('status');
        $date_created = $this->input->post('date_created');
        $kode_mapel = $this->input->post('kode_mapel');
        $kelas_id = $this->input->post('kelas_id'); // Tambahkan untuk mengambil kelas_id

        $dataInsert = array(
            'nama_mapel' => $nama_mapel,
            'deskripsi' => $deskripsi,
            'status' => $status,
            'date_created' => $date_created,
            'kode_mapel' => $kode_mapel,
            'kelas_id' => $kelas_id, // Tambahkan untuk menyimpan kelas_id
            'status' => 1
        );
        $this->db->trans_begin();
        $this->Md_mapel->addMapel($dataInsert);

        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('status' => 'success', 'csrf' => $csrf));
            die;
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 'gagal', 'message' => 'Data Mapel gagal disimpan', 'csrf' => $csrf));
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

        $mapel = $this->Md_mapel->getMapelById(decrypt($argv1));
        // var_dump($mapel);
        // die;

        $row = array();
        if ($mapel) {
            $row['data'] = TRUE;
            $row['mapel_id'] = encrypt($mapel->mapel_id);
            $row['nama_mapel'] = $mapel->nama_mapel;
            $row['deskripsi'] = $mapel->deskripsi;
            $row['status'] = $mapel->status;
            $row['date_created'] = $mapel->date_created;
            $row['kode_mapel'] = $mapel->kode_mapel;
            $row['kelas_id'] = $mapel->kelas_id; // Tambahkan untuk mengambil kelas_id
            $kelas = $this->Md_kelas->getKelasById($mapel->kelas_id); // Mengambil nama kelas berdasarkan kelas_id
            $row['nama_kelas'] = $kelas->nama_kelas; // Tambahkan untuk mengambil nama kelas
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('nama_mapel', '', 'required');
        $this->form_validation->set_rules('deskripsi', '', 'required');
        $this->form_validation->set_rules('kode_mapel', '', 'required');
        $this->form_validation->set_rules('kelas_id', '', 'required'); // Tambahkan validasi untuk kelas_id

        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $mapel_id = $this->input->post('mapel_id') == "" ? NULL : decrypt($this->input->post('mapel_id'));
            $nama_mapel = $this->input->post('nama_mapel') == "" ? NULL : $this->input->post('nama_mapel');
            $deskripsi = $this->input->post('deskripsi') == "" ? NULL : $this->input->post('deskripsi');
            $kode_mapel = $this->input->post('kode_mapel') == "" ? NULL : $this->input->post('kode_mapel');
            $kelas_id = $this->input->post('kelas_id') == "" ? NULL : $this->input->post('kelas_id'); // Tambahkan untuk mengambil kelas_id

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua fill harus terisi',
                    'csrf' => $csrf
                ]));
            }

            $this->db->trans_begin();
            $this->Md_mapel->updateMapel($mapel_id, [
                'nama_mapel' => $nama_mapel,
                'deskripsi' => $deskripsi,
                'kode_mapel' => $kode_mapel,
                'kelas_id' => $kelas_id, // Tambahkan untuk mengupdate kelas_id
                'status' => 1
            ]);
            // addLog('Update Data', 'Mengubah data Mapel, 'GMapelD' . $gumapel);
            if ($this->db->trans_status() == TRUE) {
                $this->db->trans_commit();
                echo json_encode(array('status' => 'success', 'csrf' => $csrf));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'gagal', 'message' => 'Data Mapel gagal disimpan', 'csrf' => $csrf));
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
        $this->Md_mapel->updateMapel($dataid, array('status' => 2));

        // addLog('Delete Data', 'Menghapus data Mapel', 'Mapel ID' . $dataid);
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

