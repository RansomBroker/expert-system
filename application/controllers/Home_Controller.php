<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Author_Model');
        $this->load->library('pagination');
    }

    public function index()
    {
        /*
         * TODO
         *  1. Show Data (complete)
         *  2. search (complete)
         *  3. pagination (complete)
         * */
        $query = isset($_GET['q']) ? $_GET['q'] : "";
        $total_data_author = $this->Author_Model->total_data();
        $pagination = new CI_Pagination();
        $config['base_url'] = base_url('index.php');
        $config['total_rows'] = isset($_GET['q']) ? $this->Author_Model->total_data_search($query): $total_data_author;
        $config['per_page'] = 5;
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $offset = isset($_GET["page"]) != NULL ? $_GET["page"]: '1';

        // pagintaion view
        $config['attributes'] = array('class' => 'page-link');
        $config['next_link'] = '&gt';
        $config['prev_link'] = '&lt';
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['prev_tag_open'] = "<li class='page_item'>";
        $config['prev_tag_close'] = "</li>";
        $config['next_tag_open'] = "<li class='page-item'>";
        $config['next_tag_close'] = "</li>";
        $config['first_tage_open'] = "<li class='page-item disabled'>";
        $config['first_tage_close'] = "<li class='page-item disabled'>";
        $config['cur_tag_open'] = "<li class='page-item active'><span class='page-link'>";
        $config['cur_tag_close'] = "</li>";
        $config['cur_tag_close'] = "</span></li>";
        $config['full_tag_close'] = "</ul>";

        $pagination->initialize($config);

        $data['per_page'] = $config['per_page'];
        $data['curr_page'] = isset($_GET["page"]) != NULL ? $_GET["page"]: '1';
        $data['total_data'] = $config['total_rows'];
        $data['author_profile'] = isset($_GET['q']) ? $this->Author_Model->search_author($query, $config['per_page'], ($offset * $config['per_page'])- $config['per_page']) : $this->Author_Model->get_author_data($config['per_page'], ($offset * $config['per_page'])- $config['per_page']);

        $data['pagination_link'] = $pagination->create_links();

        $this->load->view('index', $data);
    }
}