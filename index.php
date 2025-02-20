<?php
require 'config.php';
require 'app/core/Router.php';
require 'app/core/Database.php';

$router = new Router();
$router->route();
?>
