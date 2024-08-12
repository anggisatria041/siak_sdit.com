<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_kelas extends CI_Model
{
    public $table = 'kelas';

    /*** BEGIN COMPONENT DATA TABLE ***/
    public $column_search = array('nama_kelas');
    public $column_order = array(null, 'nama_kelas');
    public $order = array('kelas_id' => 'asc');

    public function getDataForDataTable()
    {
        $this->db->select('k.*');
        $this->db->from('kelas k');
        $this->db->where('k.status', 1);

    }
    public function getkelas()
    {
        $this->db->select('k.*');
        $this->db->from('kelas k');
        $this->db->where('k.status', 1);
        $query = $this->db->get();
        return $query->result();
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
    function addkelas($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function getkelasById($id)
    {
        $this->db->where('kelas_id', $id);
        $hasil = $this->db->get($this->table)->row();
        return $hasil;
    }
    function updatekelas($id, $data)
    {
        $this->db->where('kelas_id', $id);
        $this->db->update($this->table, $data);
    }
    public function getKelasCount($nis='')
    {
        
        if($nis){
            $this->db->select('k.nama_kelas');
            $this->db->from('kelas k');
            $this->db->join('siswa sw', 'sw.kelas_id = k.kelas_id','left');
            $this->db->where('sw.nis', $nis);
            $this->db->limit(1); 
            $query = $this->db->get();
            return $query->row();
        }else{
            $this->db->select('COUNT(*) as total');
            $this->db->from('kelas');
            $this->db->where('status', 1);
            $query = $this->db->get();
            $result = $query->row(); 
            return $result->total; 
        }
        
    }


}
