<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
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
        $this->load->model('Publication_Model');
        $this->load->model('Citation_Model');
        $this->load->library('pagination');
        $this->load->model('Media_Quality_Model');
    }

    public function index()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $search = isset($_GET['q']) ? $_GET['q'] : "";
        $author_id = isset($_GET['id']) ? $_GET['id'] : "";
        $check_author = $this->Author_Model->author_check($author_id);
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

    public function detail($author_id)
    {
        $pagination = new CI_Pagination();
        $config['base_url'] = base_url("authors/detail/$author_id/");
        $config['total_rows'] = $this->Publication_Model->author_total_publication_data($author_id);
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

        $data['media_quality_exist'] = $this->Media_Quality_Model->check_media_quality($author_id);
        $data['media_quality_data'] = $this->Media_Quality_Model->get_media_quality($author_id);
        $data['sinta_data'] = $this->Sinta_Model->get_author_media_sinta($author_id);
        $data['scopus_data'] = $this->Scopus_Model->get_author_media_scopus($author_id);
        $data['per_page'] = $config['per_page'];
        $data['curr_page'] =isset($_GET["page"]) != NULL ? $_GET["page"]: '1';
        $data['total_data'] = $this->Publication_Model->author_total_publication_data($author_id);
        $data['pagination_link'] = $pagination->create_links();
        $data['publication_data'] = $this->Publication_Model->get_author_publication($author_id, $config['per_page'], ($offset * $config['per_page'])- $config['per_page']);
        $data['author_data'] = $this->Author_Model->get_author_detail($author_id);
        $this->load->view('detail', $data);
    }

    public function calculate_media($author_id)
    {
        /*
         * TODO
         *  1. calculate sinta and scopus
         *  2. count average
         *  3. save into db
         * */
        $sinta_data =  $this->Sinta_Model->get_author_media_sinta($author_id);
        $scopus_data = $this->Scopus_Model->get_author_media_scopus($author_id);

        // calculate sinta
        $sinta_total_data = 0.0;
        $sinta_percentage = 30;
        foreach ($sinta_data as $i => $data) {
            foreach ($data as $j => $score) {
                $sum = $score * ( $sinta_percentage /100 );
                $sinta_total_data += $sum;
                $sinta_percentage -= 5;
            }
        }

        // calculate scopus
        $scopus_total_data = 0.0;
        $scopus_percentage = 40;
        foreach ($scopus_data as $data) {
            foreach ($data as $j => $score) {
                if ($j == 'undefined' || $j == 'article' || $j == 'conference') {
                } else {
                    $sum = $score * ($scopus_percentage / 100);
                    $scopus_total_data += $sum;
                    $scopus_percentage -= 10;
                }
            }
        }

        //calculate article and conference
        $confer_aritcle_total_data = 0.0;
        $confer_aritcle_percentage = 70;
        foreach ($scopus_data as $data) {
            foreach ($data as $j => $score) {
                if ($j == 'article' || $j == 'conference') {
                    $sum = $score * ($confer_aritcle_percentage / 100);
                    $confer_aritcle_total_data += $sum;
                    $confer_aritcle_percentage -= 40;
                }
            }
        }

        $sinta_total = round($sinta_total_data*(30 / 100), 4);
        $scopus_total = round($scopus_total_data * (50 / 100), 4);
        $confer_article_total = round($confer_aritcle_total_data * (20 / 100), 4);

        $avg = ($sinta_total + $scopus_total + $confer_article_total) / 3;

        $data = array(
            'id_author' => $author_id,
            'sinta' => $sinta_total,
            'scopus' => $scopus_total,
            'confer_article' => $confer_article_total,
            'total' => round($avg, 4)
        );

        if ($this->Media_Quality_Model->insert_data($data)) {
            $this->session->set_flashdata('calculate_success', "berhasil menghitung kualitas media");
        } else {
            $this->session->set_flashdata('calculate_failed', "gaga menghitung kualitas media");
        }

        redirect("authors/detail/$author_id");
    }

    public function insert_data()
    {
        /*
        *  TODO
        *  1. insert author
        *  2. insert media
        *  3. insert publication gs(all jurnal wos, and scopus get coverd into gs)
        *  4. citation
        *
        * */

        $author_id = isset($_GET['id']) ? $_GET['id'] : "";
        $author_exist = $this->Author_Model->author_check($author_id);
        $scopus_exist = $this->Scopus_Model->data_exist($author_id);
        $sinta_exist =  $this->Sinta_Model->data_exist($author_id);
        $publication_exist = $this->Publication_Model->publication_check($author_id);
        $citaion_exist = $this->Citation_Model->citation_check($author_id);

        if (!$author_exist) {
            // insert author
            $insert_author = $this->insert_author($author_id);
        }

        if (!$sinta_exist || !$scopus_exist) {
            // insert media
            $insert_media = $this->insert_media_pub($author_id);
        }

        if (!$publication_exist) {
            // get author name
            $author_name = $this->Author_Model->get_author_name($author_id)[0]->author_name;
            // insert publication
            $insert_publication = $this->insert_pub($author_id);;
        }

        if (!$citaion_exist) {
            // insert citation
            $insert_citation = $this->insert_citation($author_id);
        }

        if ($insert_author && $insert_media && $insert_publication && $insert_citation) {
            $author_name = $this->Author_Model->get_author_name($author_id)[0]->author_name;
            $this->session->set_flashdata('insert_data_success', "Data $author_name Berhasil Ditambahkan");
        } else {
            $this->session->set_flashdata('insert_data_failed', "Data gagal Ditambahkan. Coba Lagi");
        }

        redirect('authors');
    }

    public function insert_author($author_id)
    {
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

        // make alias name
        $name_length = count(explode(" ",strtolower($author_profile[0]['author_name'])));
        $author_last_name = array(explode(" ",strtolower($author_profile[0]['author_name']))[$name_length-1]);

        $data = array(
            'id_author' => $author_id,
            'author_img_url' => $author_profile[0]['author_img'][0],
            'author_name' => $author_profile[0]['author_name'],
            'author_alias' => $author_last_name[0],
            'author_affiliation' => $author_profile[0]['author_affiliate'],
            'author_field' => (isset($author_profile[0]['author_department']) ? $author_profile[0]['author_department'] : null)
        );

        if ($this->Author_Model->insert_new_author($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_media_pub($author_id)
    {
        $isSintaSuccess = false;
        $isScopusSuccess = false;
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
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function insert_pub($author_id)
    {
        // awal data untuk mengambil total halaman
        $gs_initial = $this->goute->request('GET', "https://sinta.kemdikbud.go.id/authors/detail?id=$author_id&view=documentsgs");

        $total_page = $gs_initial->filter('caption')->each(function ($node) {
            return explode(" ", $node->text())[3];
        });

        /*
         * Mengambil seluruh data dari gs
         * */
        for ($i = 1; $i <= $total_page[0]; $i++ ) {
            $gs_pub_page = $this->goute->request('GET', "https://sinta.kemdikbud.go.id/authors/detail?page=$i&id=$author_id&view=documentsgs");

            $publications_title[] = $gs_pub_page->filter('.paper-link ')->each(function ($node) {
                return $node->text();
            });

            $publications_author[] = $gs_pub_page->filter(".uk-description-list-line")->each(function (Crawler  $node) {
                return $node->filter('dd')->eq(0)->each(function ($node) {
                    return explode(',', strtolower($node->text()));
                })[0];
            });

            $published_year[] =  $gs_pub_page->filter(".uk-description-list-line")->each(function (Crawler  $node) {
                return $node->filter('dd')->eq(1)->each(function ($node) {
                    $txt_length = count(explode("|", $node->text()));
                    return explode("|", $node->text())[$txt_length-1];
                })[0];
            });
        }

        /*
         * Menghitung Posisi Author dan Total Penulis
         * */
        foreach ($publications_author as $i => $authors) {
            foreach ($authors as $j => $author) {
                $total  = 0;
                $author_position = 0;
                foreach ($author as $k => $author_name) {
                    $total += 1;;
                    foreach (explode(" ", $author_name) as $l => $data_name) {
                        if ($this->Author_Model->check_author_position($data_name)) {
                            $author_position += $k+1;
                        } else {
                            $author_position += 0;
                        }
                    }
                }
                $author_data[] = array(
                    'posisi_penulis' => $author_position,
                    'total_penulis' => $total,
                );
            }
        }

        /*var_dump($author_data);*/
        /*
         * meresturktur data agar dapat di masukan ke database
         * */
        $pos = 0;
        foreach ($publications_title as $i => $titles) {
            foreach ($titles as $j => $title) {
                $data[] = array(
                    'id_author' => $author_id,
                    'id_jenis_media_publikasi' => 2,
                    'judul' => $title,
                    'posisi_penulis' => $author_data[$pos]['posisi_penulis'],
                    'total_penulis' => $author_data[$pos]['total_penulis'],
                    'tahun_publikasi' => ($published_year[$i][$j] != '0000' ? $published_year[$i][$j] : null),
                );
                $pos++;
            }
        }

        if ($this->Publication_Model->insert_data($data)){
            return true;
        }else {
            return false;
        }
    }

    public function insert_citation($author_id)
    {
        $citation = $this->goute->request('GET', "https://sinta.kemdikbud.go.id/authors/detail?id=$author_id&view=overview");

        $scopus_citation = $citation->filter('.uk-width-large-1-2')->eq(0)->filter('div > div')->eq(11)->text();

        $gs_citation = $citation->filter('.uk-width-large-1-2')->eq(0)->filter('div > div')->eq(18)->text();

        $wos_citation = $citation->filter('.uk-width-large-1-2')->eq(0)->filter('div > div')->eq(25)->text();

        $data = array(
            array(
                'id_author' => $author_id,
                'id_jenis_media_publikasi' => 1,
                'sitasi' => $scopus_citation
            ),
            array(
                'id_author' => $author_id,
                'id_jenis_media_publikasi' => 2,
                'sitasi' => $gs_citation
            ),
            array(
                'id_author' => $author_id,
                'id_jenis_media_publikasi' => 4,
                'sitasi' => ($wos_citation != "-" ? $wos_citation : 0)
            )
        );

        if ($this->Citation_Model->insert_data($data)) {
            return true;
        } else {
            return false;
        }
    }

    // not use for now
    public function insert_wos($author_id)
    {
        $wos_initial = $this->goute->request('GET', "https://sinta.kemdikbud.go.id/authors/detail?id=$author_id&view=documentswos");

        $total_page = $wos_initial->filter('caption')->each(function ($node) {
            return explode(" ", $node->text())[3];
        });

        for ($i = 1; $i <= $total_page[0]; $i++ ) {
            $wos_pub_page = $this->goute->request('GET', "https://sinta.kemdikbud.go.id/authors/detail?page=$i&id=$author_id&view=documentswos");

            $publications_title[] = $wos_pub_page->filter('.paper-link ')->each(function ($node) {

                $client = new Client(HttpClient::create(array(
                    'headers' => array(
                        'user-agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0', // will be forced using 'Symfony BrowserKit' in executing
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'en-US,en;q=0.5',
                        'Referer' => 'https://scholar.google.com/',
                        'Upgrade-Insecure-Requests' => '1',
                        'Save-Data' => 'on',
                        'Pragma' => 'no-cache',
                        'Cache-Control' => 'no-cache',
                        'cookie' => "ANID=AHWqTUndcphfNJ_zx4DkN1LeDzjUTUkCn6oQhFFfE5wHjYuXKok6IaJly7YY_pxm; HSID=ArOdU8c1GAooj8Zpb; SSID=Ap_xVbiDKuFNNGzmN; APISID=rj_W7mXWV7PMABmd/Auxzq4tdDD_DuDh9c; SAPISID=EYRNZFhBPydeo3ZM/Acb4NPZkNGaJ9Hpxg; __Secure-1PAPISID=EYRNZFhBPydeo3ZM/Acb4NPZkNGaJ9Hpxg; __Secure-3PAPISID=EYRNZFhBPydeo3ZM/Acb4NPZkNGaJ9Hpxg; SID=JQjRngG84iDIIv0E2qDK38Nqf9nG21u3vXhIY4ajJ_t-QI1aZ8xQYNr9EXg2AXaVBkm6Ag.; __Secure-1PSID=JQjRngG84iDIIv0E2qDK38Nqf9nG21u3vXhIY4ajJ_t-QI1aYgimNL-lAh6oOAZBOrvOlg.; __Secure-3PSID=JQjRngG84iDIIv0E2qDK38Nqf9nG21u3vXhIY4ajJ_t-QI1aUn4BTc-wgfIAWx9SNtQa-g.; OGPC=19027466-1:19027679-1:19022958-1:19022552-1:19022622-1:19028987-1:; OGP=-19028987:; SEARCH_SAMESITE=CgQIrZUB; AEC=AakniGMKgf8dEn0e1sNDreyf8xDTGjgWKYqNAe1wY6xcjoben_2_A-SvmPY; 1P_JAR=2022-05-06-02; NID=511=Dri7BKxEDTO1yx4es7P9a0DBiEW6xs6JS16O2ukjn4s9PXAzbW7H8r596bsLagooWx3qn234ap_lpX3tCaxq6-0_4cWQDZmDe0N3VFI9qHLYuzT9nxYUkDyZCqX6sXdexKNYahiqNoLCzrdRGwgO--SBfV-gCkpVfpfjC-jmxCt018EqXWZYVqEAJiswTTzWL01yE1qQc3i75Qm4KSgFbaHcKj19qsjM2ceZfHn81Ldy3N-ml6Hhlm9GIJ7yQ5xTlNM2YaNIF6DcNwiF_BvnmIr9AHCZjrQiemDDhBJ4b1bYJa67smz1mliB_ZTM8JK9ReRooa757CND1g_rMqa-NwYc1yTDldKsihLV8tFIfGgmgnIfpX3rYJ5cfOJlBWlIRwkOyW7wBSRKXsvsaXPlpqzx-8K6xxZqUphvH4EiRMi2C3gZ2dHlWpY1dXu_KhaPYEEq7oGg4d6JE3yJgfEQxj2uiuUwvbMo; GSP=A=B40hgQ:CPTS=1651804089:HG=:LM=1651804089:S=jEqCQtLIWn_CsB7K; SIDCC=AJi4QfE_CxHoV-pgBJ74q0sSsKtQh2-R3w2pgORz3F6lscRjqcDAeVJzmdkZBYfWBLE1BMczk2Q; __Secure-3PSIDCC=AJi4QfG_cUJjfWxBVVlB78HruPCvkDwkTlJUL5tsmPKXzWQPPY4sj0HnxDj6QElYDBszCxo3sESw"
                    ),
                )));

                $client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0');

                $gs_search = $client->request('GET', 'https://scholar.google.com/scholar?q='.urlencode($node->text()).'&hl=id&as_sdt=0,5');

                $published_year = $gs_search->filter('.gs_a')->each(function ($result) {
                    $explode_author_data = explode(',', explode('-', $result->text())[1]);
                    $get_length = count(explode(',', explode('-', $result->text())[1]));
                    return str_replace(' ', '', $explode_author_data[$get_length-1]);
                });

                sleep(rand(1, 3));

                return array(
                    'title' => $node->text(),
                    'published_year' => $published_year
                );
            });

            $publications_author[] = $wos_pub_page->filter(".uk-description-list-line")->each(function (Crawler  $node) {
                return $node->filter('dd')->eq(1)->each(function ($node) {
                    $split_author_list =explode(';', strtolower($node->text()));
                    $author_list = array_filter($split_author_list, function ($page){
                        return strlen($page) > 0;
                    });
                    return $author_list;
                })[0];
            });
        }

        /*
        * Menghitung Posisi Author dan Total Penulis
        * */
        foreach ($publications_author as $i => $publication_author) {
            foreach ($publication_author as $j => $author_name) {
                $total  = 0;
                $author_position = 0;
                foreach ($author_name as $k => $author) {
                    $total += 1;
                    if ($this->Author_Model->check_author_position(explode(',', $author)[0])) {
                        $author_position += $k+1;
                    }else {
                        $author_position += 0;
                    }
                }
                $author_data[] = array(
                    'posisi_penulis' => $author_position,
                    'total_penulis' => $total,
                );
            }
        }

        /*
         * restruktur data
         * */
        $pos = 0;
        foreach ($publications_title as $i => $titles) {
            foreach ($titles as $j => $row_title) {
                $data[] = array(
                    'id_author' => $author_id,
                    'id_jenis_media_publikasi' => 4,
                    'judul' => $row_title['title'],
                    'posisi_penulis' => $author_data[$pos]['posisi_penulis'],
                    'total_penulis' => $author_data[$pos]['total_penulis'],
                    'tahun_publikasi' => (isset($row_title['published_year']) ? $row_title['published_year'][0] : null),
                );
                $pos++;
            }
        }

        if ($this->Publication_Model->insert_gs_data($data)){
            echo "Success added Wos Data ";
            return true;
        }else {
            echo "Failled added Wos Data ";
            return false;
        }

    }

    // not use for now
    public function insert_scopus($author_id){
        // awal data untuk mengambil total halaman
        $scopus_initial = $this->goute->request('GET', "https://sinta.kemdikbud.go.id/authors/detail?id=$author_id&view=documentsscopus");

        $total_page = $scopus_initial->filter('caption')->each(function ($node) {
            return explode(" ", $node->text())[3];
        });

        for ($i = 1; $i <= $total_page[0]; $i++ ) {
            $wos_pub_page = $this->goute->request('GET', "https://sinta.kemdikbud.go.id/authors/detail?page=$i&id=$author_id&view=documentsscopus");

            $publications_title[] = $wos_pub_page->filter('.paper-link ')->each(function ($node) {
                return $node->text();
            });
        }

        foreach ($publications_title as $i => $publication_title){
            foreach ($publication_title as $j => $title) {
                $client = new Client(HttpClient::create(array(
                    'headers' => array(
                        'user-agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0', // will be forced using 'Symfony BrowserKit' in executing
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'en-US,en;q=0.5',
                        'Referer' => 'https://scholar.google.com/',
                        'Upgrade-Insecure-Requests' => '1',
                        'Save-Data' => 'on',
                        'Pragma' => 'no-cache',
                        'Cache-Control' => 'no-cache',
                        'cookie' => "ANID=AHWqTUndcphfNJ_zx4DkN1LeDzjUTUkCn6oQhFFfE5wHjYuXKok6IaJly7YY_pxm; HSID=ArOdU8c1GAooj8Zpb; SSID=Ap_xVbiDKuFNNGzmN; APISID=rj_W7mXWV7PMABmd/Auxzq4tdDD_DuDh9c; SAPISID=EYRNZFhBPydeo3ZM/Acb4NPZkNGaJ9Hpxg; __Secure-1PAPISID=EYRNZFhBPydeo3ZM/Acb4NPZkNGaJ9Hpxg; __Secure-3PAPISID=EYRNZFhBPydeo3ZM/Acb4NPZkNGaJ9Hpxg; SID=JQjRngG84iDIIv0E2qDK38Nqf9nG21u3vXhIY4ajJ_t-QI1aZ8xQYNr9EXg2AXaVBkm6Ag.; __Secure-1PSID=JQjRngG84iDIIv0E2qDK38Nqf9nG21u3vXhIY4ajJ_t-QI1aYgimNL-lAh6oOAZBOrvOlg.; __Secure-3PSID=JQjRngG84iDIIv0E2qDK38Nqf9nG21u3vXhIY4ajJ_t-QI1aUn4BTc-wgfIAWx9SNtQa-g.; OGPC=19027466-1:19027679-1:19022958-1:19022552-1:19022622-1:19028987-1:; OGP=-19028987:; SEARCH_SAMESITE=CgQIrZUB; AEC=AakniGMKgf8dEn0e1sNDreyf8xDTGjgWKYqNAe1wY6xcjoben_2_A-SvmPY; 1P_JAR=2022-05-06-02; NID=511=Dri7BKxEDTO1yx4es7P9a0DBiEW6xs6JS16O2ukjn4s9PXAzbW7H8r596bsLagooWx3qn234ap_lpX3tCaxq6-0_4cWQDZmDe0N3VFI9qHLYuzT9nxYUkDyZCqX6sXdexKNYahiqNoLCzrdRGwgO--SBfV-gCkpVfpfjC-jmxCt018EqXWZYVqEAJiswTTzWL01yE1qQc3i75Qm4KSgFbaHcKj19qsjM2ceZfHn81Ldy3N-ml6Hhlm9GIJ7yQ5xTlNM2YaNIF6DcNwiF_BvnmIr9AHCZjrQiemDDhBJ4b1bYJa67smz1mliB_ZTM8JK9ReRooa757CND1g_rMqa-NwYc1yTDldKsihLV8tFIfGgmgnIfpX3rYJ5cfOJlBWlIRwkOyW7wBSRKXsvsaXPlpqzx-8K6xxZqUphvH4EiRMi2C3gZ2dHlWpY1dXu_KhaPYEEq7oGg4d6JE3yJgfEQxj2uiuUwvbMo; GSP=A=B40hgQ:CPTS=1651804089:HG=:LM=1651804089:S=jEqCQtLIWn_CsB7K; SIDCC=AJi4QfE_CxHoV-pgBJ74q0sSsKtQh2-R3w2pgORz3F6lscRjqcDAeVJzmdkZBYfWBLE1BMczk2Q; __Secure-3PSIDCC=AJi4QfG_cUJjfWxBVVlB78HruPCvkDwkTlJUL5tsmPKXzWQPPY4sj0HnxDj6QElYDBszCxo3sESw"
                    ),
                )));

                $client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0');

                $gs_search = $client->request('GET', 'https://scholar.google.com/scholar?q='.urlencode($title).'&hl=id&as_sdt=0,5');

                /*$published_year = $gs_search->filter('.gs_a')->each(function ($result) {
                    $explode_author_data = explode(',', explode('-', $result->text())[1]);
                    $get_length = count(explode(',', explode('-', $result->text())[1]));
                    return str_replace(' ', '', $explode_author_data[$get_length-1]);
                });*/

                var_dump($gs_search->html());

                sleep(rand(1, 5));
            }
        }
    }
}

