<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1">

    <title>Authors</title>

    <!--  css  -->
    <?php $this->load->view("/includes/css.php");?>

    <!--  js  -->
    <?php $this->load->view("/includes/js.php");?>
</head>
<body>
<?php $this->load->view("/includes/navbar.php");?>

<div class="container-fluid px-5 mt-5">
    <?if ($this->session->flashdata('insert_data_success')):?>
        <div class="w-100 border-success border border-2 rounded bg-success-50 p-2 d-flex justify-content-between">
            <p class="m-0 text-success"><?= $this->session->flashdata('insert_data_success')?></p>
            <a href="<?= base_url('authors')?>"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>
    <?endif;?>
    <?if ($this->session->flashdata('insert_data_failed')):?>
        <div class="w-100 border-danger border border-2 rounded bg-danger-50 p-2 d-flex justify-content-between">
            <p class="m-0 text-danger"><?= $this->session->flashdata('insert_data_failed')?></p>
            <a href="<?= base_url('authors')?>"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>
    <?endif;?>
</div>

<div class="container-fluid row gap-5 m-0 my-5">
    <!-- res -->
    <div class="col-lg-6 col-sm-6 card shadow border-0">
        <div class="card-body">
            <!-- search name -->
            <form action="<?= base_url('/authors')?>" method="GET" class="w-100">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Nama Author..." aria-label="Recipient's username" aria-describedby="btn-search" name="q">
                    <button class="btn btn-outline-secondary" type="submit" id="btn-search"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
            </form>
            <!-- search result name-->
            <?php if (isset($_GET['q']) && strlen($_GET['q']) > 0):?>
                <div class="w-100 border-success border border-2 rounded bg-success-50 p-2 d-flex justify-content-between">
                    <p class="m-0 text-success"><i>Search Result for:</i> "<?= $_GET['q'];?>"</p>
                    <a href="<?= base_url('authors')."?q=&page="?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                </div>
            <?endif;?>
            <!-- pagination top -->
            <nav aria-label="Page navigation paginate" class="mt-3">
                <ul class="pagination pagination-md justify-content-end">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?$search = isset($_GET['q']) ? $_GET['q'] : "";?>
                    <?php foreach($pagination["page_list"] as $page_list):?>
                            <li class="page-item  <?= $pagination['active_page']['page'] == $page_list ? "active": ""?>
                        <?php if(strlen($page_list) > 0):?>"><a class="page-link" href="<?=base_url('authors')."?q=".$search."&page=$page_list"?>"><?= $page_list?></a></li>
                        <?php endif;?>
                    <?php endforeach;?>

                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- page record -->
            <p class="text-muted"><?= $data_information[0]?></p>
            <!-- author -->
            <?php foreach($authors_data as $author_data):?>
                <div class="row g-4 p-3 my-2 shadow rounded">
                    <div class="col-md-2">
                        <img src="<?= $author_data["author_img"]?>" alt="author image" class="img-fluid">
                    </div>
                    <div class="col-md-10">
                        <a href="<?=base_url('authors')."?q=$search&page=".$pagination['active_page']['page']."&id=".$author_data['author_db_id']."&name=".$author_data["author_name"]?>" class="text-primary text-uppercase text-decoration-none"><?= $author_data["author_name"]?></a>
                        <p class="text-muted m-0"><small><?= $author_data["author_affiliate"]?></small></p>
                        <p class="text-muted m-0"><?= $author_data["author_id"]?></p>
                        <div class="my-2 text-muted">
                            <img src="https://sinta.kemdikbud.go.id/<?= $author_data["media_logo"][0]?>" alt="scopus image" height="14">
                            H-Index :
                            <span class="text-orange"><?= $author_data["author_indexed_scopus"]?></span>
                            <span>|</span>
                            <img src="https://sinta.kemdikbud.go.id/<?= $author_data["media_logo"][1]?>" alt="schoolarship image" height="14">
                            H-Index :
                            <span class="text-blue"><?= $author_data["author_indexed_google"]?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
            <!-- pagination bottom -->
            <nav aria-label="Page navigation paginate" class="mt-3">
                <ul class="pagination pagination-md justify-content-end">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?$search = isset($_GET['q']) ? $_GET['q'] : "";?>
                    <?php foreach($pagination["page_list"] as $page_list):?>
                        <li class="page-item  <?= $pagination['active_page']['page'] == $page_list ? "active": ""?>
                        <?php if(strlen($page_list) > 0):?>"><a class="page-link" href="<?=base_url('authors')."?q=".$search."&page=$page_list"?>"><?= $page_list?></a></li>
                        <?php endif;?>
                    <?php endforeach;?>

                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <?php if (!$is_author_exist && isset($_GET['id'])):?>
        <div class="col-lg-5 col-sm-6 card shadow border-0 align-self-start h-96 align-items-center justify-content-center show-data">
            <p><?= $author_name?>. Belum terdapat di database</p>
            <a href="<?= base_url("authors/insert-data?id=").$_GET['id']?>" class="btn btn-primary shadow">Simpan Data Ke Database</a>
        </div>
    <?php elseif ($is_author_exist):?>
        <div class="col-lg-5 col-sm-6 card shadow border-0 align-self-start h-96 align-items-center justify-content-center show-data">
            <p><?= $author_name?>. Sudah Terdaftar Ke database</p>
            <button class="btn btn-success shadow">Lihat Detail Author</button>
        </div>
    <?php endif;?>
</div>

</body>
</html>