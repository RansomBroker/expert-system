<?php

class Author_Position_Model extends CI_Model
{
    private $table = "posisi_penulis";

    public function insert_data($data)
    {
        $this->db->insert($this->table,$data);

        if ($this->db->affected_rows() > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function get_data($author_id)
    {
        return $this->db
            ->select('score')
            ->where('id_author', $author_id)
            ->get($this->table)
            ->result_array();
    }

    public function check_author_position_exist($author_id)
    {
        return $this->db
            ->where('id_author', $author_id)
            ->get($this->table)
            ->num_rows();
    }
}