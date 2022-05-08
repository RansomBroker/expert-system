<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initials-scale=1">
    <title>Home</title>

    <!--  css  -->
    <?php $this->load->view("/includes/css.php");?>

    <!--  js  -->
    <?php $this->load->view("/includes/js.php");?>
</head>
<body>
    <?php $this->load->view("/includes/navbar.php");?>

    <div class="container-fluid px-5 mt-3">
        <h1 class="fs-4">List Author di database</h1>
    </div>

    <div class="container-fluid d-flex justify-content-center mt-3">
        <form action="<?= base_url('/')?>" method="GET" class="w-50">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Nama Author..." aria-label="Recipient's username" aria-describedby="btn-search" name="q">
                <button class="btn btn-outline-secondary" type="submit" id="btn-search"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
        </form>
    </div>

    <!-- show author data -->
    <div class="container-fluid mt-3 row gap-5 m-0 my-5 justify-content-center">
        <nav class="w-100 d-flex justify-content-between align-items-center px-5">
            <p class="text-muted"><small><i>page <?= $curr_page?> of <?= ceil($total_data/$per_page) ?> | total data <?= $total_data?> </i></small></p>
            <?= $pagination_link?>
        </nav>
        <?php foreach ($author_profile as $data):?>
            <div class="col-lg-3 col-md-6 card shadow border-0 py-3">
                <div class="row">
                    <div class="col-md-4 d-flex align-items-center">
                        <img src="<?= $data["author_img_url"]?>" alt="author image" class="img-fluid">
                    </div>
                    <div class="col">
                        <a href="<?= base_url("authors/detail/").$data['id_author'] ?>" class="text-primary text-uppercase text-decoration-none"><?= $data["author_name"]?></a>
                        <p class="text-muted m-0"><small><?= $data["author_affiliation"]?></small></p>
                        <p class="text-muted m-0"><?= $data["author_field"]?></p>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>

</body>
</html>