<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Author_Model extends CI_Model
{
    private $table = "author";

    public function author_exist($id)
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
}