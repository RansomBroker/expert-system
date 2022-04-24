<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends CI_Model
{
    private $_table = 'user';
    private $SESSION_KEY = 'user';

    public function rules()
    {
        return array(
            array(
                'field' => 'username',
                'label' => 'username',
                'rules' => 'required'
            ),
            array(
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required'
            )
        );
    }

    public function getUser()
    {
        $data = $this->db->get($this->_table);
        return $data->result();
    }

    public function login($username, $password)
    {
        $username_db = $this->db
            ->where('username', $username)
            ->get($this->_table)
            ->row();

        // check username
        if (!$username_db) {
            return false;
        }
        // check password
        if (!password_verify($password, $username_db->password)) {
            return  false;
        }

        //jika berhasil buat sesion baru
        $this->session->set_userdata($this->SESSION_KEY, $username_db->username);

        return $this->session->has_userdata($this->SESSION_KEY);
    }

    public function logout()
    {
        $this->session->unset_userdata($this->SESSION_KEY);
        return !$this->session->has_userdata($this->SESSION_KEY);
    }
}