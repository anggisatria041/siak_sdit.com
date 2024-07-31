<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_tahun_ajaran extends CI_Model
{
    public $table = 'tahun_ajaran';

    /*** BEGIN COMPONENT DATA TABLE ***/
    public $column_search = array('lower(nama_tajaran)');
    public $column_filter =array('nama_tajaran');
    public $order         = array('tajaran_id' => 'asc');

    public function getDataForDataTable()
    {
        $this->db->select('ta.*');
        $this->db->from('tahun_ajaran ta');
        $this->db->where('ta.status', 1);
        
    }
    public function getTahunAjaran()
    {
        $this->db->select('ta.*');
        $this->db->from('tahun_ajaran ta');
        $this->db->where('ta.status', 1);
        $query = $this->db->get();
        return $query->result();
    }
    public function getTahunAjaranGroup()
    {
        $this->db->select('ta.*');
        $this->db->from('tahun_ajaran ta');
        $this->db->where('ta.status', 1);
        $this->db->group_by('ta.nama_tajaran');
        $query = $this->db->get();
        return $query->result();
    }

    private function getDatatablesQuery()
    {
        $this->Md_tahun_ajaran->getDataForDataTable();
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
    function addTahunAjaran(array $data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function getTahunAjaranById($id){
       $this->db->where('tajaran_id', $id);
       $hasil = $this->db->get($this->table)->row();
       return $hasil;
    }
    function getTahunAjaranAktif(){
        $this->db->where('status_tajaran', 'Aktif');
        $hasil = $this->db->get($this->table)->row_array();
        return $hasil;
     }
    function updateTahunAjaran($id, $data) {
        $this->db->where('tajaran_id', $id);
        $this->db->update($this->table, $data);
    }
    function getTahunAjaranNoId($id){
        $this->db->where('tajaran_id !=', $id);
        $this->db->where('status', 1);
        $hasil = $this->db->get($this->table)->result();
        return $hasil;
    }
    function getNamaBulan($bulan){
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        return $namaBulan[$bulan];
    }
   

}
