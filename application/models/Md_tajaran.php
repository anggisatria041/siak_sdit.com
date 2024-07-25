<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_tajaran extends CI_Model
{
    public $table = 'tahun_ajaran';
    public $primary_key = 'tajaran_id';

    /*** BEGIN COMPONENT DATA TABLE ***/
    public $column_search = array('lower(nama_tajaran)');
    public $column_filter = array('nama_tajaran');
    public $order = array('tajaran_id' => 'asc');

    public function getDataForDataTable()
    {
        $this->db->select('a.*');
        $this->db->from('tahun_ajaran a');
        $this->db->where('a.status', 1);

    }
    function getTajaran()
    {
        $this->db->select('s.*');
        $this->db->from('tahun_ajaran s');
        $this->db->where('s.status', 1);
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
    function addTahunAjaran($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function getTahunAjaranById($id)
    {
        $this->db->where('tajaran_id', $id);
        $hasil = $this->db->get($this->table)->row();
        return $hasil;
    }
    function updateTahunAjaran($id, $data)
    {
        $this->db->where('tajaran_id', $id);
        $this->db->update($this->table, $data);
    }


}
