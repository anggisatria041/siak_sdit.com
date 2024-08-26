<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_Absensi_detail extends CI_Controller
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
    public function index($id_kelas, $bulan)
    { /*             * * FOR CREATE DATA TABLE ** */
        /**
         * @var $config for configuration column and field data table into helper m_datatable
         * @param title    | name of table columns
         * @param field    | record that will be shown into tabl
         * @param sortable | setting each column if it can be sorted
         * @param width    | setting width each column -> default value is FALSE for auto width
         * @param template | making template for displaying record -> default value is FALSE
         */


        if ($this->akses != 'orang_tua') {
            $configColumn['title'] = array('NO', 'Nama Siswa');
            $configColumn['field'] = array('no', 'nama_siswa');
            $configColumn['sortable'] = array(FALSE, TRUE);
            $configColumn['width'] = array(30, 100);
            $configColumn['template'] = array(
                FALSE,
                FALSE,
            );

            for ($i = 1; $i <= 31; $i++) {
                $configColumn['title'][] = 'Tanggal ' . $i;
                $configColumn['field'][] = 'kehadiran_' . $i;
                $configColumn['sortable'][] = FALSE;
                $configColumn['width'][] = 80; //on px
                $configColumn['template'][] = FALSE;
            }
            $tajaran = $this->Md_tahun_ajaran->getTahunAjaranGroup();
            $filter_tajaran = array();
            if ($tajaran) {
                foreach ($tajaran as $list) {
                    //untuk filter
                    $filter_tajaran[] = array('id' => $list->nama_tajaran, 'attr' => $list->nama_tajaran);
                }
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
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.nis +\'\\\');"><i class="la la-edit"></i> Edit Absensi</a>\
                                     </div>\
                        </div>\
                    \';
                    }'
            ;

            $configFilter = array(
                array(
                    'nama_filter' => 'Tahun Ajaran',
                    'id_filter' => 'nama_tajaran',
                    'option_filter' => $filter_tajaran,
                )
            );

            /**
             * @var $set['columns'] -> Mendefinisikan kolom-kolom pada table
             * @var $set['search'] -> Mendefinisikan box searching ditampilkan atau tidak
             * @var $set['filter'] -> Mendefinisikan box filtering bagian kolom tertentu
             * @var $set['URL'] -> Mendefinisikan url mengambil data dari server 
             * @var $set['search'] -> Mendefinisikan box searching ditampilkan atau tidak.
             */
            $set['id_table'] = 'tableManageDetail'; // tanpa spasi dan karakter
            $set['json_url'] = base_url() . 'dir/api/manage_detail/' . $id_kelas . '/' . $bulan;
            $set['columns'] = $this->m_datatable->setColumn($configColumn);
            $set['filter'] = $this->m_datatable->setFilter($configFilter); // wajib
            $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set['server_side'] = TRUE; // wajib
            $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

            $pageData['tableManageDetail'] = $this->m_datatable->generateScript($set);

            $pageData['page_name'] = 'V_absensi_detail';
            $pageData['page_dir'] = 'absensi';
            $pageData['bulan'] = $bulan;
            $pageData['id_kelas'] = $id_kelas;
            $this->load->view('index', $pageData);
        } else {
            $pageData['page_name'] = 'V_absensi_detailSiswa';
            $pageData['page_dir'] = 'absensi';
            $source = getDataForDataTable('Md_absensi', decrypt($id_kelas));

            foreach ($source['data'] as $list) {
                $row = array();
                $row['no'] = ++$source['no'];
                $row['nis'] = $list->nis;
                $row['nama_siswa'] = $list->nama;
                for ($i = 1; $i <= 31; $i++) {
                    $absensi = $this->Md_absensi->getAbsensiByNis($list->nis, $i, $list->tajaran_id, decrypt($id_kelas), $bulan);

                    if ($absensi != null) {
                        $row['kehadiran_' . $i] = $absensi->kehadiran;
                    } else {
                        $row['kehadiran_' . $i] = '-';
                    }
                }

                $data[] = $row;


            }
            // var_dump($data[0]);
            // die;
            $pageData['data'] = $data[0];
            switch ($bulan) {
                case 1:
                    $nama_bulan = 'januari';
                    break;
                case 2:
                    $nama_bulan = 'februari';
                    break;
                case 3:
                    $nama_bulan = 'maret';
                    break;
                case 4:
                    $nama_bulan = 'april';
                    break;
                case 5:
                    $nama_bulan = 'mei';
                    break;
                case 6:
                    $nama_bulan = 'juni';
                    break;
                case 7:
                    $nama_bulan = 'juli';
                    break;
                case 8:
                    $nama_bulan = 'agustus';
                    break;
                case 9:
                    $nama_bulan = 'september';
                    break;
                case 10:
                    $nama_bulan = 'oktober';
                    break;
                case 11:
                    $nama_bulan = 'november';
                    break;
                case 12:
                    $nama_bulan = 'desember';
                    break;
                default:
                    $nama_bulan = 'tidak diketahui';
                    break;
            }

            $pageData['namabulan'] = $nama_bulan;
            $pageData['bulan'] = $bulan;
            $pageData['id_kelas'] = $id_kelas;
            $this->load->view('index', $pageData);
        }

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
        $tajaran = $this->Md_tahun_ajaran->getTahunAjaranAktif();
        $nama_tajaran = $tajaran['nama_tajaran'];
        $tajaran_id = $tajaran['tajaran_id'];
        $siswa = $this->Md_siswa->getSiswaByNis_k($argv1);
        $nis = $siswa['nis'];
        $id_kelas = $siswa['kelas_id'];
        $bulan = $this->input->get('bulan');

        $nama_bulan = $this->Md_tahun_ajaran->getNamaBulan($bulan);

        $row = array();
        if ($siswa) {

            $row['data'] = TRUE;
            $row['tajaran_id'] = encrypt($tajaran_id);
            $row['nama_tajaran'] = $nama_tajaran;
            $row['nama_siswa'] = $siswa['nama'];
            $row['nis'] = $nis;
            $row['siswa'] = $siswa;
            $row['bulan'] = $bulan;
            $row['nama_bulan'] = $nama_bulan;
            for ($i = 1; $i <= 31; $i++) {
                $absensi = $this->Md_absensi->getAbsensiByNis($nis, $i, $tajaran_id, $id_kelas, $bulan);

                if ($absensi != null) {
                    $row['kehadiran_' . $i] = $absensi->kehadiran;
                } else {
                    $row['kehadiran_' . $i] = '-';
                }
            }

        } else {
            $row['data'] = FALSE;
        }


        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('nama_siswa', '', 'required');



        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {
            $tanggal = $this->input->post('tanggal') == "" ? NULL : $this->input->post('tanggal');
            $nis = $this->input->post('nis') == "" ? NULL : $this->input->post('nis');
            $tajaran_id = $this->input->post('tajaran_id') == "" ? NULL : decrypt($this->input->post('tajaran_id'));
            $bulan = $this->input->post('bulan') == "" ? NULL : $this->input->post('bulan');
            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua fill harus terisi',
                    'csrf' => $csrf
                ]));
            }
            $siswa_hadir = $this->input->post('siswa_hadir');
            foreach ($siswa_hadir as $key => $value) {

                $absen_valid = $this->Md_absensi->getAbsensiByTanggal_bulan($key, $bulan, $tajaran_id, $nis);
                if ($absen_valid) {
                    $dataUpdate = array(
                        'kehadiran' => $value,
                    );
                    $this->db->trans_start();
                    if (!empty($dataUpdate)) {
                        $this->Md_absensi->updateAbsensi($absen_valid->absensi_id, $dataUpdate);
                    }
                    $this->db->trans_complete();
                } else {
                    $tahun = 2024;
                    $tanggal_lengkap = $tahun . '-' . $bulan . '-' . $key;
                    $dataInsert[] = array(
                        'tanggal' => $tanggal_lengkap,
                        'tajaran_id' => $tajaran_id,
                        'nis' => $nis,
                        'kehadiran' => $value,
                    );
                }
            }

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

    }
}


