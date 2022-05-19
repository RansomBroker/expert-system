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

    public function get_author_publication($author_id, $number, $offset)
    {
        return $this->db
            ->where('id_author', $author_id)
            ->get($this->table, $number, $offset)
            ->result_array();
    }

    public function author_total_publication_data($author_id)
    {
        return $this->db
            ->select('judul, posisi_penulis, total_penulis, tahun_publikasi')
            ->where('id_author', $author_id)
            ->get($this->table)
            ->num_rows();
    }

    public function count_first_author($author_id)
    {
        return $this->db
            ->where('id_author', $author_id)
            ->where('posisi_penulis', 1)
            ->get($this->table)
            ->num_rows();
    }

    public function count_next_author($author_id)
    {
        return $this->db
            ->where('id_author', $author_id)
            ->where('posisi_penulis >', 1)
            ->get($this->table)
            ->num_rows();
    }

}