<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authors_Controller extends CI_Controller
{
    public function index()
    {
        $this->load->view('authors');
    }
}