<nav class="navbar navbar-expand-lg navbar-light bg-blue-gradient d-flex shadow p-0">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="<?=base_url()?>">
            <img src="<?= base_url('assets/img/brand_sinta.png') ?>" width="24" height="24" alt="brand_sinta.png"><small> Sinta Indonesia</small>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav me-4 mb-lg-0">
                <li class="nav-item">
                    <a href="<?= base_url()?>" class="nav-link text-white fw-bold">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('authors')?>"  class="nav-link text-white fw-bold">
                        Authors
                    </a>
                </li>
                <?php if($this->session->has_userdata('user')):?>
                    <li class="nav-item">
                        <a href="<?= base_url('logout')?>" class="btn btn-danger">
                            <i class="fa fa-sign-out" aria-hidden="true"></i> Log Out
                        </a>
                    </li>
                <?php endif;?>
            </ul>
        </div>
        <form action="<?= base_url("authors")?>" METHOD="GET" class="d-flex ms-4 p-2">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
    </div>
</nav>