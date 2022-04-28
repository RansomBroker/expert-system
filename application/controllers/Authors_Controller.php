<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;
class Authors_Controller extends CI_Controller
{
    private $goute;

    public function __construct()
    {
        parent::__construct();
        $this->goute = new Client();
        $this->load->model('Author_Model');
        $this->load->model('Sinta_Model');
        $this->load->model('Scopus_Model');
    }

    public function index()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $search = isset($_GET['q']) ? $_GET['q'] : "";
        $author_id = isset($_GET['id']) ? $_GET['id'] : "";
        $check_author = $this->Author_Model->author_exist($author_id);
        $author_name = isset($_GET['name']) ? $_GET['name'] : "";

        $response = $this->goute->request('GET', "https://sinta.kemdikbud.go.id/authors?q=".$search."&page=".$page."&sort=year2&view=&search=1");

        $data_information = $response->filter('caption')->each(function ($node){
            return $node->text();
        });

        $pagination = $response->filter('.top-paging')->each(function (Crawler $node, $i) {
            if ($i == 0) {
                $active_page = $node->filter('.uk-active > span')->each(function ($node) {
                    return array(
                        'is_active' => true,
                        'page' => $node->text()
                    );
                });

                $page = $node->filter('.top-paging > li > a')->each(function ($node) {
                    return $node->text();
                });


                array_push($page, $active_page[0]['page']);
                $page = array_filter($page, function ($page){
                    return strlen($page) > 0;
                });
                sort($page);

                return array(
                    'active_page' => $active_page[0],
                    'page_list' => $page
                );
            }
        });

        $default_author_data = $response->filter("tbody > tr ")->each(function (Crawler $node) {

            $author_img = $node->filter(".author-photo-small")->each(function ($node){
                return $node->attr('src');
            });

            $author_name_id = $node->filter("dl > dt > .text-blue")->each(function ($node){
                $data["author_name"] = $node->text();
                $link = explode("=", $node->attr('href'))[1];
                $data["author_id"] = explode("&", $link)[0];
                return $data;
            });

            $author_afiliate = $node->filter("dl > dd > .uk-text-muted")->each(function ($node){
                return $node->text();
            });

            $author_identification = $node->filter('dl > dd')->each(function ($node, $i){
                if ($i == 1) {
                    $data['author_id'] = $node->text();
                    return $data;
                }
            });

            $author_indexed = $node->filter('.indexed-by')->each(function (Crawler $node){
                $indexed_img['media_logo'] = $node->filter('.indexed-img')->each(function ($node){
                   $data = $node->attr('src');
                   return $data;
                });
                $indexed = $node->filter('.indexed-by-val')->each(function ($node){
                   $data['media'] = $node->text();
                   return $data;
                });
                return array($indexed_img, $indexed);
            });

           return array(
               'author_img' => $author_img[0],
               'author_name' => $author_name_id[0]['author_name'],
               'author_db_id'=> $author_name_id[0]['author_id'],
               'author_affiliate' => $author_afiliate[0],
               'author_id' => $author_identification[1]['author_id'],
               'media_logo' => $author_indexed[0][0]['media_logo'],
               'author_indexed_scopus' => $author_indexed[0][1][0]['media'],
               'author_indexed_google' => $author_indexed[0][1][1]['media']);
        });
        $data['authors_data'] = $default_author_data;
        $data['data_information'] = $data_information;
        $data['pagination'] = $pagination[0];
        $data['is_author_exist'] = $check_author;
        $data['author_name'] = $author_name;
        $this->load->view('authors', $data);
    }

    public function insert_author()
    {
        $author_id = isset($_GET['id']) ? $_GET['id'] : "";
        $author_exist = $this->Author_Model->author_exist($author_id);

        if(!$author_exist){
            $response = $this->goute->request('GET', "https://sinta.kemdikbud.go.id/authors/detail?id=$author_id&view=overview");

            $author_profile = $response->filter('.default-stat')->each(function (Crawler $node){
                $author_img = $node->filter('.author-photo-normal')->each(function ($node){
                    return $node->attr('src');
                });

                $author_name = $node->filter('.au-name')->each(function ($node) {
                    return $node->text();
                });

                $author_afiliate = $node->filter('.au-affil > a')->each(function ($node){
                    return $node->text();
                });

                $author_department = $node->filter('.au-department')->each(function ($node){
                    return $node->text();
                });

                return array(
                    'author_img' => $author_img,
                    'author_name' => $author_name[0],
                    'author_affiliate' => $author_afiliate[0],
                    'author_department' => $author_department[0]
                );
            });

            $data = array(
                'id_author' => $author_id,
                'author_img_url' => $author_profile[0]['author_img'][0],
                'author_name' => $author_profile[0]['author_name'],
                'author_affiliation' => $author_profile[0]['author_affiliate']
            );

            if ($this->Author_Model->insert_new_author($data)) {
                echo "input data berhasil";
            } else {
                echo "input data gagal";
            }
        }else {
            echo "data exist on db";
        }
    }

    public function insert_media_pub()
    {
        $isSintaSuccess = false;
        $isScopusSuccess = false;
        $author_id = isset($_GET['id']) ? $_GET['id'] : "";
        $scopus_exist = $this->Scopus_Model->data_exist($author_id);
        $sinta_exist =  $this->Sinta_Model->data_exist($author_id);

        if ($scopus_exist == false && $sinta_exist == false) {
            $response = $this->goute->request('GET', "https://sinta.kemdikbud.go.id/authors/detail?id=$author_id&view=overview");

            $sinta_accreditaion_data = $response->filter('div > .uk-width-large-1-3 ')->eq(5)->each(function (Crawler $node) {
                $sinta = $node->filter('.stat-num-pub')->each(function ($node){
                    return $node->text();
                });

                return $sinta;
            });

            $scopus_quartile_data = $response->filter('div > .uk-width-large-1-3 ')->eq(4)->each(function (Crawler $node){
                $quartile_scopus = $node->filter('.stat-num-pub')->each(function ($node){
                    return $node->text();
                });

                return $quartile_scopus;
            });

            $scopus_research_output_data = $response->filter('div > .uk-width-large-1-3 ')->eq(3)->each(function (Crawler $node){
                $research_ouput = $node->filter('.stat-num-pub')->each(function ($node){
                    return $node->text();
                });

                return $research_ouput;
            });

            $sinta_data = array(
                'author_id' => $author_id,
                's1' => $sinta_accreditaion_data[0][0],
                's2' => $sinta_accreditaion_data[0][1],
                's3' => $sinta_accreditaion_data[0][2],
                's4' => $sinta_accreditaion_data[0][3],
                's5' => $sinta_accreditaion_data[0][4],
                's6' => $sinta_accreditaion_data[0][5],
                'uncategorized' => $sinta_accreditaion_data[0][5],
            );

            $scopus_data = array(
                'author_id' => $author_id,
                'q1' => $scopus_quartile_data[0][0],
                'q2' => $scopus_quartile_data[0][1],
                'q3' => $scopus_quartile_data[0][2],
                'q4' => $scopus_quartile_data[0][3],
                'undefined' => $scopus_quartile_data[0][4],
                'article' => $scopus_research_output_data[0][0],
                'conference' => $scopus_research_output_data[0][1],
            );

            if ($this->Sinta_Model->insert_sinta($sinta_data)) {
                    $isSintaSuccess = true;
            }

            if ($this->Scopus_Model->insert_scopus($scopus_data)) {
                    $isScopusSuccess = true;
            }

            if ($isSintaSuccess && $isScopusSuccess) {
                echo "success added";
            } else {
                echo "Failed added";
            }

        } else {
            echo "data exist in db";
        }


    }

    public function insert_pub()
    {
        $author_id = isset($_GET['id']) ? $_GET['id'] : "";
    }
}