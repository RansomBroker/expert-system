<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Author_Model extends CI_Model
{
    private $table = "author";

    public function author_check($id)
    {

        if (!empty($this->db->where('id_author', $id)->get($this->table)->result())) {
            return true;
        } else {
            return false;
        }

    }

    public function insert_new_author($data)
    {
        $this->db->insert($this->table, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return  false;
        }
    }

    public function get_author_name($id)
    {
        return $this->db
            ->select('author_name')
            ->where('id_author', $id)->get($this->table)
            ->result();
    }

    public function get_author_data($number, $offset)
    {
        return $this->db->get($this->table, $number, $offset)->result_array();
    }

    public function get_author_detail($author_id)
    {
        return $this->db
            ->where('id_author', $author_id)
            ->get($this->table)
            ->result_array();
    }

    public function check_author_position($alias)
    {
        $query = $this->db
            ->select('author_name, author_alias')
            ->like('author_name', $alias, 'both')
            ->like('author_alias', $alias, 'both')
            ->get($this->table)
            ->result();

        if (count($query) == 1 && $query[0]->author_alias == $alias) {
            return true;
        } else {
            return  false;
        }
    }

    public function search_author($query, $number, $offset)
    {
        return $this->db
            ->like('author_name', $query, 'both')
            ->get($this->table, $number, $offset)
            ->result_array();
    }

    public function total_data()
    {
        return $this->db->get($this->table)->num_rows();
    }

    public function total_data_search($query)
    {
        return $this->db
            ->like('author_name', $query, 'both')
            ->get($this->table)
            ->num_rows();
    }

}