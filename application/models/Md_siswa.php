<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_siswa extends CI_Model
{
    public $table = 'siswa';

    /*** BEGIN COMPONENT DATA TABLE ***/
    public $column_search = array('lower(nama)');
    public $column_filter =array('nama');
    public $order         = array('siswa_id' => 'asc');

    public function getDataForDataTable()
    {
        $this->db->select('s.*,ot.id_orang_tua,ot.nama_ayah,ot.nama_ibu');
        $this->db->from('siswa s');
        $this->db->join('orang_tua ot', 'ot.id_orang_tua = s.id_orang_tua');
        $this->db->where('s.status', 1);
        
    }
    public function getsiswa()
    {
        $this->db->select('s.*');
        $this->db->from('siswa s');
        $this->db->where('s.status', 1);
        $query = $this->db->get();
        return $query->result();
    }
    public function getSiswaByKelas($id)
    {
        $this->db->select('s.*');
        $this->db->from('siswa s');
        $this->db->where('s.status', 1);
        $this->db->where('s.kelas_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    private function getDatatablesQuery()
    {
        $this->Md_siswa->getDataForDataTable();
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
    function addSiswa($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function getSiswaById($id){
       $this->db->where('siswa_id', $id);
       $hasil = $this->db->get($this->table)->row();
       return $hasil;
    }
    function getSiswaByNis($id){
        $this->db->where('siswa_id', $id);
        $hasil = $this->db->get($this->table)->row_array();
        return $hasil;
     }
     function getSiswaByNis_k($id){
        $this->db->where('nis', $id);
        $hasil = $this->db->get($this->table)->row_array();
        return $hasil;
     }
    function updateSiswa($id, $data) {
        $this->db->where('siswa_id', $id);
        $this->db->update($this->table, $data);
    }
   

}
