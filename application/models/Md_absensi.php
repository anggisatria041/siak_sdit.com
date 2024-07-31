<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_absensi extends CI_Model
{
    public $table = 'absensi';

    /*** BEGIN COMPONENT DATA TABLE ***/
    public $column_search = array('lower(keterangan)');
    public $column_filter = array('keterangan');
    public $order = array('absensi_id' => 'desc');

    public function getDataForDataTable()
    {
        $this->db->select('a.*, b.nama');
        $this->db->from('absensi a');
        $this->db->join('siswa b', 'a.nis = b.nis', 'right');
        $this->db->group_by('b.siswa_id');
        $this->db->where('a.status', 1);
        $this->db->where('b.kelas_id', 1);
        $this->db->order_by('b.siswa_id', 'asc');
    }


    private function getDatatablesQuery()
    {
        $this->getDataForDataTable();
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($this->input->post('query[generalSearch]')) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, strtolower($this->input->post('query[generalSearch]')));
                } else {
                    $this->db->or_like($item, strtolower($this->input->post('query[generalSearch]')));
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        foreach ($this->column_filter as $filter) {
            if ($this->input->post('query[' . $filter . ']')) {
                $this->db->where($filter, $this->input->post('query[' . $filter . ']'));
            }
        }

        if ($this->input->post('sort[field]')) {
            $this->db->order_by($this->input->post('sort[field]'), $this->input->post('sort[sort]'));
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function getDatatables()
    {
        $this->getDatatablesQuery();
        if ($this->input->post('pagination[perpage]') != -1) {
            $this->db->limit($this->input->post('pagination[perpage]'), ($this->input->post('pagination[perpage]') * (($this->input->post('pagination[page]') - 1))));
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function countFiltered()
    {
        $this->getDatatablesQuery();
        $query = $this->db->count_all_results();
        return $query;
    }
    function addAbsensi($data)
    {
        $this->db->insert_batch($this->table, $data);
        return $this->db->affected_rows();
    }
    function getAbsensiById($id)
    {
        $this->db->where('absensi_id', $id);
        $hasil = $this->db->get($this->table)->row();
        return $hasil;
    }
    function updateAbsensi($id, $data)
    {
        $this->db->where('absensi_id', $id);
        $this->db->update($this->table, $data);
    }
    public function getAbsensiByKeterangan($keterangan, $role='')
    {
        $this->db->select('ab.*');
        $this->db->from('absensi ab');
        $this->db->group_start();
        $this->db->like('keterangan', $keterangan);
        $this->db->group_end();
        $this->db->where('ab.status', 1);
        $query = $this->db->get();
        if ($query && $query->num_rows() > 0) {
            return $query->row();
        }
        
        return null;
    }
    public function getAbsensiByTanggal($tanggal, $tajaran_id, $nis)
    {
        $this->db->where('tanggal', $tanggal);
        $this->db->where('tajaran_id', $tajaran_id);
        $this->db->where('nis', $nis);
        $query = $this->db->get($this->table);
        return $query->row();
    }
    public function getAbsensiByNis($nis, $tanggal, $tajaran_id,$kelas_id)
    {
        $this->db->select('ab.*');
        $this->db->from('siswa s');
        // $this->db->join('kelas kl', 's.kelas_id = kl.kelas_id');
        $this->db->join('absensi ab', 's.nis = ab.nis');
        $this->db->where('s.nis', $nis);
        $this->db->where('s.kelas_id', $kelas_id);
        $this->db->where('ab.tanggal', date('Y-m-' . sprintf("%02d", $tanggal)));
        $this->db->where('ab.tajaran_id', $tajaran_id);
        $query = $this->db->get();
        if ($query) {
            return $query->row();
        } else {
            return null;
        }
    }

}
