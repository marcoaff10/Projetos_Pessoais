<?php

namespace App\Classes\Controllers;

use App\Classes\Helpers\Store;
use App\Classes\Requests\RegistrarClientesRequest;

class AuthCliente extends Auth
{
    //====================================================================================================
    public function registrarCliente()
    {
        // verifica se já existe sessao
        if (Store::clienteLogado()) {
            header('Location: index.php?a=inicio');
            return;
        }

        // verifica se houve submissão de um formulário
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: index.php?a=inicio');
            return;
        }

        $request = new RegistrarClientesRequest();

        $dados = $request->validarRegistro($_POST);

        $this->salvarRegistro($dados);

        $purl = $dados[':purl'];

        $link = "http://localhost/storeweb/public/index.php?a=confirmarEmail&purl=$purl";


        
    }

    //====================================================================================================
    public function loginCliente()
    {
    }
}
