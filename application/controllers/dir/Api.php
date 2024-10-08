<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
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
        $this->load->library('M_datatable');

        //Load Model//
        $this->load->model('Md_siswa');
        $this->load->model('Md_guru');
        $this->load->model('Md_nilai');
        $this->load->model('Md_orang_tua');
        $this->load->model('Md_tahun_ajaran');

        //Load Helper//
        $this->load->helper('get_datatable');
        $this->load->helper('encryption_id');
        $this->load->helper('get_datatable');
        $this->load->helper('encryption_id');
        $this->load->helper('log');
        $this->load->helper('indonesia_day');
        $this->load->helper('hr');
        $this->load->helper('integer_to_roman');
        $this->load->helper('pushnotif');
        $this->load->helper('number_format');
        $this->load->helper('number_to_word');
        $this->load->helper('number_generator');

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
        show_error('Silahkan akses Controller melalui AJAX Request', '403', 'Forbidden Access 403');
    }
    public function manage_siswa()
    {
        $source = getDataForDataTable('Md_siswa', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['siswa_id'] = encrypt($list->siswa_id);
            $row['id_orang_tua'] = encrypt($list->id_orang_tua);
            $row['nama_ayah'] = $list->nama_ayah;
            $row['nama_ibu'] = $list->nama_ibu;
            $row['nis'] = $list->nis;
            $row['nama'] = $list->nama;
            $row['jenis_kelamin'] = $list->jenis_kelamin;
            $row['tempat_lahir'] = $list->tempat_lahir;
            $row['tanggal_lahir'] = $list->tanggal_lahir;
            $row['agama'] = $list->agama;
            $row['alamat'] = $list->alamat;
            $row['no_hp'] = $list->no_hp;
            $row['nama_kelas'] = $list->nama_kelas;
            $row['kelas_id'] = encrypt($list->kelas_id);

            $data[] = $row;
        }

        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }
    public function manage_guru()
    {
        $source = getDataForDataTable('Md_guru', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['guru_id'] = encrypt($list->guru_id);
            $row['niy'] = $list->niy;
            $row['nama_guru'] = $list->nama_guru;
            $row['jenis_kelamin'] = $list->jenis_kelamin;
            $row['agama'] = $list->agama;
            $row['tgl_lahir'] = $list->tgl_lahir;
            $row['alamat'] = $list->alamat;
            $row['pendidikan_terakhir'] = $list->pendidikan_terakhir;
            $row['no_hp'] = $list->no_hp;
            $data[] = $row;
        }

        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }

    public function manage_mapel()
    {
        $source = getDataForDataTable('Md_mapel', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['mapel_id'] = encrypt($list->mapel_id);
            $row['nama_mapel'] = $list->nama_mapel;
            $row['deskripsi'] = $list->deskripsi;
            $row['status'] = $list->status;
            $row['date_created'] = $list->date_created;
            $row['kode_mapel'] = $list->kode_mapel;
            $row['kelas_id'] = encrypt($list->kelas_id);
            $row['nama_kelas'] = $list->nama_kelas; // Tambahkan nama kelas
            $data[] = $row;
        }

        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }
    public function manage_kelas()
    {
        $source = getDataForDataTable('Md_kelas', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['kelas_id'] = encrypt($list->kelas_id);
            $row['nama_kelas'] = $list->nama_kelas;
            $row['keterangan'] = $list->keterangan;
            $row['status'] = $list->status;
            $row['date_created'] = $list->date_created;
            $data[] = $row;
        }

        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }

    public function manage_akun()
    {
        $source = getDataForDataTable('Md_akun', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['akun_id'] = encrypt($list->akun_id);
            $row['username'] = $list->username;
            $row['role'] = $list->role;
            $row['password'] = $list->password;
            $row['niy'] = $list->niy;
            $row['nis'] = $list->nis;
            $row['status'] = $list->status;
            $row['date_created'] = $list->date_created;
            $data[] = $row;
        }

        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }
    public function manage_pembayaran()
    {
        $source = getDataForDataTable('Md_pembayaran', null);
        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['pembayaran_id'] = encrypt($list->pembayaran_id);
            $row['nis'] = $list->nis;
            $row['tajaran_id'] = $list->tajaran_id;
            $row['nominal'] = $list->nominal;
            $row['nama_siswa'] = $list->nama_siswa;
            $row['nama_tajaran'] = $list->nama_tajaran;
            $row['tanggal_pembayaran'] = $list->tanggal_pembayaran;
            switch (strtolower($list->status_pembayaran)) {
                case 'belum lunas':
                    $row['status_pembayaran'] = '<span class="m-badge m-badge--danger m-badge--rounded text-white text-center">Belum Lunas</span>';
                    break;
                case 'lunas':
                    $row['status_pembayaran'] = '<span class="m-badge m-badge--success m-badge--rounded text-white text-center">Lunas</span>';
                    break;
                case 'menunggu verifikasi':
                    $row['status_pembayaran'] = '<span class="m-badge m-badge--warning m-badge--rounded text-white text-center">Menunggu Verifikasi</span>';
                    break;
                case 'ditolak':
                    $row['status_pembayaran'] = '<span class="m-badge m-badge--danger m-badge--rounded text-white text-center">Ditolak</span>';
                    break;
                default:
                    $row['status_pembayaran'] = '<span class="m-badge m-badge--info m-badge--rounded text-white text-center">Tidak Diketahui</span>';
                    break;
            }
            if ($list->bukti_pembayaran != '') {
                $row['bukti_pembayaran'] = '<a class="btn btn-outline-info text-center" data-bs-toggle="modal" data-bs-target="#modalImage-' . $list->pembayaran_id . '"><img onclick="img(' . $list->pembayaran_id . ')" src="' . base_url() . 'assets/upload/bukti_pembayaran/' . $list->bukti_pembayaran . '" alt="Bukti Pembayaran" width="50" ></a>';
            } else {
                $row['bukti_pembayaran'] = '-';
            }
            $row['catatan'] = $list->catatan;
            $row['status'] = $list->status;
            $row['date_created'] = $list->date_created;
            $data[] = $row;
        }

        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }
    public function manage_tahun_ajaran()
    {
        $source = getDataForDataTable('Md_tahun_ajaran', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['tajaran_id'] = encrypt($list->tajaran_id);
            $row['nama_tajaran'] = $list->nama_tajaran;
            $row['semester'] = $list->semester;
            $row['status_tajaran'] = $list->status_tajaran;
            $row['status'] = $list->status;
            $row['date_created'] = $list->date_created;


            $data[] = $row;
        }

        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }
    public function manage_orang_tua()
    {
        $source = getDataForDataTable('Md_orang_tua', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['id_orang_tua'] = encrypt($list->id_orang_tua);
            $row['nama_ayah'] = $list->nama_ayah;
            $row['tahun_lahir_ayah'] = $list->tahun_lahir_ayah;
            $row['pekerjaan_ayah'] = $list->pekerjaan_ayah;
            $row['pendidikan_ayah'] = $list->pendidikan_ayah;
            $row['penghasilan_ayah'] = $list->penghasilan_ayah;
            $row['alamat_ayah'] = $list->alamat_ayah;
            $row['nama_ibu'] = $list->nama_ibu;
            $row['tahun_lahir_ibu'] = $list->tahun_lahir_ibu;
            $row['pekerjaan_ibu'] = $list->pekerjaan_ibu;
            $row['pendidikan_ibu'] = $list->pendidikan_ibu;
            $row['penghasilan_ibu'] = $list->penghasilan_ibu;
            $row['alamat_ibu'] = $list->alamat_ibu;

            $data[] = $row;
        }

        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }
    public function manage_absensi()
    {
        $source = getDataForDataTable('Md_kelas', null);

        foreach ($source['data'] as $list) {
            $row = array();
            $row['no'] = ++$source['no'];
            $row['kelas_id'] = encrypt($list->kelas_id);
            $row['nama_kelas'] = $list->nama_kelas;
            $row['status'] = $list->status;
            $row['date_created'] = $list->date_created;
            $data[] = $row;
        }

        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }
    public function manage_detail($id_kelas, $bulan)
    {

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
        $output = null;
        if (!empty($source)) {
            $output = [
                "meta" => $source['meta'],
                "data" => isset($data) ? $data : [],
            ];
        }

        die(json_encode($output));
    }
    public function nilai_lingkup($lingkup)
{
    $source = getDataForDataTable('Md_nilai', null);
    $data = [];

    foreach ($source['data'] as $list) {
        // Cari apakah sudah ada record untuk siswa ini berdasarkan 'nis'
        $existingIndex = null;
        foreach ($data as $key => $existingRow) {
            if ($existingRow['nis'] == $list->nis) {
                $existingIndex = $key;
                break;
            }
        }

        if ($lingkup == 6) {
            // Jika siswa sudah ada, update nilai lm1, lm2, lm3, atau lm4 tanpa menambah record baru
            if ($existingIndex !== null) {
                if ($list->lingkup_materi == 1) {
                    $data[$existingIndex]['lm1'] = $list->tp1 + $list->tp2 + $list->tp3 + $list->tp4;
                } elseif ($list->lingkup_materi == 2) {
                    $data[$existingIndex]['lm2'] = $list->tp1 + $list->tp2 + $list->tp3 + $list->tp4;
                } elseif ($list->lingkup_materi == 3) {
                    $data[$existingIndex]['lm3'] = $list->tp1 + $list->tp2 + $list->tp3 + $list->tp4;
                } elseif ($list->lingkup_materi == 4) {
                    $data[$existingIndex]['lm4'] = $list->tp1 + $list->tp2 + $list->tp3 + $list->tp4;
                }

                // Hitung ulang sumatif setelah lm1, lm2, lm3, dan lm4 diperbarui
                $lmTotal = $data[$existingIndex]['lm1'] + $data[$existingIndex]['lm2'] + $data[$existingIndex]['lm3'] + $data[$existingIndex]['lm4'];
                $data[$existingIndex]['sumatif'] = $lmTotal / 4;

            } else {
                // Jika belum ada, buat record baru dan set nilai lm1, lm2, lm3, atau lm4
                $row = [];
                $row['no'] = ++$source['no'];
                $row['nilai_id'] = encrypt($list->nilai_id);
                $row['nis'] = $list->nis;
                $row['nama'] = $list->nama;
                $row['lm1'] = ($list->lingkup_materi == 1) ? $list->tp1 + $list->tp2 + $list->tp3 + $list->tp4 : 0;
                $row['lm2'] = ($list->lingkup_materi == 2) ? $list->tp1 + $list->tp2 + $list->tp3 + $list->tp4 : 0;
                $row['lm3'] = ($list->lingkup_materi == 3) ? $list->tp1 + $list->tp2 + $list->tp3 + $list->tp4 : 0;
                $row['lm4'] = ($list->lingkup_materi == 4) ? $list->tp1 + $list->tp2 + $list->tp3 + $list->tp4 : 0;

                // Hitung sumatif saat pertama kali record ditambahkan
                $lmTotal = $row['lm1'] + $row['lm2'] + $row['lm3'] + $row['lm4'];
                $row['sumatif'] = $lmTotal / 4;

                $data[] = $row;
            }
        } else {
            // Untuk lingkup spesifik, hanya tambahkan data jika lingkup_materi sesuai
            if ($list->lingkup_materi == $lingkup) {
                if ($existingIndex === null) {
                    $row = [];
                    $row['no'] = ++$source['no'];
                    $row['nilai_id'] = encrypt($list->nilai_id);
                    $row['nis'] = $list->nis;
                    $row['nama'] = $list->nama;
                    $row['nama_mapel'] = $list->nama_mapel;
                    $row['tp1'] = $list->tp1;
                    $row['tp2'] = $list->tp2;
                    $row['tp3'] = $list->tp3;
                    $row['tp4'] = $list->tp4;

                    $data[] = $row;
                }
            }
        }
    }

    $output = null;
    if (!empty($source)) {
        $output = [
            "meta" => $source['meta'],
            "data" => $data,
        ];
    }

    die(json_encode($output));
}

}