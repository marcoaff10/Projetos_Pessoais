<?php

namespace App\Classes\Helpers;

use Exception;

class Store
{
    //====================================================================================================
    public static function layout($estruturas, $dados = null)
    {
        // Verificando se $estruturas é um array
        if (!is_array($estruturas)) {
            throw new Exception('Coleção de estruturas inválida.');
        }

        if (!empty($dados) && is_array($dados)) {
            extract($dados);
        }

        foreach ($estruturas as $estrutura) {
            include("../app/views/$estrutura.php");
        }
    }

    //====================================================================================================
    // ===========================================================
    public static function clienteLogado()
    {

        // verifica se existe um cliente com sessao
        return isset($_SESSION['cliente']);
    }

    // ===========================================================
    public static function criarHash($num_caracteres = 12)
    {

        // criar hashes
        $chars = '01234567890123456789abcdefghijklmnopqrstuwxyzabcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZABCDEFGHIJKLMNOPQRSTUWXYZ';
        return substr(str_shuffle($chars), 0, $num_caracteres);
    }
}
