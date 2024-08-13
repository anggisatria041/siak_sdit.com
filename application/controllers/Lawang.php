<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lawang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Md_siswa');
        $this->load->model('Md_log');
        $this->load->model('Md_tahun_ajaran');
        $this->load->model('Md_akun');

        /**
         * @filesource libraries/Recaptcha.php
         * @filesource config/recaptcha.php
         */

        $this->load->config('recaptcha');

        $this->load->library('M_datatable');
        $this->load->helper('sso');
        $this->load->helper('encryption_id');
        $this->load->helper('log');

        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        # Check Cookie
        if (!$this->session->flashdata('invalid_cookie')) {
            if (checkCookie())
                redirect(base_url() . 'lawang/cekLogin');
        }

        $pageData['page_name'] = 'lawang';
        $pageData['recaptcha'] = $this->config->item('recaptcha_site_key');
        $this->load->view('lawang/index', $pageData);
    }

    function cekLogin()
    {
      
        if (checkCookie() == true) { //login with jwt

            $payload = getPayload();
            if ($payload->auth_login != null) {
                $siswaId = $this->encryption->decrypt($payload->auth_login);
                $dt_siswa = $this->Md_siswa->getSiswaById($siswaId);
                if ($dt_siswa) {
                    $this->confirm($dt_siswa);
                } else {
                    # data akun tidak ada atau tidak aktif
                    $this->session->set_flashdata('invalid_cookie', true);
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('message', 'Anda tidak memiliki izin akses');
                    redirect(base_url() . 'lawang', 'refresh');
                }
            } else {
                # data akun tidak ada atau tidak aktif
                $this->session->set_flashdata('invalid_cookie', true);
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('message', 'Anda tidak memiliki izin akses');
                redirect(base_url() . 'lawang', 'refresh');
            }
        } else {

            //memastikan ada request POST
            if (!isset($_POST['username']) or !isset($_POST['password']))
                redirect(base_url() . 'lawang', 'refresh');

            //memastikan data post bukan array ( acunetix test )
            if (is_array($this->input->post('password')))
                redirect(base_url() . 'lawang', 'refresh');
            if (is_array($this->input->post('username')))
                redirect(base_url() . 'lawang', 'refresh');

            // memastikan data required terisi
            // mencegah kemungkinan adanya expert user remove attr required HTML
            $this->form_validation->set_rules('username', 'field username', 'required');
            $this->form_validation->set_rules('password', 'field password', 'required');
            $this->form_validation->set_rules('g-recaptcha_response', 'field recaptcha', 'required');

            //siteKey and SecretKey Google
            $secretKey = $this->config->item('recaptcha_secret_key');
            $siteKey = $this->config->item('recaptcha_site_key');

            define($secretKey, $siteKey);

            // call curl to POST request
            if ($this->form_validation->run() != FALSE) {
                $username = strtolower(trim($this->input->post('username')));
                $password = $this->input->post('password');
                $dt_akun = $this->Md_akun->getAkunByNikOrUsername($username);
                
                // jika data akun ada
                if ($dt_akun) {
                    //cek password dengan metode password hash
                    if (password_verify($password, $dt_akun->password)) {
                        $this->confirm($dt_akun);
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('message', 'Password Anda Salah, Silahkan Ulangi');
                        redirect(base_url() . 'lawang', 'refresh');
                    }
                } else { //data akun tidak ada atau tidak aktif
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('message', 'Anda tidak memiliki izin akses');
                    redirect(base_url() . 'lawang', 'refresh');
                }
            } else { //form validasi false
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('message', 'Seluruh field wajib');
                redirect(base_url() . 'lawang', 'refresh');
            }
            // var_dump($data,$getData->penggunaid);die;
        }
    }

    //function konfirmasi login
    public function confirm($dt_akun)
    {
        $array_items = array('admin', 'orang tua', 'guru');
        $this->session->unset_userdata($array_items);
        $dt = $dt_akun->niy ? $dt_akun->niy : ($dt_akun->nis ? $dt_akun->nis : $dt_akun->username);
        $pengguna = $this->Md_akun->getAkunByNikOrUsername($dt);
        if ($pengguna) {
            // set login
            $hakakses = array();

                //hak akses di set Spadmin
                if ($pengguna->role == 'admin') {
                    $hakakses['admin'] = TRUE;
                }
                //hak akses di set admin transport
                if ($pengguna->role == 'orang tua') {
                    $hakakses['orang_tua'] = TRUE;
                }
                //hak akses di set admin mekanik
                if ($pengguna->role == 'guru') {
                    $hakakses['guru'] = TRUE;
                }


            // menyimpan data akun_id di session
            // $this->session->set_userdata(['auth_login' => encrypt($dt_akun->akun_id)]);
            if($pengguna->role == 'admin'){
                 $data = array(
                    'is_admin' => TRUE,
                    'username' => $dt_akun->username,
                    'hak_akses' => 'admin',
                    'pengguna_id' => encrypt($dt_akun->akun_id),
                );
            }elseif($pengguna->role == 'guru'){
                $data = array(
                    'is_guru' => TRUE,
                    'username' => $dt_akun->username,
                    'hak_akses' => 'guru',
                    'pengguna_id' => encrypt($dt_akun->akun_id),
                );
            }else{
                $data = array(
                    'is_ortu' => TRUE,
                    'username' => $dt_akun->username,
                    'hak_akses' => 'orang_tua',
                    'nis' => $dt_akun->nis,
                    'pengguna_id' => encrypt($dt_akun->akun_id),
                );
            }
           

            $this->session->set_userdata($data);
            // addLog('Login', 'as Admin Mekanik', 'Karyawan ID ' . $penggunaData->akunid);
            redirect(base_url() . 'C_dashboard', 'refresh');
            // $pageData['recaptcha'] = $this->config->item('recaptcha_site_key');
            // $pageData['hak_akses'] = $hakakses;
            // $pageData['page_dir'] = 'dashboard';
            // $pageData['page_name'] = 'V_dashboard';
            // $this->load->view('index', $pageData);
        } else {
            $this->session->set_flashdata('invalid_cookie', true);
            $this->session->set_flashdata('alert', 'danger');
            $this->session->set_flashdata('message', 'Anda belum mempunyai hak akses untuk sistem ini, hubungi administrator');
            redirect(base_url() . 'lawang', 'refresh');
        }
    }

    public function auth()
    {
        // jika tidak ada parameter get auth
        if (!isset($_GET['auth']))
            redirect(base_url() . 'lawang', 'refresh');

        // ambil data dari parameter
        $auth = $this->input->get('auth');
        $x = md5('spadmin');
        $y = md5('mekanik');
        $c = md5('transport');
        $w = md5('warehouse');
        $f = md5('fuelman');

        # Create Cookie
        createCookie();

        if ($auth == $y) {  // jika autentikasi hakakses = pegawai biasa
            $penggunaData = $this->Md_akun->getKaryawanById(decrypt($this->session->userdata('auth_login')), 1);
            $getData = $this->Md_pengguna->GetHakByDivisi($penggunaData->akunid, 'Admin Mekanik', 'Transport');
            $cekbengkelkepala = $this->Md_bengkel_kepala->getBengkelKepalaByKaryawanId($penggunaData->akunid);
            $is_bengkelkepala = false;
            if (!empty($cekbengkelkepala)) {
                $is_bengkelkepala = true;
            }
            $data = array(
                'is_mekanik' => TRUE,
                'is_bengkelkepala' => $is_bengkelkepala,
                'akun_id' => encrypt($penggunaData->akunid),
                'nama' => $penggunaData->nama,
                'pengguna_id' => encrypt($getData->penggunaid),
                'foto' => $penggunaData->foto,
            );

            $this->session->set_userdata($data);
            addLog('Login', 'as Admin Mekanik', 'Karyawan ID ' . $penggunaData->akunid);
            redirect(base_url() . 'mekanik', 'refresh');
        } else if ($auth == $c) {
            $penggunaData = $this->Md_akun->getKaryawanById(decrypt($this->session->userdata('auth_login')), 1);
            $getData = $this->Md_pengguna->GetHakByDivisi($penggunaData->akunid, 'Admin Transport', 'Transport');
            $cekkepalatransport = $this->Md_kepala_transport->getKepalaTransportByKaryawanId($penggunaData->akunid);
            $is_kepalatransport = false;
            if (!empty($cekkepalatransport)) {
                $is_kepalatransport = true;
            }
            $data = array(
                'is_transport' => TRUE,
                'is_kepalatransport' => $is_kepalatransport,
                'akun_id' => encrypt($penggunaData->akunid),
                'nama' => $penggunaData->nama,
                'pengguna_id' => encrypt($getData->penggunaid),
                'foto' => $penggunaData->foto,
            );
            // var_dump($data,$getData->penggunaid);die;
            $this->session->set_userdata($data);
            $ketdet = 'Karyawan ID ' . $penggunaData->akunid;
            addLog('Login', 'as Admin Transport', $ketdet);
            redirect(base_url() . 'transport', 'refresh');
        } else if ($auth == $x) {
            $penggunaData = $this->Md_akun->getKaryawanById(decrypt($this->session->userdata('auth_login')), 1);
            $getData = $this->Md_pengguna->GetHakByDivisi($penggunaData->akunid, 'Spadmin', 'Transport');
            $data = array(
                'is_spadmin' => TRUE,
                'is_kepalatransport' => TRUE,
                'akun_id' => encrypt($penggunaData->akunid),
                'nama' => $penggunaData->nama,
                'pengguna_id' => encrypt($getData->penggunaid),
                'foto' => $penggunaData->foto,
            );
            // var_dump($data,$getData->penggunaid);die;
            $this->session->set_userdata($data);
            $ketdet = 'Karyawan ID ' . $penggunaData->akunid;
            addLog('Login', 'as Spadmin', $ketdet);
            redirect(base_url() . 'spadmin', 'refresh');
        } else if ($auth == $w) {
            $penggunaData = $this->Md_akun->getKaryawanById(decrypt($this->session->userdata('auth_login')), 1);
            $getData = $this->Md_pengguna->GetHakByDivisi($penggunaData->akunid, 'Warehouse', 'Transport');
            $cekkepalawarehouse = $this->Md_warehouseaccount->getWarehouseAccountByKaryawan($penggunaData->akunid);
            $is_kepalawarehouse = false;
            if (!empty($cekkepalawarehouse)) {
                $is_kepalawarehouse = true;
            }

            $warehouses = array_map(function ($row) {
                return encrypt($row->warehouseid);
            }, $this->Md_warehouse->getWarehouseByPenggunaid($getData->penggunaid));

            $this->session->set_userdata([
                'is_warehouse' => TRUE,
                'is_kepalawarehouse' => $is_kepalawarehouse,
                'akun_id' => encrypt($penggunaData->akunid),
                'nama' => $penggunaData->nama,
                'pengguna_id' => encrypt($getData->penggunaid),
                'foto' => $penggunaData->foto,
                'list_warehouse' => $warehouses,
            ]);

            $ketdet = 'Karyawan ID ' . $penggunaData->akunid;
            addLog('Login', 'as Admin Warehouse', $ketdet);
            redirect(base_url() . 'warehouse', 'refresh');
        } else if ($auth == $f) {
            $penggunaData = $this->Md_akun->getKaryawanById(decrypt($this->session->userdata('auth_login')), 1);
            $getData = $this->Md_pengguna->GetHakByDivisi($penggunaData->akunid, 'Mini Warehouse', 'Transport');
            $warehouse = $this->Md_warehouse->getMiniWarehouseByKaryawanid($penggunaData->akunid);

            $is_miniwarehouse = false;
            if (!empty($warehouse)) {
                $is_miniwarehouse = true;
            }

            $warehouses = array_map(function ($row) {
                return encrypt($row->warehouseid);
            }, $warehouse);

            $this->session->set_userdata([
                'is_fuelman' => $is_miniwarehouse,
                'akun_id' => encrypt($penggunaData->akunid),
                'nama' => $penggunaData->nama,
                'pengguna_id' => encrypt($getData->penggunaid),
                'foto' => $penggunaData->foto,
                'list_warehouse' => $warehouses,
            ]);

            $ketdet = 'Karyawan ID ' . $penggunaData->akunid;
            addLog('Login', 'as Fuelman', $ketdet);
            redirect(base_url() . 'fuelman', 'refresh');
        }
    }

    public function forgot_password($argv = '')
    {
        if ($argv == '') {
            $pageData['page_name'] = 'lawang';
            $pageData['recaptcha'] = $this->config->item('recaptcha_site_key');
            $this->load->view('lawang/forgot_password', $pageData);
        } else if ($argv == 'reset') {
            $this->form_validation->set_rules('id', 'field id', 'required');
            $this->form_validation->set_rules('g-recaptcha_response', 'field recaptcha', 'required');

            //siteKey and SecretKey Google
            $secretKey = $this->config->item('recaptcha_secret_key');
            $siteKey = $this->config->item('recaptcha_site_key');

            define($secretKey, $siteKey);

            // Take Token Google Captcha
            $token = $this->input->post('g-recaptcha_response');

            // call curl to POST request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $secretKey, 'response' => $token)));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $arrResponse = json_decode($response, true);

            // verify the response
            if ($arrResponse["success"] != '1' && $arrResponse["score"] <= 0.5) {
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('error', 'Ulangi Captcha');
                redirect(base_url() . 'lawang/forgot_password', 'refresh');
            } else {
                if ($this->form_validation->run() == TRUE) {
                    $id = $this->input->POST('id');
                    $pegawai = $this->Md_akun->getKaryawanByNikOrEmail($id);

                    if (isset($pegawai)) {
                        if ($pegawai->email) {
                            $reset_password = $this->Md_mailbox->getMailboxByToAndSubject($pegawai->email, 'Reset Password');
                            if (!isset($reset_password)) {
                                $send = TRUE;
                            } else {
                                $start = new DateTime($reset_password->tglpost);
                                $end = new DateTime(date('Y-m-d H:i:s'));
                                $diff = date_diff($end, $start);
                                $minute = $diff->format('%i');
                                if ($minute < 5) {
                                    $send = FALSE;
                                } else {
                                    $send = TRUE;
                                }
                            }

                            if ($send == TRUE) {
                                //enkripsi id
                                $length = 8;
                                $randomPass = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

                                $msg = 'Dear ' . ucwords($pegawai->nama) . ',<br/><br/>
                                Berikut adalah informasi reset password yang di kirimkan melalui HR Information System PT Vadhana International :<br><br><i>
                                Password Baru : ' . $randomPass . '
                                <i><br/><br/>
                                <br><br><br>Terimakasih.<br><br>
                                <br/>Do not reply to this computer-generated email. <br/>';


                                $dataInsert = array(
                                    'to' => $pegawai->email,
                                    'from' => 'noreply@vadhana.co.id',
                                    'subjek' => 'Reset Password',
                                    'isi' => $msg,
                                    'tglpost' => date('Y-m-d H:i:s'),
                                    'statuskirim' => 'belum',
                                    'status' => 1
                                );


                                $dataUpdate = array(
                                    'password' => password_hash($randomPass, PASSWORD_DEFAULT)
                                );

                                if ($dataInsert) {
                                    $this->Md_mailbox->addMailbox($dataInsert);
                                }

                                $this->Md_akun->updateKaryawan($pegawai->akunid, $dataUpdate);

                                $this->session->set_flashdata('success', 'Reset Password akan dikirim ke Email yang dituju');
                                redirect(base_url() . 'lawang/forgot_password', 'refresh');
                            } else {
                                $this->session->set_flashdata('error', 'Reset Password anda sudah dikirim ke Email yang dituju, silahkan tunggu 5 menit lagi untuk mengirim ulang');
                                redirect(base_url() . 'lawang/forgot_password', 'refresh');
                            }
                        } else {
                            $this->session->set_flashdata('error', 'Email Karyawan belum didaftarkan di sistem,<br/> silahkan hubungi administrator');
                            redirect(base_url() . 'lawang/forgot_password', 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('error', 'NIK atau Email tidak benar');
                        redirect(base_url() . 'lawang/forgot_password', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('error', 'NIK atau Email tidak benar');
                    redirect(base_url() . 'lawang/forgot_password', 'refresh');
                }
            }
        } else {
            redirect(base_url() . 'lawang', 'refresh');
        }
    }

    public function switchAccount()
    {
        //memastikan ada request POST
        if (!isset($_POST['pengguna_id']))
            redirect(base_url() . 'lawang', 'refresh');

        // memastikan data required terisi
        // mencegah kemungkinan adanya expert user remove attr required HTML
        $this->form_validation->set_rules('pengguna_id', 'field pengguna_id', 'required');

        if ($this->form_validation->run() != FALSE) {
            $penggunaid = (is_int(decrypt($this->input->post('pengguna_id')))) ? decrypt($this->input->post('pengguna_id')) : NULL;
            $dataPengguna = $this->Md_pengguna->getPenggunaById($penggunaid);

            $array_items = array('is_spadmin', 'is_transport', 'is_mekanik', 'is_warehouse');
            $this->session->unset_userdata($array_items);
            // jika data transport ada
            if ($dataPengguna) {
                # Create Cookie
                if (checkCookie() == false) {
                    createCookie($dataPengguna->akunid);
                }
                if ($dataPengguna->hakaksesname == 'Admin Transport') {

                    $cekkepalatransport = $this->Md_kepala_transport->getKepalaTransportByKaryawanId($dataPengguna->akunid);
                    $is_kepalatransport = false;
                    if (!empty($cekkepalatransport)) {
                        $is_kepalatransport = true;
                    }
                    // set login sebagai transport biasa
                    $data = array(
                        'is_transport' => TRUE,
                        'is_kepalatransport' => $is_kepalatransport,
                        'akun_id' => encrypt($dataPengguna->akunid),
                        'nama' => $dataPengguna->nama,
                        'pengguna_id' => encrypt($dataPengguna->penggunaid),
                        'foto' => $dataPengguna->foto,
                    );

                    //$this->session->sess_destroy();
                    $this->session->unset_userdata('is_mekanik');
                    $this->session->unset_userdata('is_spadmin');

                    $this->session->set_userdata($data);
                    addLog('Login', 'as Admin Transport', 'Karyawan ID ' . $dataPengguna->akunid);
                    redirect(base_url() . 'transport', 'refresh');
                } else if ($dataPengguna->hakaksesname == 'Admin Mekanik') {

                    $cekbengkelkepala = $this->Md_bengkel_kepala->getBengkelKepalaByKaryawanId($dataPengguna->akunid);
                    $is_bengkelkepala = false;
                    if (!empty($cekbengkelkepala)) {
                        $is_bengkelkepala = true;
                    }

                    $data = array(
                        'is_mekanik' => TRUE,
                        'is_bengkelkepala' => $is_bengkelkepala,
                        'akun_id' => encrypt($dataPengguna->akunid),
                        'nama' => $dataPengguna->nama,
                        'pengguna_id' => encrypt($dataPengguna->penggunaid),
                        'foto' => $dataPengguna->foto,
                    );

                    $this->session->unset_userdata('is_transport');
                    $this->session->unset_userdata('is_spadmin');

                    $this->session->set_userdata($data);
                    $ketdet = 'Karyawan ID ' . $dataPengguna->akunid;
                    addLog('Login', 'as Admin Mekanik', $ketdet);
                    redirect(base_url() . 'mekanik', 'refresh');
                } else if ($dataPengguna->hakaksesname == 'Warehouse') {
                    $penggunaData = $this->Md_akun->getKaryawanById(decrypt($this->session->userdata('auth_login')), 1);
                    $getData = $this->Md_pengguna->GetHakByDivisi($penggunaData->akunid, 'Warehouse', 'Transport');
                    $cekkepalawarehouse = $this->Md_warehouseaccount->getWarehouseAccountByKaryawan($penggunaData->akunid);

                    $is_kepalawarehouse = false;
                    if (!empty($cekkepalawarehouse)) {
                        $is_kepalawarehouse = true;
                    }
                    $data = array(
                        'is_warehouse' => TRUE,
                        'is_kepalawarehouse' => $is_kepalawarehouse,
                        'akun_id' => encrypt($dataPengguna->akunid),
                        'nama' => $dataPengguna->nama,
                        'pengguna_id' => encrypt($dataPengguna->penggunaid),
                        'foto' => $dataPengguna->foto,
                    );

                    $this->session->unset_userdata('is_warehouse');
                    $this->session->unset_userdata('is_spadmin');

                    $this->session->set_userdata($data);
                    $ketdet = 'Karyawan ID ' . $dataPengguna->akunid;
                    addLog('Login', 'as Admin Warehouse', $ketdet);
                    redirect(base_url() . 'warehouse', 'refresh');
                } else if ($dataPengguna->hakaksesname == 'Warehouse') {
                    $penggunaData = $this->Md_akun->getKaryawanById(decrypt($this->session->userdata('auth_login')), 1);
                    $getData = $this->Md_pengguna->GetHakByDivisi($penggunaData->akunid, 'Warehouse', 'Transport');
                    $warehouse = $this->Md_warehouse->getMiniWarehouseByKaryawanid($penggunaData->akunid);
                    $is_miniwarehouse = false;
                    if (!empty($warehouse)) {
                        $is_miniwarehouse = true;
                    }
                    $data = array(
                        'is_fuelman' => $is_miniwarehouse,
                        'akun_id' => encrypt($dataPengguna->akunid),
                        'nama' => $dataPengguna->nama,
                        'pengguna_id' => encrypt($dataPengguna->penggunaid),
                        'foto' => $dataPengguna->foto,
                    );

                    $this->session->unset_userdata('is_fuelman');
                    $this->session->unset_userdata('is_spadmin');

                    $this->session->set_userdata($data);
                    $ketdet = 'Karyawan ID ' . $dataPengguna->akunid;
                    addLog('Login', 'as Fuelman', $ketdet);
                    redirect(base_url() . 'fuelman', 'refresh');
                } else if ($dataPengguna->hakaksesname == 'Spadmin') {
                    $data = array(
                        'is_spadmin' => true,
                        'is_bengkelkepala' => true,
                        'akun_id' => encrypt($dataPengguna->akunid),
                        'nama' => $dataPengguna->nama,
                        'pengguna_id' => encrypt($dataPengguna->penggunaid),
                        'foto' => $dataPengguna->foto,
                    );

                    $this->session->unset_userdata('is_transport');
                    $this->session->unset_userdata('is_mekanik');
                    $this->session->unset_userdata('is_spadmin');


                    $this->session->set_userdata($data);
                    $ketdet = 'Karyawan ID ' . $dataPengguna->akunid;
                    addLog('Login', 'as Spadmin', $ketdet);
                    redirect(base_url() . 'spadmin', 'refresh');
                }
            } else {
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('message', 'Anda tidak memiliki izin akses');
                redirect(base_url() . 'lawang', 'refresh');
            }
        } else {
            $this->session->set_flashdata('alert', 'danger');
            $this->session->set_flashdata('message', 'Seluruh field wajib');
            redirect(base_url() . 'lawang', 'refresh');
        }
    }

    public function logout($as = '')
    {

        # update session id table akun
        if ($this->input->cookie('X-CEKLOGIN-SESSION')) {

            # delete cookie
            deleteCookie();
            $akunid = decrypt($this->session->userdata('akun_id'));
            $this->Md_akun->updateAkun($akunid, ['sessionid' => null]);
        }
        $this->session->sess_destroy();
        redirect(base_url() . 'lawang', 'refresh');
    }
}

/* End of file Lawang.php */
/* Location: ./application/controllers/Lawang.php */
