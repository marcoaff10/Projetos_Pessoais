<?php

// Coleção de rotas

$rotas = [
    'inicio' => 'main@index',
    'loja' => 'main@loja'
];

// Definindo uma ação por defeito
$acao = 'inicio';

// Verificando se exsite $acao na query string

if (isset($_GET['a'])) {
    // Verificando se a ação existe nas rotas
    if (!key_exists( $_GET['a'], $rotas)) {
        $acao = 'inicio';
    } else {
        $acao = $_GET['a'];
    }
}

// Tratando a definição das rotas
$partes = explode('@', $rotas[$acao]);

$controlador = ucfirst($partes[0]);
$controlador = 'App\\Classes\\Controllers\\'.$controlador;
$metodo = $partes[1];

$ctr = new $controlador();
$ctr->$metodo();
