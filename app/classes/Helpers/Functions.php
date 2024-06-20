<?php

namespace App\Classes\Helpers;

use Exception;

class Functions
{
    //====================================================================================================
    public static function layout($estruturas, $dados = null)
    {
        // Verificando se $estruturas é um array
        if (!is_array($estruturas)) {
            throw new Exception('Coleção de estruturas inválida.');
        } 

        if (!empty($dados) && is_array($dados))
        {
            extract($dados);
        }

        foreach($estruturas as $estrutura)
        {
            include("../app/views/$estrutura.php");
        }
    }

    //====================================================================================================
}