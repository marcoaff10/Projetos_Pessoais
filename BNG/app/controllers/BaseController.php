<?php

namespace bng\Controllers;

abstract class BaseController
{
    public function view($view, $data = [])
    {
        // check if data is array
        if(!is_array($data)){
            die("Data is not an array: " . var_dump($data));
        }

        // transforms data into variables
        extract($data);

        // includes the file if exists
        if(file_exists("../app/views/$view.php")){
            require_once("../app/views/$view.php");
        } else {
            die("View não encontrada: " . $view);
        }
    }
}