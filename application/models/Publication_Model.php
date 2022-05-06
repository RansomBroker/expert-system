<?php

class Publication_Model extends CI_Model
{
    private $table = 'publikasi';

    public function insert_data($data)
    {
        $this->db->insert_batch($this->table, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        }else {
            return false;
        }
    }

    public function publication_check($id)
    {
        if (!empty($this->db->where('id_author', $id)->get($this->table)->result())){
            return true;
        } else {
            return false;
        }
    }
}