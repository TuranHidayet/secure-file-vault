<?php
session_start();

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/Models/FileModel.php';
require_once __DIR__ . '/../app/Controllers/FileController.php';
require_once __DIR__ . '/../core/helpers.php';

$router = new Router();
$router->dispatch();