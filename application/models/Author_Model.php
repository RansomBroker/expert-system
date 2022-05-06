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

    public function get_author_alias($id)
    {
        return $this->db
            ->select('author_alias')
            ->where('id_author', $id)->get($this->table)
            ->result();
    }

    public function get_author_name($id)
    {
        return $this->db
            ->select('author_name')
            ->where('id_author', $id)->get($this->table)
            ->result();
    }

    public function check_author_position($alias)
    {
        $query = $this->db
            ->select('author_name, author_alias')
            ->like('author_alias', $alias, 'both')
            ->or_like('author_name', $alias, 'both')
            ->get($this->table)
            ->result();

        if (!empty($query[0]->author_alias)) {
            return true;
        } else {
            return  false;
        }
    }

    public function get_all_author_data()
    {
        return $this->db
            ->select('id_author, author_img_url, author_name, author_affiliation, author_field')
            ->get($this->table)
            ->result();
    }

}