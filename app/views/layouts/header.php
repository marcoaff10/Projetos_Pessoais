<?php

use App\Classes\Helpers\Store; 
?>
<div class="container-fluid navegacao">
    <div class="row justify-content-between align-items-center">
        <div class="col-6 p-3">
            <a href="?a=inicio" class="titulo">
                <h3 class="align-middle fs-2"><?= APP_NAME ?></h3>
            </a>
        </div>
        <div class="d-none d-lg-flex col-6 justify-content-end p-3">

            <a href="?a=inicio" class="mx-3 linkInicioLoja">Inicio</a>
            <a href="?a=loja" class="mx-3 linkInicioLoja">Loja</a>

            <?php if (Store::clienteLogado()) : ?>
                <div class="dropdown mx-2">
                    <a class="dropdown-toggle" role="button" id="dropdownLogado" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user-gear"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownLogado">
                        <li>
                            <a href="?a=minhaConta" class="dropdown-item">
                                <i class="fa-solid fa-gears me-1"></i>
                                Minha conta
                            </a>
                        </li>
                        <li>
                            <a href="?a=logoutCliente" class="dropdown-item">
                                <i class="fa-solid fa-arrow-right-from-bracket me-1"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else : ?>

                <a href="?a=loginCliente" class="mx-3 linkInicioLoja">
                    Login
                </a>
                <a href="?a=novoCliente" class="mx-3 linkInicioLoja">
                    Cadastrar-se
                </a>
            <?php endif ?>
            <a href="?a=carrinho" class="mx-3 linkInicioLoja"><i class="fa-solid fa-cart-plus"></i></a>
            <span class="badge bg-warning d-none">10</span>
        </div>

        <div class="col-6 d-flex d-lg-none justify-content-end menuMobile">
            <a class="dropMobile" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bars"></i>
            </a>

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <?php if (Store::clienteLogado()) : ?>
                    <li>
                        <a href="" class="dropdown-item">
                            <i class="fa-solid fa-gears me-1"></i>
                            Minha conta
                        </a>
                    </li>
                    <li>
                        <a href="?a=logoutCliente" class="dropdown-item">
                            <i class="fa-solid fa-arrow-right-from-bracket me-1"></i>
                            Logout
                        </a>
                    </li>
                <?php else : ?>
                    <li>
                        <a href="?a=loginCliente" class="dropdown-item">Login</a>
                    </li>
                    <li>
                    <a href="?a=novoCliente" class=" dropdown-item">Cadastrar-se</a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</div>