<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_pembayaran extends CI_Controller
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
        $this->load->model('Md_pembayaran');
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
        if ($this->akses == 'orang_tua') {
            $template = 'function (e) {
                    return \'\
                        <div class="dropdown down">\
                            <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                <i class="la la-gear"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-right">\
                                <a class="dropdown-item" href="javascript:uploadBuktiPembayaran(\\\'\'+e.pembayaran_id+\'\\\');"><i class="la la-upload"></i> Upload Bukti Pembayaran</a>\
                            </div>\
                        </div>\
                    \';
                    }';
        } else {
            $template = 'function (e) {
                    return \'\
                        <div class="dropdown down">\
                            <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                <i class="la la-gear"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-right">\
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.pembayaran_id +\'\\\');"><i class="la la-edit"></i> Edit Pembayaran</a>\
                                <a class="dropdown-item" href="javascript:verifikasi(\\\'\'+e.pembayaran_id+\'\\\');"><i class="la la-check-circle"></i> Konfirmasi Status Pembayaran</a>\
                            </div>\
                        </div>\
                    \';
                    }';
        }
        /*             * * FOR CREATE DATA TABLE ** */
        /**
         * @var $config for configuration column and field data table into helper m_datatable
         * @param title    | name of table columns
         * @param field    | record that will be shown into tabl
         * @param sortable | setting each column if it can be sorted
         * @param width    | setting width each column -> default value is FALSE for auto width
         * @param template | making template for displaying record -> default value is FALSE
         */
        $configColumn['title'] = array('NO', 'NIS', 'Nama Siswa', 'Nama Tajaran', 'Nominal', 'Tanggal Pembayaran', 'Status Pembayaran', 'Bukti Pembayaran', 'Catatan', 'AKSI');
        $configColumn['field'] = array('no', 'nis', 'nama_siswa', 'nama_tajaran', 'nominal', 'tanggal_pembayaran', 'status_pembayaran', 'bukti_pembayaran', 'catatan', 'aksi');
        $configColumn['sortable'] = array(FALSE, TRUE, TRUE, TRUE, TRUE, TRUE, FALSE, FALSE, FALSE, FALSE);
        $configColumn['width'] = array(20, 50, 50, 70, 70, 100, 100, 140, 100, 50); //on px
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
            $template,
        );
        $configFilter = FALSE;

        /**
         * @var $set['columns'] -> Mendefinisikan kolom-kolom pada table
         * @var $set['search'] -> Mendefinisikan box searching ditampilkan atau tidak
         * @var $set['filter'] -> Mendefinisikan box filtering bagian kolom tertentu
         * @var $set['URL'] -> Mendefinisikan url mengambil data dari server 
         * @var $set['search'] -> Mendefinisikan box searching ditampilkan atau tidak.
         */
        $set['id_table'] = 'tableManagepembayaran'; // tanpa spasi dan karakter
        $set['json_url'] = base_url() . 'dir/api/manage_pembayaran';
        $set['columns'] = $this->m_datatable->setColumn($configColumn);
        $set['filter'] = FALSE; // wajib
        $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
        $set['server_side'] = TRUE; // wajib
        $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000
        $set['scrollX'] = true; // menambahkan scrolling horizontal

        $pageData['tableManagepembayaran'] = $this->m_datatable->generateScript($set);
        if ($this->session->userdata('hak_akses') == 'orang_tua') {
            $pageData['siswa'] = $this->Md_siswa->getSiswaByNis_k($this->session->userdata('nis'));
          
        } else {
            $pageData['siswa'] = $this->Md_siswa->getsiswa();
           
        }
        $pageData['tajaran'] = $this->Md_tahun_ajaran->getTahunAjaran();
        $pageData['pembayaran'] = $this->Md_pembayaran->getAllPembayaran();
        // var_dump($pageData['pembayaran']);
        // die;
        $pageData['page_name'] = 'V_pembayaran';
        $pageData['page_dir'] = 'pembayaran';
        $this->load->view('index', $pageData);


    }
    public function add()
    {

        $this->form_validation->set_rules('nis', '', 'required');
        $this->form_validation->set_rules('nama_tajaran', '', 'required');
        $this->form_validation->set_rules('nominal', '', 'required');
        $this->form_validation->set_rules('status_pembayaran', '', 'required');


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

        $nis = $this->input->post('nis');
        $tajaran_id = $this->input->post('nama_tajaran');
        $nominal = $this->input->post('nominal');
        $tanggal_pembayaran = $this->input->post('tanggal_pembayaran');
        $status_pembayaran = $this->input->post('status_pembayaran');
        $bukti_pembayaran = $this->input->post('bukti_pembayaran');
        $catatan = $this->input->post('catatan');

        $dataInsert = array(
            'nis' => $nis,
            'tajaran_id' => $tajaran_id,
            'nominal' => $nominal,
            'tanggal_pembayaran' => $tanggal_pembayaran,
            'status_pembayaran' => $status_pembayaran,
            'bukti_pembayaran' => $bukti_pembayaran,
            'catatan' => $catatan,
            'status' => 1
        );
        $upload_file = $_FILES['bukti_pembayaran']['name'];
        if ($upload_file) {
            $file_name = str_replace(' ', '_', $upload_file);
            $new_file_name = preg_replace('/\.(?=.*\.)/', '_', $file_name);
            $tipe_file = pathinfo($new_file_name, PATHINFO_EXTENSION);
            $file_size_kb = $_FILES['bukti_pembayaran']['size'] / 1024;

            $config['allowed_types'] = 'pdf|csv|docx|xls|xlsx|png|jpg|jpeg';
            $config['max_size'] = '10240';
            $config['upload_path'] = './assets/upload/bukti_pembayaran/';
            $config['file_name'] = $new_file_name;

            if (!in_array($tipe_file, explode('|', $config['allowed_types']))) {
                jsonResult(false, 'Jenis file tidak diizinkan. Silakan pilih file dengan tipe: ' . $config['allowed_types'], NULL);
            } elseif ($file_size_kb > $config['max_size']) {
                jsonResult(false, 'Ukuran file melebihi batas maksimum: ' . $config['max_size'] . ' KB', NULL);
            } else {
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('bukti_pembayaran')) {
                    $new_image = $this->upload->data('file_name');
                    $dataInsert['bukti_pembayaran'] = $new_file_name;
                } else {
                    echo $this->upload->display_errors();
                }
            }
        }
        // var_dump($new_file_name);
        // die;
        $this->db->trans_begin();
        $this->Md_pembayaran->addPembayaran($dataInsert);

        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('status' => 'success', 'csrf' => $csrf));
            die;
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 'gagal', 'message' => 'Data Pembayaran gagal disimpan', 'csrf' => $csrf));
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

        $pembayaran = $this->Md_pembayaran->getPembayaranById(decrypt($argv1));
        // var_dump($pembayaran);
        // die;

        $row = array();
        if ($pembayaran) {
            $row['data'] = TRUE;
            $row['pembayaran_id'] = encrypt($pembayaran->pembayaran_id);
            $row['nis'] = $pembayaran->nis;
            $row['tajaran_id'] = $pembayaran->tajaran_id;
            $row['nominal'] = $pembayaran->nominal;
            $row['tanggal_pembayaran'] = $pembayaran->tanggal_pembayaran;
            $row['status_pembayaran'] = $pembayaran->status_pembayaran;
            $row['bukti_pembayaran'] = $pembayaran->bukti_pembayaran;
            $row['catatan'] = $pembayaran->catatan;
            $row['status'] = $pembayaran->status;
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function verifikasi($argv1 = '')
    {

        $valid = $argv1 == '' ? FALSE : (is_int(decrypt($argv1)) ? TRUE : FALSE);
        if (!$valid) {
            echo json_encode(array('data' => FALSE));
            die;
        }

        $pembayaran = $this->Md_pembayaran->getPembayaranById(decrypt($argv1));
        // var_dump($pembayaran);
        // die;

        $row = array();
        if ($pembayaran) {
            $row['data'] = TRUE;
            $row['pembayaran_id'] = encrypt($pembayaran->pembayaran_id);
            $row['nis'] = $pembayaran->nis;
            $row['tajaran_id'] = $pembayaran->tajaran_id;
            $row['nominal'] = $pembayaran->nominal;
            $row['tanggal_pembayaran'] = $pembayaran->tanggal_pembayaran;
            $row['status_pembayaran'] = $pembayaran->status_pembayaran;
            $row['bukti_pembayaran'] = $pembayaran->bukti_pembayaran;
            $row['catatan'] = $pembayaran->catatan;
            $row['status'] = $pembayaran->status;
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }

    public function update()
    {
        $this->form_validation->set_rules('nis', '', 'required');
        $this->form_validation->set_rules('nama_tajaran', '', 'required');
        $this->form_validation->set_rules('nominal', '', 'required');
        $this->form_validation->set_rules('status_pembayaran', '', 'required');
        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $pembayaran_id = $this->input->post('pembayaran_id') == "" ? NULL : decrypt($this->input->post('pembayaran_id'));
            $nis = $this->input->post('nis') == "" ? NULL : $this->input->post('nis');
            $tajaran_id = $this->input->post('nama_tajaran') == "" ? NULL : $this->input->post('nama_tajaran');
            $nominal = $this->input->post('nominal') == "" ? NULL : $this->input->post('nominal');
            $tanggal_pembayaran = $this->input->post('tanggal_pembayaran') == "" ? NULL : $this->input->post('tanggal_pembayaran');
            $status_pembayaran = $this->input->post('status_pembayaran') == "" ? NULL : $this->input->post('status_pembayaran');
            $bukti_pembayaran = $this->input->post('bukti_pembayaran') == "" ? NULL : $this->input->post('bukti_pembayaran');
            $catatan = $this->input->post('catatan') == "" ? NULL : $this->input->post('catatan');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua fill harus terisi',
                    'csrf' => $csrf
                ]));
            }
            $updatedata = array(
                'nis' => $nis,
                'tajaran_id' => $tajaran_id,
                'nominal' => $nominal,
                'tanggal_pembayaran' => $tanggal_pembayaran,
                'status_pembayaran' => $status_pembayaran,
                'catatan' => $catatan,
            );
            $upload_file = $_FILES['bukti_pembayaran']['name'];
            if ($upload_file) {
                $file_name = str_replace(' ', '_', $upload_file);
                $new_file_name = preg_replace('/\.(?=.*\.)/', '_', $file_name);
                $tipe_file = pathinfo($new_file_name, PATHINFO_EXTENSION);
                $file_size_kb = $_FILES['bukti_pembayaran']['size'] / 1024;

                $config['allowed_types'] = 'pdf|csv|docx|xls|xlsx|png|jpg|jpeg';
                $config['max_size'] = '10240';
                $config['upload_path'] = './assets/upload/bukti_pembayaran/';
                $config['file_name'] = $new_file_name;

                if (!in_array($tipe_file, explode('|', $config['allowed_types']))) {
                    jsonResult(false, 'Jenis file tidak diizinkan. Silakan pilih file dengan tipe: ' . $config['allowed_types'], NULL);
                } elseif ($file_size_kb > $config['max_size']) {
                    jsonResult(false, 'Ukuran file melebihi batas maksimum: ' . $config['max_size'] . ' KB', NULL);
                } else {
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('bukti_pembayaran')) {
                        $new_image = $this->upload->data('file_name');
                        $updatedata['bukti_pembayaran'] = $new_file_name;
                    } else {
                        echo $this->upload->display_errors();
                    }
                }
            }

            $this->db->trans_begin();
            $this->Md_pembayaran->updatePembayaran($pembayaran_id, $updatedata);

            // addLog('Update Data', 'Mengubah data Pembayaran, 'GPembayaranD' . $gupembayaran);
            if ($this->db->trans_status() == TRUE) {
                $this->db->trans_commit();
                echo json_encode(array('status' => 'success', 'csrf' => $csrf));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'gagal', 'message' => 'Data Pembayaran gagal disimpan', 'csrf' => $csrf));
                die;
            }
        }
    }
    public function updateverifikasi()
    {

        $this->form_validation->set_rules('status_pembayaran', '', 'required');
        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $pembayaran_id = $this->input->post('pembayaran_id') == "" ? NULL : decrypt($this->input->post('pembayaran_id'));
            $status_pembayaran = $this->input->post('status_pembayaran') == "" ? NULL : $this->input->post('status_pembayaran');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua fill harus terisi',
                    'csrf' => $csrf
                ]));
            }
            $file_bukti_pembayaran = $_FILES['file_bukti_pembayaran']['name'];
            $updatedata = array(
                'status_pembayaran' => $status_pembayaran,
            );
            if ($file_bukti_pembayaran) {
                $upload_file = $_FILES['file_bukti_pembayaran']['name'];
                $file_name = str_replace(' ', '_', $upload_file);
                $new_file_name = preg_replace('/\.(?=.*\.)/', '_', $file_name);
                $tipe_file = pathinfo($new_file_name, PATHINFO_EXTENSION);
                $file_size_kb = $_FILES['file_bukti_pembayaran']['size'] / 1024;

                $config['allowed_types'] = 'pdf|csv|docx|xls|xlsx|png|jpg|jpeg';
                $config['max_size'] = '10240';
                $config['upload_path'] = './assets/upload/bukti_pembayaran/';
                $config['file_name'] = $new_file_name;

                if (!in_array($tipe_file, explode('|', $config['allowed_types']))) {
                    echo json_encode([
                        'status' => 'gagal',
                        'message' => 'Jenis file tidak diizinkan. Silakan pilih file dengan tipe: ' . $config['allowed_types'],
                        'csrf' => $csrf
                    ]);
                    die;
                } elseif ($file_size_kb > $config['max_size']) {
                    echo json_encode([
                        'status' => 'gagal',
                        'message' => 'Ukuran file melebihi batas maksimum: ' . $config['max_size'] . ' KB',
                        'csrf' => $csrf
                    ]);
                    die;
                } else {
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('file_bukti_pembayaran')) {
                        $new_image = $this->upload->data('file_name');
                        $updatedata = array(
                            'bukti_pembayaran' => $new_image,
                        );
                    } else {
                        echo $this->upload->display_errors();
                        die;
                    }
                }
            }

            $this->db->trans_begin();
            $this->Md_pembayaran->updatePembayaran($pembayaran_id, $updatedata);

            // addLog('Update Data', 'Mengubah data Pembayaran, 'GPembayaranD' . $gupembayaran);
            if ($this->db->trans_status() == TRUE) {
                $this->db->trans_commit();
                echo json_encode(array('status' => 'success', 'csrf' => $csrf));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'gagal', 'message' => 'Data Pembayaran gagal disimpan', 'csrf' => $csrf));
                die;
            }
        }
    }
    public function uploadBuktiPembayaran()
    {
        var_dump($this->input->post('id'));
        die;

        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        $pembayaran_id = $this->input->post('pembayaran_id_') == "" ? NULL : decrypt($this->input->post('pembayaran_id_'));
        $file_bukti_pembayaran = $this->input->post('file_bukti_pembayaran') == "" ? NULL : $this->input->post('file_bukti_pembayaran');

        if ($file_bukti_pembayaran) {
            $upload_file = $_FILES['file_bukti_pembayaran']['name'];
            $file_name = str_replace(' ', '_', $upload_file);
            $new_file_name = preg_replace('/\.(?=.*\.)/', '_', $file_name);
            $tipe_file = pathinfo($new_file_name, PATHINFO_EXTENSION);
            $file_size_kb = $_FILES['file_bukti_pembayaran']['size'] / 1024;

            $config['allowed_types'] = 'pdf|csv|docx|xls|xlsx|png|jpg|jpeg';
            $config['max_size'] = '10240';
            $config['upload_path'] = './assets/upload/bukti_pembayaran/';
            $config['file_name'] = $new_file_name;

            if (!in_array($tipe_file, explode('|', $config['allowed_types']))) {
                echo json_encode([
                    'status' => 'gagal',
                    'message' => 'Jenis file tidak diizinkan. Silakan pilih file dengan tipe: ' . $config['allowed_types'],
                    'csrf' => $csrf
                ]);
                die;
            } elseif ($file_size_kb > $config['max_size']) {
                echo json_encode([
                    'status' => 'gagal',
                    'message' => 'Ukuran file melebihi batas maksimum: ' . $config['max_size'] . ' KB',
                    'csrf' => $csrf
                ]);
                die;
            } else {
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('file_bukti_pembayaran')) {
                    $new_image = $this->upload->data('file_name');
                    $updatedata = array(
                        'bukti_pembayaran' => $new_image,
                    );
                } else {
                    echo $this->upload->display_errors();
                    die;
                }
            }
        } else {
            echo json_encode([
                'status' => 'gagal',
                'message' => 'Tidak ada file yang diupload',
                'csrf' => $csrf
            ]);
            die;
        }

        $this->db->trans_begin();
        $this->Md_pembayaran->updatePembayaran($pembayaran_id, $updatedata);

        if ($this->db->trans_status() == TRUE) {
            $this->db->trans_commit();
            echo json_encode(array('status' => 'success', 'csrf' => $csrf));
            die;
        } else {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 'gagal', 'message' => 'Data Pembayaran gagal disimpan', 'csrf' => $csrf));
            die;
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
        $this->Md_pembayaran->updatePembayaran($dataid, array('status' => 2));

        // addLog('Delete Data', 'Menghapus data Pembayaran', 'Pembayaran ID' . $dataid);
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
