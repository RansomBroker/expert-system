<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sinta_Model extends CI_Model
{
    private $table = 'sinta';

    public function insert_sinta($data)
    {
        $this->db->insert($this->table, $data);

        if ($this->db->affected_rows() > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function data_exist($id)
    {
        if (!empty($this->db->where('author_id', $id)->get($this->table)->result())){
            return true;
        } else {
            return false;
        }
    }

    public function get_author_media_sinta($author_id)
    {
        return $this->db
            ->select('s1, s2, s3, s4, s5, s6')
            ->where('author_id', $author_id)
            ->get($this->table)
            ->result_array();
    }

}