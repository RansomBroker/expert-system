<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initials-scale=1">
    <title>Login Admin</title>

    <!-- font -->
    <?php $this->load->view("/includes/font.php");?>
    <!--  css  -->
    <?php $this->load->view("/includes/css.php");?>
    <!--  js  -->
    <?php $this->load->view("/includes/js.php");?>
</head>
<body>
<div class="container-fluid vh-100 row justify-content-center align-items-center">
    <div class="card col-lg-4 col-sm-6 shadow">
        <div class="card-body">
            <h1 class="text-center fs-4">Login Ke System</h1>
            <?php if ($this->session->flashdata('message_login_error')):?>
            <div class="w-100 border border-danger border-2 rounded bg-danger-50 px-1 py-2 d-flex align-items-center justify-content-center border-2">
                <p class="text-white text-danger m-0 text-center "><?= $this->session->flashdata('message_login_error')?></p>
            </div>
            <?php endif;?>
            <form action="<?= base_url('login')?>" method="POST">
                <div class="mb-3 has-validation">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control <?= form_error('username') ? 'is-invalid': ''?> " required>
                    <div class="invalid-feedback" id="username">
                        <?= form_error('username')?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control <?= form_error('password') ? 'is-invalid': ''?>" required>
                    <div class="invalid-feedback" id="password">
                        <?= form_error('password')?>
                    </div>
                </div>
                <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html><?php
