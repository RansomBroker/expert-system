<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Author_Model');
    }

    public function index()
    {
        var_dump($this->Author_Model->get_all_author_data());
        $this->load->view('index');
    }
}