<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_guru extends CI_Controller
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
        $configColumn['title'] = array('NO', 'NIY', 'Nama Guru', 'Jenis Kelamin', 'Agama', 'Tanggal Lahir', 'Alamat', 'Pendidikan Terakhir', 'No Hp', 'Aksi');
        $configColumn['field'] = array('no', 'niy', 'nama_guru', 'jenis_kelamin', 'agama', 'tgl_lahir', 'alamat', 'pendidikan_terakhir', 'no_hp', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE);
        $configColumn['width'] = array(30, 50, 50, 100, 100, 80, 50, 50, 50, 100); //on px
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
            'function (e) {
                    return \'\
                        <div class="dropdown down">\
                            <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                <i class="la la-gear"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-right">\
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.guru_id +\'\\\');"><i class="la la-edit"></i> Edit Guru</a>\
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

        $this->form_validation->set_rules('niy', '', 'required');
        $this->form_validation->set_rules('nama_guru', '', 'required');
        $this->form_validation->set_rules('jenis_kelamin', '', 'required');
        $this->form_validation->set_rules('agama', '', 'required');
        $this->form_validation->set_rules('tgl_lahir', '', 'required');
        $this->form_validation->set_rules('alamat', '', 'required');
        $this->form_validation->set_rules('pendidikan_terakhir', '', 'required');
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

        $guru = $this->Md_guru->getGuruById(decrypt($argv1));
        // var_dump($guru);
        // die;

        $row = array();
        if ($guru) {
            $row['data'] = TRUE;
            $row['guru_id'] = encrypt($guru->guru_id);
            $row['niy'] = $guru->niy;
            $row['nama_guru'] = $guru->nama_guru;
            $row['jenis_kelamin'] = $guru->jenis_kelamin;
            $row['tgl_lahir'] = $guru->tgl_lahir;
            $row['agama'] = $guru->agama;
            $row['alamat'] = ($guru->alamat);
            $row['no_hp'] = ($guru->no_hp);
            $row['pendidikan_terakhir'] = ($guru->pendidikan_terakhir);
            $row['status'] = $guru->status;
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('niy', '', 'required');
        $this->form_validation->set_rules('nama_guru', '', 'required');
        $this->form_validation->set_rules('jenis_kelamin', '', 'required');
        $this->form_validation->set_rules('tgl_lahir', '', 'required');
        $this->form_validation->set_rules('agama', '', 'required');
        $this->form_validation->set_rules('alamat', '', 'required');
        $this->form_validation->set_rules('no_hp', '', 'required');
        $this->form_validation->set_rules('pendidikan_terakhir', '', 'required');

        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $guru_id = $this->input->post('guru_id') == "" ? NULL : decrypt($this->input->post('guru_id'));
            $niy = $this->input->post('niy') == "" ? NULL : $this->input->post('niy');
            $nama_guru = $this->input->post('nama_guru') == "" ? NULL : $this->input->post('nama_guru');
            $jenis_kelamin = $this->input->post('jenis_kelamin') == "" ? NULL : $this->input->post('jenis_kelamin');
            $tgl_lahir = $this->input->post('tgl_lahir') == "" ? NULL : $this->input->post('tgl_lahir');
            $agama = $this->input->post('agama') == "" ? NULL : $this->input->post('agama');
            $alamat = $this->input->post('alamat') == "" ? NULL : $this->input->post('alamat');
            $no_hp = $this->input->post('no_hp') == "" ? NULL : $this->input->post('no_hp');
            $pendidikan_terakhir = $this->input->post('pendidikan_terakhir') == "" ? NULL : $this->input->post('pendidikan_terakhir');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua fill harus terisi',
                    'csrf' => $csrf
                ]));
            }

            $this->db->trans_begin();
            $this->Md_guru->updateGuru($guru_id, [
                'niy' => $niy,
                'nama_guru' => $nama_guru,
                'jenis_kelamin' => $jenis_kelamin,
                'tgl_lahir' => $tgl_lahir,
                'agama' => $agama,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'pendidikan_terakhir' => $pendidikan_terakhir,
                'status' => 1
            ]);
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
}


