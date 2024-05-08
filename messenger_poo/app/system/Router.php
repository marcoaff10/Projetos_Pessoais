<?php

namespace messenger\System;

class Router 
{
    public static function dispatch()
    {

        // main route values
        $httpverb = $_SERVER['REQUEST_METHOD'];

        // default controller and method
        $controller = 'main';
        $method = 'index';


        // check uri parameters
        if (isset($_GET['ct'])) {
            $controller = $_GET['ct'];
        }

        if (isset($_GET['mt'])) {
            $method = $_GET['mt'];
        }


        // method parameters
        $parameters = $_GET;


        // remove controller from parameters
        if (key_exists("ct", $parameters)) {
            unset($parameters['ct']);
        }

        // remove method from parameters
        if (key_exists("mt", $parameters)) {
            unset($parameters['mt']);
        }

        // tries to instanciate the controller and execute the method
        try {
            $class = "messenger\Controllers\\$controller";
            $controller = new $class();
            $controller->$method(...$parameters);
        } catch (\Throwable $th) {
            die('Acesso inválido. ' . PHP_EOL . $th->getMessage() . ' : ' . $th->getFile() . $th->getLine());
        }
    }
}