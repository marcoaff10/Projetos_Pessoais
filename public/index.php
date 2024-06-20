<?php


// Abrindo a sessão

use App\Classes\Models\Database;

session_start();

// Incluindo configurações, rotas e classes
require_once('../config.php');

// Carregando todas as classes do projeto
require_once('../vendor/autoload.php');
