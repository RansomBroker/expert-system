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

    public function get_citation($author_id)
    {
        return $this->db
            ->select("jenis_media_publikasi.media, sitasi.data_sitasi")
            ->from('jenis_media_publikasi')
            ->join($this->table, 'jenis_media_publikasi.id_jenis_media_publikasi = sitasi.id_jenis_media_publikasi')
            ->where('id_author', $author_id)
            ->get()
            ->result_array();
    }

    public function citation_check($id)
    {
        if (!empty($this->db->where('id_author', $id)->get($this->table)->result())){
            return true;
        } else {
            return false;
        }
    }

    public function citation_exist($author_id)
    {
        return $this->db
            ->where('id_author', $author_id)
            ->get($this->table)
            ->num_rows();
    }



}