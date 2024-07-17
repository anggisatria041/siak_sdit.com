<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_log extends CI_Model
{
    var $table = 'log';

    /*     * * BEGIN COMPONENT DATA TABLE ** */
    var $column_search = array('lower(jenis_log)');
    var $column_filter = array('jenis_log');
    var $order = array('ls.log_id' => 'desc');

    function getDataForDataTable()
    {
        $this->db->select('ls.*,k.nama as pengguna');
        $this->db->from('log ls');
        $this->db->join('hrd.karyawan k', 'k.karyawanid = ls.karyawanid and k.status=1', 'left');
        $this->db->where('ls.status', 1);
    }

    private function getDatatablesQuery()
    {
        $this->Md_log->getDataForDataTable();

        $i = 0;
        foreach ($this->column_search as $item) { // loop column 
            if ($this->input->post('query[generalSearch]')) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, strtolower($this->input->post('query[generalSearch]')));
                } else {
                    $this->db->or_like($item, strtolower($this->input->post('query[generalSearch]')));
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        foreach ($this->column_filter as $filter) {
            if ($this->input->post('query[' . $filter . ']')) {
                $this->db->where($filter, $this->input->post('query[' . $filter . ']'));
            }
        }

        if ($this->input->post('sort[field]')) { // here order processing
            $this->db->order_by($this->input->post('sort[field]'), $this->input->post('sort[sort]'));
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    function getDatatables()
    {
        $this->getDatatablesQuery();
        if ($this->input->post('pagination[perpage]') != -1)
            $this->db->limit($this->input->post('pagination[perpage]'), ($this->input->post('pagination[perpage]') * (($this->input->post('pagination[page]') - 1))));
        $query = $this->db->get();
        return $query->result();
    }

    function countFiltered()
    {
        $this->getDatatablesQuery();
        $query = $this->db->count_all_results();
        return $query;
    }

    public function countAll()
    {
        $temp = $this->Md_log->getDataForDataTable();
        $temp = $this->db->get();
        return count($temp->result());
    }

    /*     * *  END COMPONENT DATA TABLE  ** */

    function addLog($data)
    {
        $this->db->insert('log', $data);
    }

    function addLogSCM($data)
    {
        $this->db->insert('scm.logsistem', $data);
    }

    function getJenisLog()
    {
        $this->db->select('distinct(jenis_log)');
        $this->db->from($this->table);
        $this->db->where('status', 1);
        return $this->db->get()->result();
    }

    function getLogAll()
    {
        $this->db->select('l.*, k.nama')
            ->from($this->table . ' as l')
            ->join('hrd.karyawan as k', 'k.karyawanid=l.karyawanid', 'left')
            ->where('l.status', 1)
            ->order_by('log_id', 'desc')
            ->limit(30);
        return $this->db->get()->result();
    }

    function getLogById($id)
    {
        $this->db->select('*');
        $this->db->from('log');
        $this->db->where('log_id', $id);
        return $this->db->get()->row();
    }

    public function getLogSistemByKaryawanid($id)
    {
        $this->db->select('log.*, k.nama')
            ->from('log as log')
            ->join('hrd.karyawan as k', 'k.karyawanid=log.karyawanid')
            ->where('log.karyawanid', $id)
            ->where('log.status', 1)
            ->order_by('log_id', 'desc')
            ->limit(30);
        return $this->db->get()->result();
    }
    // public function getLogsistemBySync($sync)
    // {
    //     $this->db->select('*');
    //     $this->db->from('logsistem');
    //     $this->db->where('sync', $sync);
    //     return $this->db->get()->result();
    // }
}

/* End of file Md_log.php */
/* Location: ./application/models/Md_log.php */
