<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_Model');
    }

    public function index()
    {
        show_404();
    }

    public function login()
    {
        // redirect to home if illegal access
        if ($this->session->has_userdata('user')) {
            redirect('dashboard');
        }

        $this->load->library('form_validation');

        $rules = $this->User_Model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == false) {
            return$this->load->view('login');
        }

        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // jika berhasil login akan redirect
        if ($this->User_Model->login($username, $password)) {
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('message_login_error', 'Login Gagal, Pastikan username dan password benar');
        }

        $this->load->view('login');
    }

    public function logout()
    {
        $this->User_Model->logout();
        redirect('');
    }
}