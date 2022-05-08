<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Citation_Model extends CI_Model
{
    private $table = 'sitasi';

    public function insert_data($data)
    {
        $this->db->insert_batch($this->table, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

    public function citation_check($id)
    {
        if (!empty($this->db->where('id_author', $id)->get($this->table)->result())){
            return true;
        } else {
            return false;
        }
    }
}