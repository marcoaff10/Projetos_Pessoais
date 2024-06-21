<?php

namespace App\Classes\Rotas;

class Rotas
{
    public array $rotas;
    public string $acao;
    
    public function __construct($rotas, $acao)
    {
        $this->rotas = $rotas;
        $this->acao = $acao;
    }
    public function roteamento()
    {

        // verifica se existe a ação na query string
        if (isset($_GET['a'])) {

            // verifica se a ação existe nas rotas
            if (!key_exists($_GET['a'], $this->rotas)) {
                $this->acao = 'inicio';
            } else {
                $this->acao = $_GET['a'];
            }
        }

        // trata a definição da rota
        $partes = explode('@', $this->rotas[$this->acao]);
        $controlador = 'App\\Classes\\Controllers\\' . ucfirst($partes[0]);
        $metodo = $partes[1];

        $ctr = new $controlador();
        $ctr->$metodo();
    }
}
