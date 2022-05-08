<?php

class Media_Quality_Model extends CI_Model
{
    private $table = 'kualitas_media';

    public function check_media_quality($author_id)
    {
        return $this->db
            ->where('id_author', $author_id)
            ->get($this->table)
            ->num_rows();
    }

    public function get_media_quality($author_id)
    {
        return $this->db
            ->select('sinta, scopus, confer_article, total')
            ->where('id_author', $author_id)
            ->get($this->table)
            ->result_array();
    }

    public function insert_data($data)
    {
        $this->db->insert($this->table,$data);

        if ($this->db->affected_rows() > 0 ) {
            return true;
        } else {
            return false;
        }
    }
}