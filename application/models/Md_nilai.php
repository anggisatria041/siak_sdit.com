<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_nilai extends CI_Model
{
    public $table = 'nilai';

    /*** BEGIN COMPONENT DATA TABLE ***/
    public $column_search = array('lower(lingkup_materi)');
    public $column_filter =array('lingkup_materi');
    public $order         = array('nilai_id' => 'asc');

    public function getDataForDataTable()
    {
        $id =$this->session->userdata('nis');
        $akses =$this->session->userdata('hak_akses');
        
        $this->db->select('n.*,mp.nama_mapel,s.nis,s.nama');
        $this->db->from('nilai n');
        $this->db->join('mapel mp', 'mp.mapel_id = n.mapel_id','left');
        $this->db->join('siswa s', 's.siswa_id = n.siswa_id','left');
        if($akses == 'orang_tua'){
            $this->db->join('akun a', 'a.nis = s.nis','left');
            $this->db->where('a.nis', $id);
        }
        $this->db->where('n.status', 1);
        
    }
    public function getNilai()
    {
        $this->db->select('n.*');
        $this->db->from('nilai n');
        $this->db->where('n.status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    private function getDatatablesQuery()
    {
        $this->Md_nilai->getDataForDataTable();
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
    function addNilai($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function getNilaiById($id){
        $this->db->select('n.*,s.nama,s.nis');
        $this->db->from('nilai n');
        $this->db->join('siswa s', 's.siswa_id = n.siswa_id','left');
        $this->db->where('n.nilai_id', $id);
        $hasil = $this->db->get()->row();
        return $hasil;
    }
    function updateNilai($id, $data) {
        $this->db->where('nilai_id', $id);
        $this->db->update($this->table, $data);
    }
    function getByIdSiswa($id)
    {
        $this->db->select('n.*');
        $this->db->from('nilai s');
        $this->db->where('n.status', 1);
        $this->db->where('n.siswa', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
   

}
