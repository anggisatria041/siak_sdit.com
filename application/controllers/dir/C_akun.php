<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_akun extends CI_Controller
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
        $this->load->model('Md_akun');
        $this->load->model('Md_guru');
        $this->load->model('Md_siswa');
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
        $configColumn['title'] = array('NO', 'Username', 'Role', 'Status', 'Aksi');
        $configColumn['field'] = array('no', 'username', 'role', 'status', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, TRUE, FALSE, FALSE);
        $configColumn['width'] = array(30, 100, 70, 100, 50); //on px
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
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.akun_id +\'\\\');"><i class="la la-edit"></i> Edit Akun</a>\
                                <a class="dropdown-item" href="javascript:hapus(\\\'\'+e.akun_id+\'\\\');"><i class="la la-trash-o"></i> Hapus Akun</a>\
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
        $set['id_table'] = 'tableManageAkun'; // tanpa spasi dan karakter
        $set['json_url'] = base_url() . 'dir/api/manage_akun';
        $set['columns'] = $this->m_datatable->setColumn($configColumn);
        $set['filter'] = FALSE; // wajib
        $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
        $set['server_side'] = TRUE; // wajib
        $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

        $pageData['tableManageAkun'] = $this->m_datatable->generateScript($set);

        $pageData['page_name'] = 'V_akun';
        $pageData['page_dir'] = 'akun';
        $pageData['guru'] = $this->Md_guru->getguru();
        $pageData['siswa'] = $this->Md_siswa->getsiswa();
        // var_dump($pageData);
        // die;
        $this->load->view('index', $pageData);


    }
    public function add()
    {

        $this->form_validation->set_rules('username', '', 'required');
        $this->form_validation->set_rules('role', '', 'required');
        $this->form_validation->set_rules('password', '', 'required');
        $role = $this->input->post('role');
        if ($role == 'admin' || $role == 'guru') {
            $this->form_validation->set_rules('niy', '', 'required');
        } elseif ($role == 'orang tua') {
            $this->form_validation->set_rules('nis', '', 'required');
        }

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

        $akun_id = decrypt($this->input->post('akun_id'));
        $username = $this->input->post('username');
        $role = $this->input->post('role');
        $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $niy = $this->input->post('niy');
        $nis = $this->input->post('nis');

        $dataInsert = array(
            'username' => $username,
            'role' => $role,
            'password' => $password,
            'date_created' => date('Y-m-d H:i:s')
        );
        $role = $this->input->post('role');
        if ($role == 'admin' || $role == 'guru') {
            $dataInsert['niy'] = $niy;
        } elseif ($role == 'orang tua') {
            $dataInsert['nis'] = $nis;
        }
        $this->db->trans_begin();
        $this->Md_akun->addAkun($dataInsert);

        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('status' => 'success', 'csrf' => $csrf));
            die;
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 'gagal', 'message' => 'Data Akun gagal disimpan', 'csrf' => $csrf));
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

        $akun = $this->Md_akun->getAkunById(decrypt($argv1));
        // var_dump($akun);
        // die;

        $row = array();
        if ($akun) {
            $row['data'] = TRUE;
            $row['akun_id'] = encrypt($akun->akun_id);
            $row['username'] = $akun->username;
            $row['role'] = $akun->role;
            $row['password'] = $akun->password;
            $row['niy'] = $akun->niy;
            $row['nis'] = $akun->nis;
            $row['status'] = $akun->status;
            $row['date_created'] = $akun->date_created;
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('username', '', 'required');
        $this->form_validation->set_rules('role', '', 'required');
        $this->form_validation->set_rules('password', '', 'required');
        $role = $this->input->post('role');
        if ($role == 'admin' || $role == 'guru') {
            $this->form_validation->set_rules('niy', '', 'required');
        } elseif ($role == 'orang tua') {
            $this->form_validation->set_rules('nis', '', 'required');
        }


        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $akun_id = $this->input->post('akun_id') == "" ? NULL : decrypt($this->input->post('akun_id'));
            $username = $this->input->post('username') == "" ? NULL : $this->input->post('username');
            $role = $this->input->post('role') == "" ? NULL : $this->input->post('role');
            $password = $this->input->post('password') == "" ? NULL : password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $niy = $this->input->post('niy') == "" ? NULL : $this->input->post('niy');
            $nis = $this->input->post('nis') == "" ? NULL : $this->input->post('nis');
            $status = $this->input->post('status') == "" ? NULL : $this->input->post('status');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua fill harus terisi',
                    'csrf' => $csrf
                ]));
            }
            $update = array(
                'username' => $username,
                'role' => $role,
                'password' => $password,
            );
            $role = $this->input->post('role');
            if ($role == 'admin' || $role == 'guru') {
                $update['niy'] = $niy;
            } elseif ($role == 'orang tua') {
                $update['nis'] = $nis;
            }

            $this->db->trans_begin();
            $this->Md_akun->updateAkun($akun_id, $update);


            // addLog('Update Data', 'Mengubah data Akun, 'GMapelD' . $gumapel);
            if ($this->db->trans_status() == TRUE) {
                $this->db->trans_commit();
                echo json_encode(array('status' => 'success', 'csrf' => $csrf));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'gagal', 'message' => 'Data Akun gagal disimpan', 'csrf' => $csrf));
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
        $this->Md_akun->updateAkun($dataid, array('status' => 2));

        // addLog('Delete Data', 'Menghapus data Akun', 'Akun ID' . $dataid);
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

