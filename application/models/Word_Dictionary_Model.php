<?php

class Word_Dictionary_Model extends CI_Model
{
    private $table = 'kamus_kata';

    public function rules()
    {
        return array(
            array(
                'field' => 'kamus_kata',
                'label' => 'kamus_kata',
                'rules' => 'required',
                'errors' => array(
                    'required'=> "Field kamus kata tidak boleh kosong"
                )
            ),
            array(
                'field' => 'select_bidang_ilmu',
                'label' => 'select_bidang_ilmu',
                'rules' => 'required',
                'errors' => array(
                    'required' => "Field Bidang Ilmu tidak boleh kosong"
                )
            )
        );
    }

    public function data($number, $offset)
    {
        $this->db->select("kamus_kata.kata, kelompok_bidang.kelompok");
        $this->db->from("kamus_kata");
        $this->db->join("kelompok_bidang", "kamus_kata.id_kelompok_bidang = kelompok_bidang.id_kelompok_bidang");

        return $this->db->limit($number, $offset)->get()->result();
    }

    public function total_data()
    {
        return $this->db->get($this->table)->num_rows();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}