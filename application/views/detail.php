<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>

    <!--  css  -->
    <?php $this->load->view("/includes/css.php");?>

    <!--  js  -->
    <?php $this->load->view("/includes/js.php");?>
</head>
<body>
    <?php $this->load->view("/includes/navbar.php");?>

    <div class="container-fluid row px-5 mt-5 gap-4">
        <?php foreach ($author_data as $data):?>
            <!-- author detail -->
            <div class="col-lg-2 col-md-12 card shadow border p-0">
                <img src="<?=$data['author_img_url']?>" class="card-img-top" alt="author img">
                <div class="card-body px-2 py-2">
                    <p class="text-primary text-uppercase text-decoration-none"><?= $data["author_name"]?></p href="<?= base_url("authors/detail/").$data['id_author'] ?>
                    <p class="text-muted m-0"><small><?= $data["author_affiliation"]?></small></p>
                    <p class="text-muted m-0"><?= $data["author_field"]?></p>
                </div>
            </div>
            <!-- author expert system -->
            <div class="col-lg-9 col-md-12 row justify-content-center gap-4">
                <!-- author publication media -->
                <div class="col-lg-8 col-md-8 card shadow border p-0">
                    <div class="card-header">
                        Data Publikasi Sinta dan Scopus
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-borderless table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-normal">Publikasi</th>
                                    <th class="fw-normal">S6</th>
                                    <th class="fw-normal">S5</th>
                                    <th class="fw-normal">S4</th>
                                    <th class="fw-normal">S3</th>
                                    <th class="fw-normal">S2</th>
                                    <th class="fw-normal">S1</th>
                                    <th class="fw-normal">Q4</th>
                                    <th class="fw-normal">Q3</th>
                                    <th class="fw-normal">Q2</th>
                                    <th class="fw-normal">Q1</th>
                                    <th class="fw-normal">Conference</th>
                                    <th class="fw-normal">Article</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Jumlah</td>
                                    <?php foreach ($sinta_data as $data):?>
                                        <td><?= $data['s6']?></td>
                                        <td><?= $data['s5']?></td>
                                        <td><?= $data['s4']?></td>
                                        <td><?= $data['s3']?></td>
                                        <td><?= $data['s2']?></td>
                                        <td><?= $data['s1']?></td>
                                    <?php endforeach;?>
                                    <?php foreach ($scopus_data as $data):?>
                                        <td><?= $data['q4']?></td>
                                        <td><?= $data['q3']?></td>
                                        <td><?= $data['q2']?></td>
                                        <td><?= $data['q1']?></td>
                                        <td><?= $data['conference']?></td>
                                        <td><?= $data['article']?></td>
                                    <?php endforeach;?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- citation -->
                <div class="col-lg-3 col-md-3 card shadow border">
                    <div class="card-body">
                        <p>Coming Soon...</p>
                    </div>
                </div>
                <!-- author sinta, author position, media quality -->
                <div class="col-lg-2 col-md-2 card shadow border">
                    <div class="card-body">
                        <p>Coming Soon...</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 card shadow border">
                    <div class="card-body">
                        <p>Coming Soon...</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 card shadow border">
                    <div class="card-body">
                        <p>Coming Soon...</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 card shadow border">
                    <div class="card-body">
                        <p>Coming Soon...</p>
                    </div>
                </div>
            </div>
            <!-- author list publication -->
            <div class="col-lg-12 col-md-12 card shadow border p-0 mb-5">
                <div class="card-header">
                    Publikasi
                </div>
                <div class="card-body table-responsive">
                    <nav class="w-100 d-flex justify-content-between align-items-center">
                        <p class="text-muted"><small><i>Page <?= $curr_page?> of <?= ceil($total_data/$per_page) ?> total data <?= $total_data?></i></small></p>
                        <?= $pagination_link?>
                    </nav>
                    <table  class="table table-borderless table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Publikasi</th>
                                <th>Tahun</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($publication_data as $data):?>
                                <tr>
                                    <td>
                                        <dl>
                                            <dt class="text-primary fw-normal"><?= $data['judul']?></dt>
                                            <dd class="text-muted"><small>Posisi penulis <?= $data['posisi_penulis']?> dari <?=$data['total_penulis']?></small></dd>
                                        </dl>
                                    </td>
                                    <td>
                                        <?= (isset($data['tahun_publikasi']) != null ? $data['tahun_publikasi']: "-")?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach;?>
    </div>

</body>
