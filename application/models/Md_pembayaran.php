<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_pembayaran extends CI_Model
{
    public $table = 'pembayaran';

    /*** BEGIN COMPONENT DATA TABLE ***/
    public $column_search = array('lower(nis)', 'lower(status_pembayaran)');
    public $column_filter = array('nis', 'status_pembayaran');
    public $order = array('pembayaran_id' => 'desc');

    public function getDataForDataTable()
    {
        $this->db->select('p.*, s.nama as nama_siswa, t.nama_tajaran');
        $this->db->from('pembayaran p');
        $this->db->join('siswa s', 's.nis = p.nis');
        $this->db->join('tahun_ajaran t', 't.tajaran_id = p.tajaran_id');
        $this->db->where('p.status', 1);
        if ($this->session->userdata('hak_akses') == 'orang_tua') {
            $this->db->where('p.nis', $this->session->userdata('nis'));
        }
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
    public function getAllPembayaran()
    {
        $this->db->select('*');
        $this->db->from('pembayaran');
        $this->db->order_by('pembayaran_id', 'desc');
       
        $query = $this->db->get();
        return $query->result();
    }

    public function countFiltered()
    {
        $this->getDatatablesQuery();
        $query = $this->db->count_all_results();
        return $query;
    }
    function addPembayaran($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function getPembayaranById($id)
    {
        $this->db->where('pembayaran_id', $id);
        $hasil = $this->db->get($this->table)->row();
        return $hasil;
    }
    function updatePembayaran($id, $data)
    {
        $this->db->where('pembayaran_id', $id);
        $this->db->update($this->table, $data);
    }


}
