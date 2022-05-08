<?php

class Scopus_Model extends CI_Model
{
    private $table = 'scopus';

    public function insert_scopus($data)
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

    public function get_author_media_scopus($author_id)
    {
        return $this->db
            ->select('q1, q2, q3, q4, undefined, article, conference')
            ->where('author_id', $author_id)
            ->get($this->table)
            ->result_array();
    }
}