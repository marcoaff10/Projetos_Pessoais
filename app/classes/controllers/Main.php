<?php

namespace App\Classes\Controllers;

use App\Classes\Helpers\Store;

class Main
{
    //====================================================================================================
    public function index()
    {

        $dados = [
            'titulo' => APP_NAME,
        ];

        return Store::layout([
            'layouts/html_header',
            'layouts/header',
            'home',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    //====================================================================================================
    public function loja()
    {
        echo 'LOJA!!!!!!!!!!';
    }
}
