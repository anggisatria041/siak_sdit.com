<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_nilai extends CI_Controller
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
        $this->load->model('Md_mapel');
        $this->load->model('Md_orang_tua');
        $this->load->model('Md_kelas');
        $this->load->model('Md_nilai');
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
        $hakakses =$this->akses;
        $id =decrypt($this->session->userdata('pengguna_id'));
    
        if($hakakses == 'orang_tua'){
            $configColumn['title'] = array('No', 'NIS', 'Nama', 'Mata Pelajaran', 'TP 1', 'TP 2', 'TP 3', 'Tp 4');
            $configColumn['field'] = array('no', 'nis', 'nama', 'nama_mapel', 'tp1', 'tp2', 'tp3', 'tp4');
            $configColumn['sortable'] = array(FALSE, TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE);
            $configColumn['width'] = array(30, 100, 100, 150, 100, 100, 80, 50); //on px
            $configColumn['template'] = array(
            FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE
            );
            $configFilter = FALSE;

            /**
             * @var $set['columns'] -> Mendefinisikan kolom-kolom pada table
             * @var $set['search'] -> Mendefinisikan box searching ditampilkan atau tidak
             * @var $set['filter'] -> Mendefinisikan box filtering bagian kolom tertentu
             * @var $set['URL'] -> Mendefinisikan url mengambil data dari server 
             * @var $set['search'] -> Mendefinisikan box searching ditampilkan atau tidak.
             */
            $set['id_table'] = 'lingkup1'; // tanpa spasi dan karakter
            $set['json_url'] = base_url() . 'dir/api/nilai_lingkup/1/'.$id;
            $set['columns'] = $this->m_datatable->setColumn($configColumn);
            $set['filter'] = FALSE; // wajib
            $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set['server_side'] = TRUE; // wajib
            $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

            $set2['id_table'] = 'lingkup2'; // tanpa spasi dan karakter
            $set2['json_url'] = base_url() . 'dir/api/nilai_lingkup/2';
            $set2['columns'] = $this->m_datatable->setColumn($configColumn);
            $set2['filter'] = FALSE; // wajib
            $set2['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set2['server_side'] = TRUE; // wajib
            $set2['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

            $set3['id_table'] = 'lingkup3'; // tanpa spasi dan karakter
            $set3['json_url'] = base_url() . 'dir/api/nilai_lingkup/3';
            $set3['columns'] = $this->m_datatable->setColumn($configColumn);
            $set3['filter'] = FALSE; // wajib
            $set3['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set3['server_side'] = TRUE; // wajib
            $set3['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

            $set4['id_table'] = 'lingkup4'; // tanpa spasi dan karakter
            $set4['json_url'] = base_url() . 'dir/api/nilai_lingkup/4';
            $set4['columns'] = $this->m_datatable->setColumn($configColumn);
            $set4['filter'] = FALSE; // wajib
            $set4['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set4['server_side'] = TRUE; // wajib
            $set4['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

            $set5['id_table'] = 'lingkup5'; // tanpa spasi dan karakter
            $set5['json_url'] = base_url() . 'dir/api/nilai_lingkup/5';
            $set5['columns'] = $this->m_datatable->setColumn($configColumn);
            $set5['filter'] = FALSE; // wajib
            $set5['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set5['server_side'] = TRUE; // wajib
            $set5['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000


            $pageData['lingkup1'] = $this->m_datatable->generateScript($set);
            $pageData['lingkup2'] = $this->m_datatable->generateScript($set2);
            $pageData['lingkup3'] = $this->m_datatable->generateScript($set3);
            $pageData['lingkup4'] = $this->m_datatable->generateScript($set4);
            $pageData['lingkup5'] = $this->m_datatable->generateScript($set5);
            $pageData['page_name'] = 'V_rekap_nilai';
            $pageData['page_dir'] = 'nilai';
            $this->load->view('index', $pageData);
        }else{
            $pageData['kelas'] = $this->Md_kelas->getkelas();
            $pageData['page_name'] = 'V_nilai';
            $pageData['page_dir'] = 'nilai';
            $this->load->view('index', $pageData);
        }
    }
    public function mata_pelajaran($jenis='', $id='')
    {
            $pageData['mapel'] = $this->Md_mapel->getByIdKelas(decrypt($id));
            $pageData['page_name'] = 'V_mata_pelajaran';
            $pageData['page_dir'] = 'nilai';
            $pageData['jenis'] = $jenis;
            $pageData['kelas_id'] = $id;
            $this->load->view('index', $pageData);
    }
    public function jenis($jenis='', $kelas_id='', $mapel_id='')
    {
        if($jenis == 'tambah'){
            $pageData['siswa'] = $this->Md_siswa->getByIdKelas(decrypt($kelas_id));
            $pageData['mapel_id'] = decrypt($mapel_id);
            $pageData['page_name'] = 'V_input_nilai';
            $pageData['page_dir'] = 'nilai';
            $this->load->view('index', $pageData);
        }else{
            $configColumn['title'] = array('No', 'NIS', 'Nama', 'TP 1', 'TP 2', 'TP 3', 'Tp 4', 'Aksi');
            $configColumn['field'] = array('no', 'nis', 'nama', 'tp1', 'tp2', 'tp3', 'tp4', 'aksi');
            $configColumn['sortable'] = array(FALSE, TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, FALSE);
            $configColumn['width'] = array(30, 100, 100, 100, 100, 80, 50, 100); //on px
            $configColumn['template'] = array(
            FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE,
                'function (e) {
                    return \'\
                        <div class="dropdown down">\
                            <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                <i class="la la-gear"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-right">\
                                <a class="dropdown-item" href="javascript:edit(\\\'\'+e.nilai_id +\'\\\');"><i class="la la-edit"></i> Edit Nilai</a>\
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
            $set['id_table'] = 'lingkup1'; // tanpa spasi dan karakter
            $set['json_url'] = base_url() . 'dir/api/nilai_lingkup/1';
            $set['columns'] = $this->m_datatable->setColumn($configColumn);
            $set['filter'] = FALSE; // wajib
            $set['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set['server_side'] = TRUE; // wajib
            $set['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

            $set2['id_table'] = 'lingkup2'; // tanpa spasi dan karakter
            $set2['json_url'] = base_url() . 'dir/api/nilai_lingkup/2';
            $set2['columns'] = $this->m_datatable->setColumn($configColumn);
            $set2['filter'] = FALSE; // wajib
            $set2['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set2['server_side'] = TRUE; // wajib
            $set2['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

            $set3['id_table'] = 'lingkup3'; // tanpa spasi dan karakter
            $set3['json_url'] = base_url() . 'dir/api/nilai_lingkup/3';
            $set3['columns'] = $this->m_datatable->setColumn($configColumn);
            $set3['filter'] = FALSE; // wajib
            $set3['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set3['server_side'] = TRUE; // wajib
            $set3['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

            $set4['id_table'] = 'lingkup4'; // tanpa spasi dan karakter
            $set4['json_url'] = base_url() . 'dir/api/nilai_lingkup/4';
            $set4['columns'] = $this->m_datatable->setColumn($configColumn);
            $set4['filter'] = FALSE; // wajib
            $set4['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set4['server_side'] = TRUE; // wajib
            $set4['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000

            $set5['id_table'] = 'lingkup5'; // tanpa spasi dan karakter
            $set5['json_url'] = base_url() . 'dir/api/nilai_lingkup/5';
            $set5['columns'] = $this->m_datatable->setColumn($configColumn);
            $set5['filter'] = FALSE; // wajib
            $set5['search'] = TRUE; // jika tidak ingin memunculkan kolom search $row['search'] = FALSE;
            $set5['server_side'] = TRUE; // wajib
            $set5['perpage'] = 10; // wajib : 10/20/30/50/100/500/1000/10000


            $pageData['lingkup1'] = $this->m_datatable->generateScript($set);
            $pageData['lingkup2'] = $this->m_datatable->generateScript($set2);
            $pageData['lingkup3'] = $this->m_datatable->generateScript($set3);
            $pageData['lingkup4'] = $this->m_datatable->generateScript($set4);
            $pageData['lingkup5'] = $this->m_datatable->generateScript($set5);
            $pageData['page_name'] = 'V_rekap_nilai';
            $pageData['page_dir'] = 'nilai';
            $this->load->view('index', $pageData);
        }
            
    }
    public function add()
    {

        $this->form_validation->set_rules('lingkup_materi', 'Lingkup Materi', 'required');
        $this->form_validation->set_rules('mapel_id', 'Mapel', 'required');
        $this->form_validation->set_rules('semester', 'Semester', 'required');
        $this->form_validation->set_rules('tp1[]', 'TP 1', 'required');
        $this->form_validation->set_rules('tp2[]', 'TP 2', 'required');
        $this->form_validation->set_rules('tp3[]', 'TP 3', 'required');
        $this->form_validation->set_rules('tp4[]', 'TP 4', 'required');


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

        $lingkup_materi = $this->input->post('lingkup_materi');
        $mapel_id = $this->input->post('mapel_id');
        $semester = $this->input->post('semester');
        $tp1 = $this->input->post('tp1');
        $tp2 = $this->input->post('tp2');
        $tp3 = $this->input->post('tp3');
        $tp4 = $this->input->post('tp4');

        $nilai=$this->Md_nilai->getNilai();
        foreach($nilai as $list){
            if($list->mapel_id == $mapel_id && $list->lingkup_materi == $lingkup_materi && $list->semester == $semester){
                echo json_encode(array('status' => 'gagal', 'message' => 'Mata Pelajaran ini untuk semester '.$semester.' dan lingkup Materi '.$lingkup_materi.' sudah diinputkan', 'csrf' => $csrf));
                die;
            }
        }

        $this->db->trans_begin();
        foreach ($tp1 as $siswa_id => $value) {
            $dataInsert = array(
                'siswa_id' => $siswa_id,
                'mapel_id' => $mapel_id,
                'tp1' => $value,
                'tp2' => isset($tp2[$siswa_id]) ? $tp2[$siswa_id] : null,
                'tp3' => isset($tp3[$siswa_id]) ? $tp3[$siswa_id] : null,
                'tp4' => isset($tp4[$siswa_id]) ? $tp4[$siswa_id] : null,
                'lingkup_materi' => $lingkup_materi,
                'semester' => $semester,
            );

            $this->Md_nilai->addNilai($dataInsert);
        }


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

        $nilai = $this->Md_nilai->getNilaiById(decrypt($argv1));


        $row = array();
        if ($nilai) {
            $row['data'] = TRUE;
            $row['nilai_id'] = encrypt($nilai->nilai_id);
            $row['nis'] = $nilai->nis;
            $row['nama'] = $nilai->nama;
            $row['tp1'] = $nilai->tp1;
            $row['tp2'] = $nilai->tp2;
            $row['tp3'] = $nilai->tp3;
            $row['tp4'] = $nilai->tp4;
        } else {
            $row['data'] = FALSE;
        }

        echo json_encode($row);
        die;
    }
    public function update()
    {
        $this->form_validation->set_rules('tp1', 'TP 1', 'required');
        $this->form_validation->set_rules('tp2', 'TP 2', 'required');
        $this->form_validation->set_rules('tp3', 'TP 3', 'required');
        $this->form_validation->set_rules('tp4', 'TP 4', 'required');


        $csrf = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($this->form_validation->run() != FALSE) {

            $nilai_id =  $this->input->post('nilai_id') == "" ? NULL : decrypt($this->input->post('nilai_id'));
            $tp1 =  $this->input->post('tp1') == "" ? 0 : $this->input->post('tp1');
            $tp2 =  $this->input->post('tp2') == "" ? 0 : $this->input->post('tp2');
            $tp3 =  $this->input->post('tp3') == "" ? 0 : $this->input->post('tp3');
            $tp4 =  $this->input->post('tp4') == "" ? 0 : $this->input->post('tp4');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'status' => 'gagal',
                    'message' => 'Semua field harus terisi',
                    'csrf' => $csrf
                ]));
            }

            $this->db->trans_begin();
            $this->Md_nilai->updateNilai($nilai_id, [
                'tp1' => $tp1,
                'tp2' => $tp2,
                'tp3' => $tp3,
                'tp4' => $tp4
            ]);

            if ($this->db->trans_status() == TRUE) {
                $this->db->trans_commit();
                echo json_encode(array('status' => 'success', 'csrf' => $csrf));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 'gagal', 'message' => 'Data Nilai gagal disimpan', 'csrf' => $csrf));
                die;
            }
        }
    }
    
}


