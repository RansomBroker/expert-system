<?php

class Knowledge_Field_Model extends CI_Model
{
    private $table = "kelompok_bidang";

    public function rules()
    {
        return array(
            array(
                'field' => 'bidang_ilmu',
                'label' => 'bidang_ilmu',
                'rules' => 'required',
                'errors' => array(
                    'required'=> "Bidang ilmu tidak boleh kosong"
                )
            ),
        );
    }

    public function insert($data)
    {
        // insert data
        $this->db->insert($this->table, $data);

        // check insert berhasil / tidak
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function data($number, $offset)
    {
        return $this->db->get($this->table, $number, $offset)->result();
    }

    public function total_data()
    {
        return $this->db->get($this->table)->num_rows();
    }

    public function get_field() 
    {
        return $this->db->get($this->table)->result();
    }
    
}