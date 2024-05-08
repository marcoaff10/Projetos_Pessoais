<?php

session_start();

use bng\System\Router;

require_once('../vendor/autoload.php');

Router::dispatch();