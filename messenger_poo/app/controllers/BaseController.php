<?php

namespace messenger\Controllers;

abstract class BaseController
{
    public function view($view, $data = [])
    {
        // check if data is array
        if (!is_array($data)) {
            die('Dados não são u array: ' . var_dump($data));
        }

        // transforms data into variables
        extract($data);

        // include the file if exists
        if(file_exists("../app/views/$view.php")) {
            require_once("../app/views/$view.php");
        } else {
            die('Tela não encontrada: ' . $view);
        }
    }
}