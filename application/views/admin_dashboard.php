<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initials-scale=1">
    <title>Admin Dashboard</title>

    <!-- font -->
    <?php $this->load->view("/includes/font.php");?>
    <!--  css  -->
    <?php $this->load->view("/includes/css.php");?>
    <!--  js  -->
    <?php $this->load->view("/includes/js.php");?>
</head>
<body>

    <!--  navbar  -->
    <?php $this->load->view("/includes/navbar.php");?>

    <!-- input  -->
    <div class="container-fluid pt-3">
        <h1 class="text-center">Selamat Datang Admin!</h1>
        <div class="row justify-content-center align-items-stretch">
            <!-- Form Bidang Ilmu -->
            <div class="card col-lg-4 col-sm-6 shadow  mx-2">
                <div class="card-body py-2 px-3">
                    <h2  class="text-center fs-3">Buat Kelompok Bidang Ilmu Baru</h2>
                    <?php if ($this->session->flashdata('message_insert_knowledge_success')):?>
                        <div class="w-100 border border-success border-2 rounded bg-success-50 px-1 py-2 d-flex align-items-center justify-content-center border-2">
                            <p class="text-white text-success m-0 text-center "><?= $this->session->flashdata('message_insert_knowledge_success')?></p>
                        </div>
                    <?php endif;?>
                    <?php if ($this->session->flashdata('message_insert_knowledge_error')):?>
                        <div class="w-100 border border-danger border-2 rounded bg-danger-50 px-1 py-2 d-flex align-items-center justify-content-center border-2">
                            <p class="text-white text-success m-0 text-center "><?= $this->session->flashdata('message_insert_knowledge_error')?></p>
                        </div>
                    <?php endif;?>
                    <form action="<?= base_url('insert-knowledge-field')?>" method="POST">
                        <div class="mb-3 ">
                            <label id="bidang_ilmu">Bidang ilmu</label>
                            <input type="text" id="bidang_ilmu" name="bidang_ilmu" class="form-control <?= form_error('bidang_ilmu') ? 'is-invalid': ''?>" placeholder="Nama Bidang ilmu...">
                            <div class="invalid-feedback">
                                <?= form_error('bidang_ilmu')?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Tambah</button>
                    </form>
                </div>
            </div>
            <!-- Form Kamus Kata -->
            <div class="card col-lg-4 col-sm-6 shadow mx-2">
                <div class="card-body py-2 px-3">
                    <h2 class="text-center fs-3">Buat Kamus Data Baru</h2>
                    <?php if ($this->session->flashdata('message_insert_word_success')):?>
                        <div class="w-100 border border-success border-2 rounded bg-success-50 px-1 py-2 d-flex align-items-center justify-content-center border-2">
                            <p class="text-white text-success m-0 text-center "><?= $this->session->flashdata('message_insert_word_success')?></p>
                        </div>
                    <?php endif;?>
                    <?php if ($this->session->flashdata('message_insert_word_error')):?>
                        <div class="w-100 border border-danger border-2 rounded bg-danger-50 px-1 py-2 d-flex align-items-center justify-content-center border-2">
                            <p class="text-white text-success m-0 text-center "><?= $this->session->flashdata('message_insert_word_error')?></p>
                        </div>
                    <?php endif;?>
                    <form action="<?= base_url('insert-word-dict')?>" method="POST">
                        <div class="mb-3">
                            <label id="kamus_kata">Kata</label>
                            <input type="text" id="kamus_kata" name="kamus_kata" class="form-control <?= form_error('kamus_kata') ? 'is-invalid': ''?>" placeholder="Kata" required>
                            <div class="invalid-feedback">
                                <?= form_error('kamus_kata')?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label id="select_bidang_ilmu">Bidang Ilmu</label>
                            <select class="form-select form-select-sm mb-3  <?= form_error('select_bidang_ilmu') ? 'is-invalid': ''?>" id="select_bidang_ilmu" name="select_bidang_ilmu">
                                <option selected value="">-- Pilih Bidang Ilmu --</option>
                                <?php foreach ($knowledge_field as $data):?>
                                    <option value="<?= $data->id_kelompok_bidang?>"><?= $data->kelompok?></option>
                                <?php endforeach;?>
                            </select>
                            <div class="invalid-feedback">
                                <?= form_error('select_bidang_ilmu')?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Tambah</button>
                    </form>
                </div>
            </div>
            <!--   table bidang ilmu     -->
            <div class="card col-lg-4 col-sm-6 shadow mx-2 my-2">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover w-100">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelompok Bidang Ilmu</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $i = isset($_GET["knowledge_page"]) != NULL ? ($_GET["knowledge_page"] * 5)-4: '1';
                        ?>
                        <?php foreach ($knowledge_list as $data):?>
                            <tr>
                                <th><?= $i++?></th>
                                <th><?= $data->kelompok?></th>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <!-- pagination -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <?= $pagination_link_1?>
                        </ul>
                    </nav>

                </div>
            </div>
            <!--   table kamus kata    -->
            <div class="card col-lg-4 col-sm-6 shadow mx-2 my-2">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover w-100">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kata</th>
                            <th>Kelompok Bidang Ilmu</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = isset($_GET["word_page"]) != NULL ? ($_GET["word_page"] * 5)-4: '1';
                        ?>
                        <?php foreach ($word_list as $data):?>
                            <tr>
                                <th><?= $n++?></th>
                                <th><?= $data->kata ?></th>
                                <th><?= $data->kelompok?></th>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <!-- pagination -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <!-- pagination -->
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                    <?= $pagination_link_2?>
                                </ul>
                            </nav>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

</body>