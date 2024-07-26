<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_akun extends CI_Model
{
    public $table = 'akun';

    /*** BEGIN COMPONENT DATA TABLE ***/
    public $column_search = array('lower(username)');
    public $column_filter = array('username');
    public $order = array('akun_id' => 'asc');

    public function getDataForDataTable()
    {
        $this->db->select('a.*');
        $this->db->from('akun a');
        $this->db->where('a.status', 1);

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
    function addAkun($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function getAkunById($id)
    {
        $this->db->where('akun_id', $id);
        $hasil = $this->db->get($this->table)->row();
        return $hasil;
    }
    function updateAkun($id, $data)
    {
        $this->db->where('akun_id', $id);
        $this->db->update($this->table, $data);
    }
    public function getAkunByNikOrUsername($username, $role='')
    {
        $this->db->select('ak.*');
        $this->db->from('akun ak');
        $this->db->group_start();
        $this->db->where('ak.username', $username);
        $this->db->or_where("lower(replace(ak.nis,' ','')) = ", $username);
        $this->db->group_end();
        $this->db->where('ak.status', 1);
        $query = $this->db->get();
        if ($query && $query->num_rows() > 0) {
            return $query->row();
        }
        
        return null;
        }


}
