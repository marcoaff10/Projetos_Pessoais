<?php

use messenger\System\Router;

session_start();

require_once('../vendor/autoload.php');

Router::dispatch();