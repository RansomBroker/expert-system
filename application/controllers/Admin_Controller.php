<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_Model');
        $this->load->model('Word_Dictionary_Model');
        $this->load->model('Knowledge_Field_Model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    public function index()
    {
        // redirect to another page if doesnt have session OR ilegal access
        if (!$this->session->has_userdata('user')) {
            redirect('home');
        }

        $total_data_knowledge_field = $this->Knowledge_Field_Model->total_data();

        $total_data_word_dictionary = $this->Word_Dictionary_Model->total_data();

        $pagination_1 = new CI_Pagination();
        $config_1['base_url'] = base_url('index.php/dashboard?knowledge_page=1');
        $config_1['total_rows'] = $total_data_knowledge_field;
        $config_1['per_page'] = 5;
        $config_1['use_page_numbers'] = TRUE;
        $config_1['page_query_string'] = TRUE;
        $config_1['reuse_query_string'] = TRUE;
        $config_1['query_string_segment'] = 'knowledge_page';
        $offset_1 = isset($_GET["knowledge_page"]) != NULL ? $_GET["knowledge_page"]: '1';

        $pagination_2 = new CI_Pagination();
        $config_2['base_url'] = base_url('index.php/dashboard?word_page=1');
        $config_2['total_rows'] = $total_data_knowledge_field;
        $config_2['per_page'] = 5;
        $config_2['use_page_numbers'] = TRUE;
        $config_2['page_query_string'] = TRUE;
        $config_2['reuse_query_string'] = TRUE;
        $config_2['query_string_segment'] = 'word_page';
        $offset_2 = isset($_GET["word_page"]) != NULL ? $_GET["word_page"]: '1';

        $pagination_1->initialize($config_1);
        $pagination_2->initialize($config_2);


        $data["knowledge_field"] = $this->Knowledge_Field_Model->get_field();

        $data["knowledge_list"] = $this->Knowledge_Field_Model->data($config_1['per_page'], ($offset_1 * $config_1['per_page'])- $config_1['per_page'] );

        $data["word_list"] = $this->Word_Dictionary_Model->data($config_2['per_page'], ($offset_2 * $config_2['per_page'])- $config_2['per_page'] );

        $data["pagination_link_1"] = $pagination_1->create_links();

        $data["pagination_link_2"] = $pagination_2->create_links();

        $this->load->view('admin_dashboard', $data);
    }

    public function insert_knowledge_field()
    {
        $rules = $this->Knowledge_Field_Model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == false) {
            return $this->load->view('admin_dashboard');
        }

        $knowledge_field = $this->input->post("bidang_ilmu");
        $data = array(
            'kelompok'=> $knowledge_field
        );

        if ($this->Knowledge_Field_Model->insert($data) > 0 ) {
            $this->session->set_flashdata('message_insert_knowledge_success', 'Data Baru Berhasil Ditambahkan');
        } else {
            $this->session->set_flashdata('message_insert_knowledge_error', 'Data gagal ditambahkan, pastikan koneksi ke database terhubung');
        }

        redirect('dashboard');
    }

    public function insert_word_dict()
    {
        $rules = $this->Word_Dictionary_Model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == false) {
            $data["knowledge_field"] = $this->Knowledge_Field_Model->get_field();
            return $this->load->view('admin_dashboard', $data);
        }

        $data = array(
            "kata" => $this->input->post('kamus_kata'),
            "id_kelompok_bidang" => $this->input->post('select_bidang_ilmu')
        );

        if ($this->Word_Dictionary_Model->insert($data)) {
            $this->session->set_flashdata('message_insert_word_success', 'Data Baru Berhasil Ditambahkan');
        } else {
            $this->session->set_flashdata('message_insert_word_error', 'Data gagal ditambahkan, pastikan koneksi ke database terhubung');
        }
        redirect('dashboard');
    }

}