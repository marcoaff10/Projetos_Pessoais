<?php

namespace App\Classes\Controllers;

use App\Classes\Helpers\Store;

class ViewsCliente
{
    public function novoCliente()
    {
        if (Store::clienteLogado()) {
            header('Location: index.php?a=inicio');
            return;
        }

        $dados = [
            'titulo' => 'Rigistrar-se' . APP_NAME,
        ];

        return Store::layout([
            'layouts/html_header',
            'layouts/header',
            'clientes/novoCliente',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    //====================================================================================================
    public function loginCliente()
    {

        if (Store::clienteLogado()) {
            header('Location: index.php?a=inicio');
            return;
        }

        $dados = [
            'titulo' => 'Login' . APP_NAME,
        ];

        return Store::layout([
            'layouts/html_header',
            'layouts/header',
            'clientes/loginCliente',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    //====================================================================================================
    public function minhaConta()
    {
        if (!Store::clienteLogado()) {
            header('Location: index.php?a=inicio');
            return;
        }

        $dados = [
            'titulo' => 'Minha Conta' . APP_NAME,
        ];

        return Store::layout([
            'layouts/html_header',
            'layouts/header',
            'clientes/minhaConta',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }
}
