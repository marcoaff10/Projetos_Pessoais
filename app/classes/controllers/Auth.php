<?php

namespace App\Classes\Controllers;

use App\Classes\Models\Database;

abstract class Auth
{

    //====================================================================================================
    public function salvarRegistro(array $dados)
    {

        $model = new Database();
        $model->insert("
            INSERT INTO clientes VALUES(
                0, 
                :email, 
                :senha, 
                :nome, 
                :endereco, 
                :telefone, 
                :cidade,  
                :purl, 
                :ativo, 
                NOW(), 
                NOW(), 
                NULL
                )",
                $dados
        );
    }

    //====================================================================================================
    public function login()
    {
    }
}
