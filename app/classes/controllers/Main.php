<?php

namespace App\Classes\Controllers;

use App\Classes\Helpers\Functions;

class Main
{
    //====================================================================================================
    public function index()
    {;

        $dados = [
            'titulo' => 'Este é um título',
            'clientes' => ['Marco', 'Vitoria', 'Ana']
        ];

        return Functions::layout([
            'layouts/html_header',
            'home',
            'layouts/html_footer',
        ], $dados);
    }

    //====================================================================================================
    public function loja()
    {
        echo 'LOJA!!!!!!!!!!';
    }
}
