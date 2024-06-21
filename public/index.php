<?php





// Abrindo a sessão

use App\Classes\Rotas\Rotas;

session_start();

// Incluindo configurações, rotas e classes
require_once('../config.php');

// Carregando todas as classes do projeto
require_once('../vendor/autoload.php');

// Carregando sistema de rotas
$rotas = [
    'inicio' => 'main@index',
    'loja' => 'main@loja',
    'carrinho' => 'main@carrinho',
    'novoCliente' => 'viewsCliente@novoCliente',
    'loginCliente' => 'viewsCliente@loginCliente',
    'minhaConta' => 'viewsCliente@minhaConta', 
    'registrarCliente' => 'authCliente@registrarCliente',
    'logoutCliente' => 'authCliente@logout',
];

$acao = 'inicio';

$rota = new Rotas($rotas, $acao);
$rota->roteamento();
